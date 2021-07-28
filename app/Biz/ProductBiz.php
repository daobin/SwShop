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

        return $prodInfo;
    }

    public function saveProduct(array $prodData, array $prodDescData, array $prodSkuData, array $prodImgData, array $prodPriceData): int
    {
        if (empty($prodData) || empty($prodDescData) || empty($prodSkuData) || empty($prodImgData) || empty($prodPriceData)) {
            return 0;
        }

        $shopId = $prodData['shop_id'] ?? 0;
        $prodId = $prodData['product_id'] ?? 0;
        $prodInfo = $this->getProductById($shopId, $prodId);

        $this->dbHelper->beginTransaction();
        try {
            if (empty($prodInfo)) {
                unset($prodInfo['product_id']);
                $prodId = $this->dbHelper->table('product')->insert($prodData);

                foreach ($prodDescData as $prodDesc) {
                    $prodDesc['product_id'] = $prodId;
                    $this->dbHelper->table('product_description')->insert($prodDesc);
                }

            } else {
                unset($prodData['created_at'], $prodData['created_by']);
                $this->dbHelper->table('product')->where(
                    ['shop_id' => $shopId, 'product_id' => $prodId])->update($prodData);

                foreach ($prodDescData as $langCode => $prodDesc) {
                    if (isset($prodInfo['desc_list'][$langCode])) {
                        $prodDescId = $prodInfo['desc_list'][$langCode]['product_description_id'];
                        unset($prodDesc['created_at'], $prodDesc['created_by']);

                        $this->dbHelper->table('product_description')->where(
                            ['shop_id' => $shopId, 'product_description_id' => $prodDescId])->update($prodDesc);
                    } else {
                        $this->dbHelper->table('product_description')->insert($prodDesc);
                    }
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
