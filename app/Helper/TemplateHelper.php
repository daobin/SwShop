<?php
/**
 * 模板视图助手
 * User: dao bin
 * Date: 2021/7/8
 * Time: 17:42
 */
declare(strict_types=1);

namespace App\Helper;

class TemplateHelper
{
    public static $viewDir = ROOT_DIR . 'app/View/';

    public static function view($template, $data = [])
    {
        $tplFile = self::$viewDir . $template . '.php';
        if (!is_file($tplFile)) {
            print_r($tplFile.PHP_EOL);
            throw new \Exception('Sorry, the accessed resource does not exist.');
        }

        ob_start();
        ob_implicit_flush(0);

        extract($data);
        include $tplFile;

        return ob_get_clean();
    }

    public static function widget($widget, $action, $params = [])
    {
        $widget = ucwords(strtolower($widget), '_');
        $widget = str_replace('_', '', $widget);
        $widget = 'App\\Widget\\' . $widget . 'Widget';

        echo (new $widget())->$action($params);
    }
}
