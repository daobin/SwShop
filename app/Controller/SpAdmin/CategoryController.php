<?php
/**
 * 商品类目管理
 * User: dao bin
 * Date: 2021/7/21
 * Time: 11:20
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\ProductBiz;
use App\Controller\Controller;
use App\Helper\ConfigHelper;
use App\Helper\DbHelper;
use App\Helper\LanguageHelper;

class CategoryController extends Controller
{
    public function index()
    {
        $cateTreeList = [];
        $prodBiz = new ProductBiz();

        $langCodes = ConfigHelper::get('app.languages', ['en']);
        foreach ($langCodes as $idx => $langCode) {
            $cateTreeList[$idx] = [
                [
                    'id' => 0 - $idx - 1,
                    'title' => LanguageHelper::get('product_category', $langCode),
                    'spread' => true,
                    'children' => $prodBiz->getCategoryTree($this->request->shop_id, 0, $langCode)
                ]
            ];
        }

        return $this->render(['lang_codes' => $langCodes, 'cate_tree_list' => $cateTreeList]);
    }

    public function detail()
    {
    }
}
