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

    public function __construct()
    {
        $this->dbHelper = new DbHelper();
        $this->cateList = [];
    }

    public function getProductList(array $condition, array $orderBy = [], int $page = 1, int $pageSize = 10): array
    {
        if (empty($condition['shop_id']) || empty($condition['language_code'])) {
            return [];
        }

        $where = ['shop_id' => (int)$condition['shop_id']];
        $prodList = $this->dbHelper->table('product')->where($where);
        if (!empty($orderBy)) {
            $prodList = $prodList->orderBy($orderBy);
        }
        $prodList = $prodList->page($page, $pageSize)->select();
        if (empty($prodList)) {
            return [];
        }

        $prodList = array_column($prodList, null, 'product_id');
        $prodIds = array_keys($prodList);

        $where = [
            'shop_id' => (int)$condition['shop_id'],
            'product_id' => ['in', $prodIds],
            'language_code' => trim($condition['language_code'])
        ];
        $descList = $this->dbHelper->table('product_description')->where($where)->select();
        if (empty($descList)) {
            return [];
        }

        foreach ($descList as $idx => $desc) {
            if (!empty($prodList[$desc['product_id']])) {
                $descList[$idx] = array_merge($prodList[$desc['product_id']], $desc);
            }
        }

        unset($prodList);
        return $descList;
    }

    public function getProductById(int $shopId, int $prodId): array
    {
        if ($shopId <= 0 || $prodId <= 0) {
            return [];
        }

        $prodInfo = $this->dbHelper->table('product')->where(
            ['shop_id' => $shopId, 'product_id' => $prodId])->find();
        if (empty($prodInfo)) {
            return [];
        }

        $descList = $this->dbHelper->table('product_description')->where(
            ['shop_id' => $shopId, 'product_id' => $prodId])->select();
        if (empty($descList)) {
            $prodInfo['desc_list'] = [];
        } else {
            $prodInfo['desc_list'] = array_column($descList, null, 'language_code');
        }

        $skuList = $this->dbHelper->table('product_sku')->where(
            ['shop_id' => $shopId, 'product_id' => $prodId])
            ->orderBy(['sort' => 'asc', 'product_sku_id' => 'asc'])->select();
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

        $skuList = $this->dbHelper->table('product_sku')->where($where)
            ->orderBy(['sort' => 'asc', 'product_sku_id' => 'asc'])->select();

        return empty($skuList) ? [] : array_column($skuList, null, 'sku');
    }

    public function getSkuQtyPriceListBySkuArr(int $shopId, array $skuArr)
    {
        if ($shopId <= 0 || empty($skuArr)) {
            return [];
        }

        $skuArr = array_values(array_unique($skuArr));
        $where = ['shop_id' => $shopId, 'sku' => ['in', $skuArr]];

        $skuQtyPriceList = $this->dbHelper->table('product_qty_price')->where($where)->select();

        return empty($skuQtyPriceList) ? [] : array_column($skuQtyPriceList, null, 'sku');

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

        $this->dbHelper->beginTransaction();
        try {
            if (empty($prodInfo)) {
                unset($prodInfo['product_id']);
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
                    if (empty($data['qty_price_data'])) {
                        return 0;
                    }

                    foreach ($data['qty_price_data'] as $qtyPrice) {
                        $qtyPrice['product_id'] = $prodId;
                        $this->dbHelper->table('product_qty_price')->insert($qtyPrice);
                    }
                }

            } else {
                unset($prodData['created_at'], $prodData['created_by']);
                $this->dbHelper->table('product')->where(
                    ['shop_id' => $shopId, 'product_id' => $prodId])->update($prodData);

                foreach ($prodDescData as $langCode => $prodDesc) {
                    if (empty($prodInfo['desc_list'][$langCode])) {
                        $prodDescId = $prodInfo['desc_list'][$langCode]['product_description_id'];
                        unset($prodDesc['created_at'], $prodDesc['created_by']);

                        $this->dbHelper->table('product_description')->where(
                            ['shop_id' => $shopId, 'product_description_id' => $prodDescId])->update($prodDesc);
                    } else {
                        $this->dbHelper->table('product_description')->insert($prodDesc);
                    }
                }

                foreach ($skuArr as $sort => $sku) {
                    if (empty($prodInfo['sku_list'][$sku])) {
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
                    } else {
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
                    }
                }

                $qtyPriceList = $this->getSkuQtyPriceListBySkuArr($shopId, $skuArr);

                foreach ($prodSkuData as $sku => $data) {
                    if (empty($data['qty_price_data'])) {
                        return 0;
                    }

                    foreach ($data['qty_price_data'] as $qtyPrice) {
                        $qtyPrice['product_id'] = $prodId;

                        if (empty($qtyPriceList[$sku])) {
                            $this->dbHelper->table('product_qty_price')->insert($qtyPrice);
                            continue;
                        }

                        $warehouse = $qtyPrice['warehouse_code'];
                        $qtyPriceList[$sku] = array_column($qtyPriceList[$sku], null, 'warehouse_code');
                        if (empty($qtyPriceList[$sku][$warehouse])) {
                            $this->dbHelper->table('product_qty_price')->insert($qtyPrice);
                        } else {
                            $qtyPriceId = $qtyPriceList[$sku][$warehouse]['product_qty_price_id'];
                            unset($qtyPrice['created_at'], $qtyPrice['created_by']);

                            $this->dbHelper->table('product_qty_price')->where(
                                ['shop_id' => $shopId, 'product_qty_price_id' => $qtyPriceId])->update($qtyPrice);
                        }
                    }
                }

                // 需要删除的SKU
                $delSkuArr = empty($prodInfo['sku_list']) ? [] : array_diff(array_keys($prodInfo['sku_list']), $skuArr);
                if (!empty($delSkuArr)) {
                    $this->dbHelper->table('product_sku')->where(
                        ['shop_id' => $shopId, 'sku' => ['in', $delSkuArr]])->delete();
                }

            }

            $res = $prodId;
            $this->dbHelper->commit();
        } catch (\PDOException $e) {
            print_r(__CLASS__ . ' :: ' . $e->getMessage());
            $res = 0;
            $this->dbHelper->rollBack();
        }

        return $res;
    }

    public function getCategoryTree(int $shopId, int $parentId = 0, string $language = 'en', int $filterCateId = 0): array
    {
        if ($shopId <= 0 || empty($language)) {
            return [];
        }

        if (empty($this->cateList[$language])) {
            $this->cateList[$language] = $this->dbHelper->table('product_category', 'pc')
                ->join('product_category_description', 'pc_desc',
                    ['pc.shop_id' => 'pc_desc.shop_id', 'pc.product_category_id' => 'pc_desc.product_category_id'])
                ->where(['pc.shop_id' => (int)$shopId, 'pc_desc.language_code' => $language])
                ->orderBy(['pc.sort' => 'asc', 'pc.product_category_id' => 'asc'])->select();
        }

        if (empty($this->cateList[$language])) {
            return [];
        }

        $cateTree = [];
        foreach ($this->cateList[$language] as $idx => $cateInfo) {
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

    public function getCategoryById(int $shopId, int $cateId): array
    {
        if ($shopId <= 0 || $cateId <= 0) {
            return [];
        }

        $cateInfo = $this->dbHelper->table('product_category')->where(
            ['shop_id' => $shopId, 'product_category_id' => $cateId])->find();
        if (empty($cateInfo)) {
            return [];
        }

        $descList = $this->dbHelper->table('product_category_description')->where(
            ['shop_id' => $shopId, 'product_category_id' => $cateId])->select();
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
