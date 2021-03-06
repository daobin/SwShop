<?php
/**
 * 商品相关业务逻辑
 * User: dao bin
 * Date: 2021/7/22
 * Time: 16:22
 */
declare(strict_types=1);

namespace App\Biz;

use App\Helper\DbHelper;

class ProductBiz
{
    private $dbHelper;
    private $cateList;
    private $cateLevelData;
    public $count;

    public function __construct()
    {
        $this->dbHelper = new DbHelper();
        $this->cateList = [];
        $this->count = 0;
    }

    public function getAttrGroupList(int $shopId, string $langCode): array
    {
        if ($shopId <= 0 || empty($langCode)) {
            return [];
        }

        return $this->dbHelper->table('product_attribute_group', 'group')
            ->join('product_attribute_group_description', 'desc', ['group.attr_group_id' => 'desc.attr_group_id'])
            ->where(['group.shop_id' => $shopId, 'desc.language_code' => $langCode])
            ->orderBy(['desc.group_name' => 'asc'])
            ->fields(['desc.attr_group_id', 'desc.group_name', 'desc.updated_at', 'desc.updated_by'])->select();
    }

    public function getAttrGroupById(int $shopId, int $groupId): array
    {
        if ($shopId <= 0 || $groupId <= 0) {
            return [];
        }

        $attrGroup = $this->dbHelper->table('product_attribute_group')
            ->where(['shop_id' => $shopId, 'attr_group_id' => $groupId])->find();
        if (empty($attrGroup)) {
            return [];
        }

        $attrGroup['desc_list'] = $this->dbHelper->table('product_attribute_group_description')
            ->where(['shop_id' => $shopId, 'attr_group_id' => $groupId])
            ->fields(['group_name', 'language_code'])->select();

        $attrGroup['desc_list'] = empty($attrGroup['desc_list']) ? [] : array_column(
            $attrGroup['desc_list'], 'group_name', 'language_code');

        return $attrGroup;
    }

    public function getAttrGroupIdByName(int $shopId, string $langCode, string $groupName): int
    {
        if ($shopId <= 0 || empty($langCode) || empty($groupName)) {
            return 0;
        }

        $groupId = $this->dbHelper->table('product_attribute_group_description')
            ->where(['shop_id' => $shopId, 'language_code' => $langCode, 'group_name' => $groupName])
            ->fields(['attr_group_id'])->find();

        return $groupId ? (int)reset($groupId) : 0;
    }

    public function saveAttrGroup($data): int
    {
        if (empty($data['shop_id']) || empty($data['group_names'])) {
            return 0;
        }

        $shopId = (int)$data['shop_id'];
        $groupId = (int)$data['group_id'];
        $operator = $data['operator'] ?? '';

        $res = 1;
        $this->dbHelper->beginTransaction();
        try {
            $time = time();

            $groupInfo = $this->getAttrGroupById($shopId, $groupId);
            if ($groupInfo) {
                $this->dbHelper->table('product_attribute_group')
                    ->where(['shop_id' => $shopId, 'attr_group_id' => $groupId])
                    ->update(['updated_at' => $time, 'updated_by' => $operator]);
            } else {
                $groupId = $this->dbHelper->table('product_attribute_group')
                    ->insert([
                        'shop_id' => $shopId,
                        'created_at' => $time,
                        'created_by' => $operator,
                        'updated_at' => $time,
                        'updated_by' => $operator
                    ]);
            }

            foreach ($data['group_names'] as $langCode => $groupName) {
                $langCode = strtolower($langCode);
                if (empty($groupInfo['desc_list'][$langCode])) {
                    $this->dbHelper->table('product_attribute_group_description')
                        ->insert([
                            'shop_id' => $shopId,
                            'attr_group_id' => $groupId,
                            'language_code' => $langCode,
                            'group_name' => $groupName,
                            'created_at' => $time,
                            'created_by' => $operator,
                            'updated_at' => $time,
                            'updated_by' => $operator
                        ]);
                } else {
                    $this->dbHelper->table('product_attribute_group_description')
                        ->where(['shop_id' => $shopId, 'attr_group_id' => $groupId, 'language_code' => $langCode])
                        ->update(['group_name' => $groupName, 'updated_at' => $time, 'updated_by' => $operator]);
                }
            }

            $this->dbHelper->commit();
        } catch (\Throwable $e) {
            $res = 0;
            $this->dbHelper->rollBack();
        }

        return $res;
    }

    public function getFeaturedProductList(int $shopId, string $langCode, string $warehouseCode): array
    {
        if ($shopId <= 0) {
            return [];
        }

        $where = [
            'shop_id' => $shopId,
            'product_status' => 1,
            'language_code' => $langCode
        ];
        $orderBy = ['is_sold_out' => 'asc', 'sort' => 'asc', 'product_id' => 'desc'];
        $prodList = $this->getProductList($where, $orderBy, 1, 12);
        if (empty($prodList)) {
            return [];
        }

        $prodList = array_column($prodList, null, 'product_id');
        $prodSkuArr = $this->getDefaultSkuArrByProdIds($shopId, array_keys($prodList));
        if (empty($prodSkuArr)) {
            return [];
        }

        $skuQtyPriceList = $this->getSkuQtyPriceListBySkuArr($shopId, $prodSkuArr, $warehouseCode, true);
        $prodImgList = $this->getProdImageListByProdIds($shopId, array_keys($prodList), true);

        $ret = [];
        foreach ($prodList as $prodId => $prodInfo) {
            $sku = $prodSkuArr[$prodId];
            if (empty($skuQtyPriceList[$sku]) || empty($prodImgList[$prodId])) {
                continue;
            }

            $ret[] = array_merge($prodList[$prodId], $skuQtyPriceList[$sku], $prodImgList[$prodId]);
        }

        return $ret;
    }

    public function getProductAllByList(int $shopId, string $warehouseCode, array $prodList): array
    {
        if ($shopId <= 0 || empty($prodList)) {
            return [];
        }

        $prodList = array_column($prodList, null, 'product_id');
        $prodSkuArr = $this->getDefaultSkuArrByProdIds($shopId, array_keys($prodList));
        if (empty($prodSkuArr)) {
            return [];
        }

        $skuQtyPriceList = $this->getSkuQtyPriceListBySkuArr($shopId, $prodSkuArr, $warehouseCode);
        $prodImgList = $this->getProdImageListByProdIds($shopId, array_keys($prodList), true);

        $ret = [];
        foreach ($prodList as $prodId => $prodInfo) {
            $sku = $prodSkuArr[$prodId];
            if (empty($skuQtyPriceList[$sku]) || empty($prodImgList[$prodId])) {
                continue;
            }

            $ret[] = array_merge($prodList[$prodId], $skuQtyPriceList[$sku], $prodImgList[$prodId]);
        }

        return $ret;
    }

    public function getDefaultSkuArrByProdIds(int $shopId, array $prodIds): array
    {
        if ($shopId <= 0 || empty($prodIds)) {
            return [];
        }

        $skuArr = $this->dbHelper->table('product_sku')
            ->where(['shop_id' => $shopId, 'product_id' => ['in', $prodIds], 'sort' => 0])
            ->fields(['sku', 'product_id'])->select();
        if (empty($skuArr)) {
            return [];
        }

        $skuArr = array_column($skuArr, 'sku', 'product_id');

        return $skuArr;
    }

    public function getProductList(array $condition, array $orderBy = [], int $page = 1, int $pageSize = 10): array
    {
        if (empty($condition['shop_id']) || empty($condition['language_code'])) {
            return [];
        }

        $shopId = (int)$condition['shop_id'];
        $langCode = trim($condition['language_code']);

        $where = ['shop_id' => $shopId];
        foreach ($condition as $key => $value) {
            switch ($key) {
                case 'product_category_id':
                case 'product_status':
                    $where[$key] = (int)$value;
                    break;
                case 'product_ids':
                    $where['product_id'] = ['in', $value];
                    break;
                case 'category_ids':
                    $where['product_category_id'] = ['in', $value];
                    break;
            }
        }

        $prodIds = [];
        if (isset($condition['keywords'])) {
            $keywordsData = $this->getProdSkuListBySkuArr($shopId, [$condition['keywords']]);
            if (!empty($keywordsData)) {
                $keywordsData = reset($keywordsData);
                $prodIds[] = $keywordsData['product_id'];
            }

            $keywordsData = $this->getProdIdsByName($shopId, $condition['keywords'], $langCode);
            if (!empty($keywordsData)) {
                $prodIds += $keywordsData;
            }

            $prodIds = $prodIds ? $prodIds : [0];
            $where['product_id'] = ['in', $prodIds];
        }

        if (empty($orderBy)) {
            $orderBy = ['product_id' => 'desc'];
        }

        $this->count = $this->dbHelper->table('product')->where($where)->count();
        if ($this->count <= 0) {
            return [];
        }

        $pageTotal = (int)ceil($this->count / $pageSize);
        $page = $page > $pageTotal ? $pageTotal : $page;

        $fields = [
            'product_id', 'product_category_id', 'product_status', 'product_url', 'sort', 'is_sold_out', 'price',
            'weight', 'weight_unit', 'width', 'length', 'height', 'size_unit', 'updated_at', 'updated_by'
        ];
        $prodList = $this->dbHelper->table('product')->where($where)->fields($fields)
            ->orderBy($orderBy)->page($page, $pageSize)->select();
        if (empty($prodList)) {
            return [];
        }

        $prodList = array_column($prodList, null, 'product_id');
        $prodIds = array_keys($prodList);

        $where = [
            'shop_id' => $shopId,
            'product_id' => ['in', $prodIds],
            'language_code' => $langCode
        ];
        $fields = ['product_description_id', 'product_id', 'language_code', 'product_name', 'product_description',
            'product_description_m', 'meta_title', 'meta_keywords', 'meta_description', 'updated_at', 'updated_by'];
        $descList = $this->dbHelper->table('product_description')->where($where)->fields($fields)->select();
        if (empty($descList)) {
            return [];
        }

        $descList = array_column($descList, null, 'product_id');

        $ret = [];
        foreach ($prodIds as $prodId) {
            if (empty($descList[$prodId])) {
                continue;
            }

            $cateLevel = $this->getCateLevelByChildId($shopId, $prodList[$prodId]['product_category_id'], $langCode);
            if (empty($cateLevel)) {
                $prodList[$prodId]['cate_level'] = '';
            } else {
                $cateLevel = array_column($cateLevel, 'category_name');
                $cateLevel = array_reverse($cateLevel);
                $prodList[$prodId]['cate_level'] = implode(' >> ', $cateLevel);
            }

            $ret[] = array_merge($prodList[$prodId], $descList[$prodId]);
        }

        unset($prodList, $descList);
        return $ret;
    }

    private function getProdIdsByName(int $shopId, string $prodName, string $langCode): array
    {
        $prodName = trim($prodName);
        if ($shopId <= 0 || empty($prodName)) {
            return [];
        }

        $prodName = preg_replace('/[\s]+/', ',', $prodName);
        $prodName = explode(',', $prodName);

        $where = [
            'shop_id' => $shopId,
            'language_code' => $langCode
        ];
        $whereOr = [
            'product_name' => ['like in', $prodName]
        ];
        $prodIds = $this->dbHelper->table('product_description')->where($where)->whereOr($whereOr)
            ->fields(['product_id'])->select();
        if (empty($prodIds)) {
            return [];
        }

        $prodIds = array_column($prodIds, null, 'product_id');
        return array_keys($prodIds);
    }

    public function getProductById(int $shopId, int $prodId): array
    {
        if ($shopId <= 0 || $prodId <= 0) {
            return [];
        }

        $fields = [
            'product_id', 'product_category_id', 'product_status', 'product_url', 'sort',
            'weight', 'weight_unit', 'width', 'length', 'height', 'size_unit',
            'created_at', 'created_by', 'updated_at', 'updated_by'
        ];
        $prodInfo = $this->dbHelper->table('product')->where(['shop_id' => $shopId, 'product_id' => $prodId])
            ->fields($fields)->find();
        if (empty($prodInfo)) {
            return [];
        }

        $fields = [
            'product_description_id', 'product_id', 'language_code', 'product_name', 'product_description',
            'product_description_m', 'meta_title', 'meta_keywords', 'meta_description', 'updated_at', 'updated_by'
        ];
        $descList = $this->dbHelper->table('product_description')->where(
            ['shop_id' => $shopId, 'product_id' => $prodId])->fields($fields)->select();
        if (empty($descList)) {
            $prodInfo['desc_list'] = [];
        } else {
            $prodInfo['desc_list'] = array_column($descList, null, 'language_code');
        }

        $fields = ['product_sku_id', 'product_id', 'sku', 'sort', 'updated_at', 'updated_by'];
        $skuList = $this->dbHelper->table('product_sku')->where(['shop_id' => $shopId, 'product_id' => $prodId])
            ->orderBy(['sort' => 'asc', 'product_sku_id' => 'asc'])->fields($fields)->select();
        if (empty($skuList)) {
            $prodInfo['sku_list'] = [];
            $prodInfo['attr_value_list'] = [];
            $prodInfo['attr_image_list'] = [];
        } else {
            $prodInfo['sku_list'] = array_column($skuList, null, 'sku');
            $skuAttrList = $this->dbHelper->table('product_sku_attribute')
                ->where(['shop_id' => $shopId, 'sku' => ['in', array_keys($prodInfo['sku_list'])]])
                ->fields(['sku', 'attr_group_id', 'attr_value_name', 'image_path', 'image_name', 'updated_at'])->select();
            foreach ($skuAttrList as $skuAttr) {
                $grpId = $skuAttr['attr_group_id'];
                $attrVal = $skuAttr['attr_value_name'];
                $prodInfo['attr_value_list'][$grpId][$skuAttr['sku']] = $attrVal;
                if (!empty($skuAttr['image_path']) && !empty($skuAttr['image_name'])) {
                    $prodInfo['attr_image_list'][$grpId][$attrVal] = $skuAttr['image_path'] . '/' . $skuAttr['image_name'] . '?' . $skuAttr['updated_at'];
                }
            }
        }

        return $prodInfo;
    }

    public function getProdSkuListBySkuArr(int $shopId, array $skuArr, ?int $notProdId = null)
    {
        if ($shopId <= 0 || empty($skuArr)) {
            return [];
        }

        $skuArr = array_values(array_unique($skuArr));
        $where = ['shop_id' => $shopId, 'sku' => ['in', $skuArr]];
        if (is_int($notProdId)) {
            $where['product_id'] = ['<>', $notProdId];
        }

        $fields = ['product_sku_id', 'product_id', 'sku', 'sort', 'updated_at', 'updated_by'];
        $skuList = $this->dbHelper->table('product_sku')->where($where)->fields($fields)
            ->orderBy(['sort' => 'asc', 'product_sku_id' => 'asc'])->select();

        return empty($skuList) ? [] : array_column($skuList, null, 'sku');
    }

    public function getSkuAttrIdsBySkuArr(int $shopId, array $skuArr): array
    {
        if ($shopId <= 0 || empty($skuArr)) {
            return [];
        }

        $skuAttrList = $this->dbHelper->table('product_sku_attribute')
            ->where(['shop_id' => $shopId, 'sku' => ['in', $skuArr]])
            ->fields(['sku', 'attr_group_id', 'attr_value_name', 'product_sku_attribute_id'])->select();
        if (empty($skuAttrList)) {
            return [];
        }

        $res = [];
        foreach ($skuAttrList as $skuAttr) {
            $grpId = (int)$skuAttr['attr_group_id'];
            $valName = strtolower($skuAttr['attr_value_name']);
            $res[$skuAttr['sku']][$grpId][$valName] = $skuAttr['product_sku_attribute_id'];
        }

        return $res;
    }

    public function getSkuAttrListBySkuArr(int $shopId, array $skuArr): array
    {
        if ($shopId <= 0 || empty($skuArr)) {
            return [];
        }

        $skuAttrList = $this->dbHelper->table('product_sku_attribute')
            ->where(['shop_id' => $shopId, 'sku' => ['in', $skuArr]])
            ->fields(['sku', 'attr_value_name', 'image_path', 'image_name', 'updated_at'])->select();
        if (empty($skuAttrList)) {
            return [];
        }

        $res = [];
        foreach ($skuAttrList as $skuAttr) {
            $res[$skuAttr['sku']][$skuAttr['attr_value_name']] = $skuAttr;
        }

        return $res;
    }

    public function getSkuQtyPriceListBySkuArr(int $shopId, array $skuArr, ?string $warehouseCode = null, bool $isValid = false): array
    {
        if ($shopId <= 0 || empty($skuArr)) {
            return [];
        }

        $skuArr = array_values(array_unique($skuArr));
        $where = ['shop_id' => $shopId, 'sku' => ['in', $skuArr]];
        if ($warehouseCode !== null) {
            $where['warehouse_code'] = $warehouseCode;
        }
        if ($isValid) {
            $where['qty'] = ['>', 0];
            $where['price'] = ['>', 0];
        }

        $fields = ['product_qty_price_id', 'product_id', 'sku', 'warehouse_code', 'qty', 'price', 'list_price', 'updated_at', 'updated_by'];

        $rows = $this->dbHelper->table('product_qty_price')->where($where)->fields($fields)->select();
        if (empty($rows)) {
            return [];
        }

        $skuQtyPriceList = [];
        if ($warehouseCode != null) {
            foreach ($rows as $row) {
                $skuQtyPriceList[$row['sku']] = $row;
            }
        } else {
            foreach ($rows as $row) {
                $skuQtyPriceList[$row['sku']][$row['warehouse_code']] = $row;
            }
        }
        unset($rows, $row);

        return $skuQtyPriceList;

    }

    public function getProdImageListByProdIds(int $shopId, array $prodIds, bool $returnDefault = false): array
    {
        if ($shopId <= 0 || empty($prodIds)) {
            return [];
        }

        $prodIds = array_values(array_unique($prodIds));
        $where = ['shop_id' => $shopId, 'product_id' => ['in', $prodIds]];
        $fields = ['product_image_id', 'product_id', 'image_path', 'image_name', 'sort', 'updated_at', 'updated_by'];

        $rows = $this->dbHelper->table('product_image')->where($where)->fields($fields)
            ->orderBy(['sort' => 'asc'])->select();
        if (empty($rows)) {
            return [];
        }

        $imageList = [];
        if ($returnDefault === true) {
            foreach ($rows as $row) {
                if (isset($imageList[$row['product_id']])) {
                    continue;
                }

                $imageList[$row['product_id']] = $row;
            }
        } else {
            foreach ($rows as $row) {
                $imageList[$row['product_id']][$row['sort']] = $row;
            }
        }
        unset($rows, $row);

        return $imageList;

    }

    public function saveProduct(array $prodData, array $prodImageData, array $prodDescData, array $prodSkuData): int
    {
        if (empty($prodData) || empty($prodImageData) || empty($prodDescData) || empty($prodSkuData)) {
            return 0;
        }

        $shopId = $prodData['shop_id'] ?? 0;
        $prodId = $prodData['product_id'] ?? 0;
        $prodInfo = $this->getProductById($shopId, $prodId);

        $skuArr = array_keys($prodSkuData);
        if (empty($prodData['product_status'])) {
            $prodData['product_status'] = 1;
        }

        $firstProdPrice = -1;
        $prodQtyTotal = 0;

        $this->dbHelper->beginTransaction();
        try {
            if (empty($prodInfo)) {
                // 商品新增
                $prodData['price'] = 0;
                unset($prodData['product_id']);
                $prodId = $this->dbHelper->table('product')->insert($prodData);

                foreach ($prodImageData as $imgSort => $prodImage) {
                    $prodImage['product_id'] = $prodId;
                    $prodImage['sort'] = $imgSort;
                    $this->dbHelper->table('product_image')->insert($prodImage);
                }

                foreach ($prodDescData as $prodDesc) {
                    $prodDesc['product_id'] = $prodId;
                    $this->dbHelper->table('product_description')->insert($prodDesc);
                }

                foreach ($skuArr as $sort => $sku) {
                    $this->dbHelper->table('product_sku')->insert([
                        'shop_id' => $shopId,
                        'product_id' => $prodId,
                        'sku' => $sku,
                        'sort' => $sort,
                        'created_at' => $prodData['created_at'],
                        'created_by' => $prodData['created_by'],
                        'updated_at' => $prodData['updated_at'],
                        'updated_by' => $prodData['updated_by']
                    ]);
                }

                foreach ($prodSkuData as $sku => $data) {
                    if (empty($data['attributes'])) {
                        throw new \PDOException('Product Attribute Empty');
                    }

                    if (empty($data['qty_price_data'])) {
                        throw new \PDOException('Product Qty and Price Empty');
                    }

                    foreach ($data['attributes'] as $groupId => $attrList) {
                        foreach ($attrList as $attr) {
                            $this->dbHelper->table('product_sku_attribute')->insert([
                                'shop_id' => $shopId,
                                'product_id' => $prodId,
                                'sku' => $sku,
                                'attr_group_id' => $groupId,
                                'attr_value_name' => $attr['value_name'],
                                'image_path' => $attr['image_path'],
                                'image_name' => $attr['image_name'],
                                'created_at' => $prodData['created_at'],
                                'created_by' => $prodData['created_by'],
                                'updated_at' => $prodData['updated_at'],
                                'updated_by' => $prodData['updated_by']
                            ]);
                        }
                    }

                    foreach ($data['qty_price_data'] as $qtyPrice) {
                        $firstProdPrice = $firstProdPrice === -1 ? $qtyPrice['price'] : $firstProdPrice;
                        $prodQtyTotal += $qtyPrice['qty'];

                        $qtyPrice['product_id'] = $prodId;
                        $this->dbHelper->table('product_qty_price')->insert($qtyPrice);
                    }
                }

            } else {
                // 商品修改
                unset($prodData['created_at'], $prodData['created_by']);
                $this->dbHelper->table('product')->where(
                    ['shop_id' => $shopId, 'product_id' => $prodId])->update($prodData);

                $prodImgList = $this->getProdImageListByProdIds($shopId, [$prodId]);
                $prodImgList = $prodImgList[$prodId] ?? [];
                foreach ($prodImageData as $imgSort => $prodImage) {
                    $prodImage['product_id'] = $prodId;
                    $prodImage['sort'] = $imgSort;

                    if (empty($prodImgList[$imgSort])) {
                        $this->dbHelper->table('product_image')->insert($prodImage);
                    } else {
                        $prodImgId = $prodImgList[$imgSort]['product_image_id'];
                        unset($prodImage['created_at'], $prodImage['created_by'], $prodImgList[$imgSort]);

                        $this->dbHelper->table('product_image')->where(
                            ['shop_id' => $shopId, 'product_image_id' => $prodImgId])->update($prodImage);
                    }
                }

                foreach ($prodDescData as $langCode => $prodDesc) {
                    if (empty($prodInfo['desc_list'][$langCode])) {
                        $this->dbHelper->table('product_description')->insert($prodDesc);
                    } else {
                        $prodDescId = $prodInfo['desc_list'][$langCode]['product_description_id'];
                        unset($prodDesc['created_at'], $prodDesc['created_by']);

                        $this->dbHelper->table('product_description')->where(
                            ['shop_id' => $shopId, 'product_description_id' => $prodDescId])->update($prodDesc);
                    }
                }

                foreach ($skuArr as $sort => $sku) {
                    if (empty($prodInfo['sku_list'][$sku])) {
                        $this->dbHelper->table('product_sku')->insert([
                            'shop_id' => $shopId,
                            'product_id' => $prodId,
                            'sku' => $sku,
                            'sort' => $sort,
                            'created_at' => $prodInfo['created_at'],
                            'created_by' => $prodInfo['created_by'],
                            'updated_at' => $prodInfo['updated_at'],
                            'updated_by' => $prodInfo['updated_by']
                        ]);
                    } else {
                        $prodSkuId = $prodInfo['sku_list'][$sku]['product_sku_id'];
                        $this->dbHelper->table('product_sku')->where(
                            ['shop_id' => $shopId, 'product_sku_id' => $prodSkuId])->update([
                            'shop_id' => $shopId,
                            'product_id' => $prodId,
                            'sku' => $sku,
                            'sort' => $sort,
                            'updated_at' => $prodInfo['updated_at'],
                            'updated_by' => $prodInfo['updated_by']
                        ]);
                    }
                }

                $skuAttrIds = $this->getSkuAttrIdsBySkuArr($shopId, $skuArr);
                $qtyPriceList = $this->getSkuQtyPriceListBySkuArr($shopId, $skuArr);

                foreach ($prodSkuData as $sku => $data) {
                    if (empty($data['attributes'])) {
                        throw new \PDOException('Product Attribute Empty');
                    }

                    if (empty($data['qty_price_data'])) {
                        throw new \PDOException('Product Qty and Price Empty');
                    }

                    foreach ($data['attributes'] as $groupId => $attrList) {
                        foreach ($attrList as $attr) {
                            if (empty($skuAttrIds[$sku][$groupId][strtolower($attr['value_name'])])) {
                                $this->dbHelper->table('product_sku_attribute')->insert([
                                    'shop_id' => $shopId,
                                    'product_id' => $prodId,
                                    'sku' => $sku,
                                    'attr_group_id' => $groupId,
                                    'attr_value_name' => $attr['value_name'],
                                    'image_path' => $attr['image_path'],
                                    'image_name' => $attr['image_name'],
                                    'created_at' => $prodData['created_at'],
                                    'created_by' => $prodData['created_by'],
                                    'updated_at' => $prodData['updated_at'],
                                    'updated_by' => $prodData['updated_by']
                                ]);
                            } else {
                                $this->dbHelper->table('product_sku_attribute')
                                    ->where([
                                        'product_sku_attribute_id' => $skuAttrIds[$sku][$groupId][strtolower($attr['value_name'])],
                                        'shop_id' => $shopId
                                    ])
                                    ->update([
                                        'shop_id' => $shopId,
                                        'product_id' => $prodId,
                                        'sku' => $sku,
                                        'attr_group_id' => $groupId,
                                        'attr_value_name' => $attr['value_name'],
                                        'image_path' => $attr['image_path'],
                                        'image_name' => $attr['image_name'],
                                        'updated_at' => $prodData['updated_at'],
                                        'updated_by' => $prodData['updated_by']
                                    ]);
                            }
                        }
                    }

                    foreach ($data['qty_price_data'] as $qtyPrice) {
                        $firstProdPrice = $firstProdPrice === -1 ? $qtyPrice['price'] : $firstProdPrice;
                        $prodQtyTotal += $qtyPrice['qty'];

                        $qtyPrice['product_id'] = $prodId;
                        if (empty($qtyPriceList[$sku])) {
                            $this->dbHelper->table('product_qty_price')->insert($qtyPrice);
                            continue;
                        }

                        $warehouseCode = $qtyPrice['warehouse_code'];
                        if (empty($qtyPriceList[$sku][$warehouseCode])) {
                            $this->dbHelper->table('product_qty_price')->insert($qtyPrice);
                        } else {
                            $qtyPriceId = $qtyPriceList[$sku][$warehouseCode]['product_qty_price_id'];
                            unset($qtyPrice['created_at'], $qtyPrice['created_by'], $qtyPriceList[$sku][$warehouseCode]);

                            $this->dbHelper->table('product_qty_price')->where(
                                ['shop_id' => $shopId, 'product_qty_price_id' => $qtyPriceId])->update($qtyPrice);
                        }
                    }
                }

                // 删除多余的商品图片
                if (!empty($prodImgList)) {
                    $this->dbHelper->table('product_image')->where(
                        ['shop_id' => $shopId, 'product_id' => $prodId, 'sort' => ['in', array_keys($prodImgList)]])->delete();
                }


                // 需要删除的SKU
                $delSkuArr = empty($prodInfo['sku_list']) ? [] : array_diff(array_keys($prodInfo['sku_list']), $skuArr);
                if (!empty($delSkuArr)) {
                    $this->dbHelper->table('product_sku')->where(
                        ['shop_id' => $shopId, 'sku' => ['in', $delSkuArr]])->delete();

                    $this->dbHelper->table('product_sku_attribute')->where(
                        ['shop_id' => $shopId, 'sku' => ['in', $delSkuArr]])->delete();

                    $this->dbHelper->table('product_qty_price')->where(
                        ['shop_id' => $shopId, 'sku' => ['in', $delSkuArr]])->delete();
                }
            }

            // 更新商品第一价格、商品总数量
            $this->dbHelper->table('product')->where(['shop_id' => $shopId, 'product_id' => $prodId])
                ->update([
                    'price' => $firstProdPrice >= 0 ? $firstProdPrice : 0,
                    'is_sold_out' => $prodQtyTotal > 0 ? 0 : 1,
                    'updated_at' => time()
                ]);

            $res = $prodId;
            $this->dbHelper->commit();
        } catch (\PDOException $e) {
            $res = 0;
            $this->dbHelper->rollBack();
        }

        return $res;
    }

    private function getCateList(int $shopId, string $language = 'en')
    {
        if ($shopId <= 0 || empty($language)) {
            return;
        }

        if (empty($this->cateList[$language])) {
            $fields = [
                'pc.product_category_id', 'parent_id', 'category_url', 'redirect_link', 'sort', 'category_status',
                'product_show_size', 'review_show_size', 'pc.updated_at', 'pc.updated_by',
                'product_category_description_id', 'language_code', 'category_name', 'category_description',
                'category_description_m', 'meta_title', 'meta_keywords', 'meta_description'
            ];

            $this->cateList[$language] = $this->dbHelper->table('product_category', 'pc')
                ->join('product_category_description', 'pc_desc',
                    ['pc.shop_id' => 'pc_desc.shop_id', 'pc.product_category_id' => 'pc_desc.product_category_id'])
                ->where(['pc.shop_id' => $shopId, 'pc_desc.language_code' => $language])
                ->orderBy(['pc.sort' => 'asc', 'pc.product_category_id' => 'asc'])->fields($fields)->select();
        }

        if (!empty($this->cateList[$language])) {
            $this->cateList[$language] = array_column($this->cateList[$language], null, 'product_category_id');
        }
        return;
    }

    public function getCateLevelByChildId(int $shopId, int $childId, string $language = 'en', bool $init = true): array
    {
        if ($init || empty($this->cateLevelData)) {
            $this->cateLevelData = [];
        }

        if ($shopId <= 0 || $childId <= 0 || empty($language)) {
            return [];
        }

        $this->getCateList($shopId, $language);
        if (empty($this->cateList[$language])) {
            return [];
        }

        if (isset($this->cateList[$language][$childId])) {
            $this->cateLevelData[] = $this->cateList[$language][$childId];
            if ($this->cateList[$language][$childId]['parent_id'] > 0) {
                $this->getCateLevelByChildId($shopId, $this->cateList[$language][$childId]['parent_id'], $language, false);
            }
        }

        return $this->cateLevelData;
    }

    public function getCategoryTree(int $shopId, int $parentId = 0, string $language = 'en', int $filterCateId = 0): array
    {
        if ($shopId <= 0 || empty($language)) {
            return [];
        }

        $this->getCateList($shopId, $language);
        if (empty($this->cateList[$language])) {
            return [];
        }

        $cateTree = [];
        foreach ($this->cateList[$language] as $cateInfo) {
            if ($cateInfo['parent_id'] !== $parentId) {
                continue;
            }

            if ($filterCateId > 0 && $cateInfo['product_category_id'] == $filterCateId) {
                continue;
            }

            // 用于 Layui 树
            if ($cateInfo['category_status']) {
                $cateInfo['title'] = xss_text($cateInfo['category_name']);
            } else {
                $cateInfo['title'] = '<del class=\"hd-color-red\">' . xss_text($cateInfo['category_name']) . '</del>';
            }
            $cateInfo['id'] = $cateInfo['product_category_id'];
            $cateInfo['spread'] = true;

            $children = $this->getCategoryTree($shopId, $cateInfo['product_category_id'], $language, $filterCateId);
            if (!empty($children)) {
                $cateInfo['children'] = $children;
            }

            $cateTree[] = $cateInfo;
        }

        return $cateTree;
    }

    public function getCategoryIds(int $shopId, int $cateId, bool $isValid = true): array
    {
        $cateIds = [$cateId];

        $cateTree = $this->getCategoryTree($shopId, $cateId);
        if (empty($cateTree)) {
            return $cateIds;
        }

        foreach ($cateTree as $cateInfo) {
            if ($isValid && (int)$cateInfo['category_status'] !== 1) {
                continue;
            }

            $cateIds[] = (int)$cateInfo['product_category_id'];
            if (empty($cateInfo['children'])) {
                continue;
            }

            foreach ($cateInfo['children'] as $child) {
                $childIds = $this->getCategoryIds($shopId, (int)$child['product_category_id'], $isValid);
                if (!empty($childIds)) {
                    $cateIds = array_merge($cateIds, $childIds);
                }
            }
        }

        $cateIds = array_unique($cateIds);
        $cateIds = array_values($cateIds);
        return $cateIds;
    }

    public function getCategoryById(int $shopId, int $cateId): array
    {
        if ($shopId <= 0 || $cateId <= 0) {
            return [];
        }

        $fields = [
            'product_category_id', 'parent_id', 'category_url', 'redirect_link', 'sort', 'category_status',
            'product_show_size', 'review_show_size', 'updated_at', 'updated_by'
        ];
        $cateInfo = $this->dbHelper->table('product_category')->where(
            ['shop_id' => $shopId, 'product_category_id' => $cateId])->fields($fields)->find();
        if (empty($cateInfo)) {
            return [];
        }

        $fields = [
            'product_category_description_id', 'product_category_id', 'language_code', 'category_name', 'category_description',
            'category_description_m', 'meta_title', 'meta_keywords', 'meta_description', 'updated_at', 'updated_by'
        ];
        $descList = $this->dbHelper->table('product_category_description')->where(
            ['shop_id' => $shopId, 'product_category_id' => $cateId])->fields($fields)->select();
        if (empty($descList)) {
            $cateInfo['desc_list'] = [];
        } else {
            $cateInfo['desc_list'] = array_column($descList, null, 'language_code');
        }

        return $cateInfo;
    }

    public function saveCategory(array $cateData, array $cateDescData): int
    {
        if (empty($cateData) || empty($cateDescData)) {
            return 0;
        }

        $shopId = $cateData['shop_id'] ?? 0;
        $cateId = $cateData['product_category_id'] ?? 0;
        $cateInfo = $this->getCategoryById($shopId, $cateId);

        $this->dbHelper->beginTransaction();
        try {
            if (empty($cateInfo)) {
                unset($cateData['product_category_id']);
                $cateId = $this->dbHelper->table('product_category')->insert($cateData);

                foreach ($cateDescData as $cateDesc) {
                    $cateDesc['product_category_id'] = $cateId;
                    $this->dbHelper->table('product_category_description')->insert($cateDesc);
                }
            } else {
                unset($cateData['created_at'], $cateData['created_by']);
                $this->dbHelper->table('product_category')->where(
                    ['shop_id' => $shopId, 'product_category_id' => $cateId])->update($cateData);

                foreach ($cateDescData as $langCode => $cateDesc) {
                    if (isset($cateInfo['desc_list'][$langCode])) {
                        $cateDescId = $cateInfo['desc_list'][$langCode]['product_category_description_id'];
                        unset($cateDesc['created_at'], $cateDesc['created_by']);

                        $this->dbHelper->table('product_category_description')->where(
                            ['shop_id' => $shopId, 'product_category_description_id' => $cateDescId])->update($cateDesc);
                    } else {
                        $this->dbHelper->table('product_category_description')->insert($cateDesc);
                    }
                }
            }

            $res = $cateId;
            $this->dbHelper->commit();
        } catch (\PDOException $e) {
//            print_r(__CLASS__ . ' :: ' . $e->getMessage());
            $res = 0;
            $this->dbHelper->rollBack();
        }

        return $res;
    }
}
