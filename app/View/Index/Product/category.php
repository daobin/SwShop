<?php \App\Helper\TemplateHelper::widget('index', 'header'); ?>

<h1>分类：<?php echo htmlspecialchars($cate_name); ?></h1>
<?php
echo '<pre>';
print_r($shop_list);
echo '</pre>';
?>

<?php \App\Helper\TemplateHelper::widget('index', 'footer'); ?>