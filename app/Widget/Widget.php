<?php
declare(strict_types=1);

namespace App\Widget;

use App\Helper\LanguageHelper;
use App\Helper\TemplateHelper;

class Widget
{
    public function __call($name, $arguments)
    {
        print_r(sprintf('Class::Method [%s::%s] Not Found', get_class($this), $name));

        return LanguageHelper::get('invalid_request');
    }

    public function render($template, $data = [])
    {
        $widget = explode('\\', get_class($this));
        $widget = str_replace('Widget', '', end($widget));
        $template = $widget . '/' . trim($template);
        return TemplateHelper::view($template, $data);
    }
}
