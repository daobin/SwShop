<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['show_top_line' => false, 'timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid hd-padding-top30">
        <blockquote class="layui-elem-quote">欢迎管理员：<?php echo xss_text($admin_name); ?></blockquote>
    </div>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
