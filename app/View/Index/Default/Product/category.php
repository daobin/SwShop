<?php \App\Helper\TemplateHelper::widget('index', 'header'); ?>
<h1>分类：<?php echo xss_text($cate_name); ?></h1>
<?php \App\Helper\TemplateHelper::widget('index', 'footer'); ?>