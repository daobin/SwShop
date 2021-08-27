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
        $skuImgList = $this->getSkuImageListBySkuArr($shopId, $prodSkuArr, true);

        $ret = [];
        foreach ($prodList as $prodId => $prodInfo) {
            $sku = $prodSkuArr[$prodId];
            if (empty($skuQtyPriceList[$sku]) || empty($skuImgList[$sku])) {
                continue;
            }

            $ret[] = array_merge($prodList[$prodId], $skuQtyPriceList[$sku], $skuImgList[$sku]);
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
        $skuImgList = $this->getSkuImageListBySkuArr($shopId, $prodSkuArr, true);

        $ret = [];
        foreach ($prodList as $prodId => $prodInfo) {
            $sku = $prodSkuArr[$prodId];
            if (empty($skuQtyPriceList[$sku]) || empty($skuImgList[$sku])) {
                continue;
            }

            $ret[] = array_merge($prodList[$prodId], $skuQtyPriceList[$sku], $skuImgList[$sku]);
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
        } else {
            $prodInfo['sku_list'] = array_column($skuList, null, 'sku');
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

    public function getSkuImageListBySkuArr(int $shopId, array $skuArr, bool $returnDefault = false): array
    {
        if ($shopId <= 0 || empty($skuArr)) {
            return [];
        }

        $skuArr = array_values(array_unique($skuArr));
        $where = ['shop_id' => $shopId, 'sku' => ['in', $skuArr]];
        $fields = ['product_image_id', 'sku', 'image_path', 'image_name', 'sort', 'updated_at', 'updated_by'];

        $rows = $this->dbHelper->table('product_image')->where($where)->fields($fields)
            ->orderBy(['sort' => 'asc'])->select();
        if (empty($rows)) {
            return [];
        }

        $skuImageList = [];
        if ($returnDefault === true) {
            foreach ($rows as $row) {
                if (isset($skuImageList[$row['sku']])) {
                    continue;
                }

                $skuImageList[$row['sku']] = $row;
            }
        } else {
            foreach ($rows as $row) {
                $skuImageList[$row['sku']][$row['sort']] = $row;
            }
        }
        unset($rows, $row);

        return $skuImageList;

    }

    public function saveProduct(array $prodData, array $prodDescData, array $prodSkuData): int
    {
        if (empty($prodData) || empty($prodDescData) || empty($prodSkuData)) {
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
                unset($prodData['product_id']);
                $prodId = $this->dbHelper->table('product')->insert($prodData);

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

                foreach ($prodSkuData as $data) {
                    if (empty($data['qty_price_data']) || empty($data['img_data'])) {
                        return 0;
                    }

                    foreach ($data['qty_price_data'] as $qtyPrice) {
                        $firstProdPrice = $firstProdPrice === -1 ? $qtyPrice['price'] : $firstProdPrice;
                        $prodQtyTotal += $qtyPrice['qty'];

                        $qtyPrice['product_id'] = $prodId;
                        $this->dbHelper->table('product_qty_price')->insert($qtyPrice);
                    }

                    foreach ($data['img_data'] as $img) {
                        $this->dbHelper->table('product_image')->insert($img);
                    }
                }

            } else {
                unset($prodData['created_at'], $prodData['created_by']);
                $this->dbHelper->table('product')->where(
                    ['shop_id' => $shopId, 'product_id' => $prodId])->update($prodData);

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

                $qtyPriceList = $this->getSkuQtyPriceListBySkuArr($shopId, $skuArr);
                $imgList = $this->getSkuImageListBySkuArr($shopId, $skuArr);

                foreach ($prodSkuData as $sku => $data) {
                    if (empty($data['qty_price_data']) || empty($data['img_data'])) {
                        return 0;
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

                    foreach ($data['img_data'] as $img) {
                        $sort = $img['sort'];
                        if (empty($imgList[$sku][$sort])) {
                            $this->dbHelper->table('product_image')->insert($img);
                        } else {
                            $prodImgId = $imgList[$sku][$sort]['product_image_id'];
                            unset($img['created_at'], $img['created_by'], $imgList[$sku][$sort]);

                            $this->dbHelper->table('product_image')->where(
                                ['shop_id' => $shopId, 'product_image_id' => $prodImgId])->update($img);
                        }
                    }
                }

                // 删除多余的商品库存
                if (!empty($qtyPriceList)) {
                    foreach ($qtyPriceList as $sku => $qtyPrice) {
                        if (empty($qtyPrice)) {
                            $delSkuArr[] = $sku;
                            continue;
                        }

                        $this->dbHelper->table('product_qty_price')->where(
                            ['shop_id' => $shopId, 'sku' => $sku, 'warehouse_code' => ['in', array_keys($qtyPrice)]])->delete();
                    }
                }

                // 删除多余的商品图片
                if (!empty($imgList)) {
                    foreach ($imgList as $sku => $img) {
                        if (empty($img)) {
                            $delSkuArr[] = $sku;
                            continue;
                        }

                        $this->dbHelper->table('product_image')->where(
                            ['shop_id' => $shopId, 'sku' => $sku, 'sort' => ['in', array_keys($img)]])->delete();
                    }
                }


                // 需要删除的SKU
                $delSkuArr = empty($prodInfo['sku_list']) ? [] : array_diff(array_keys($prodInfo['sku_list']), $skuArr);
                if (!empty($delSkuArr)) {
                    $this->dbHelper->table('product_sku')->where(
                        ['shop_id' => $shopId, 'sku' => ['in', $delSkuArr]])->delete();

                    $this->dbHelper->table('product_image')->where(
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
            print_r(__CLASS__ . ' :: ' . $e->getMessage());
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
            print_r(__CLASS__ . ' :: ' . $e->getMessage());
            $res = 0;
            $this->dbHelper->rollBack();
        }

        return $res;
    }
}
