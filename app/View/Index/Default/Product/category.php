<?php
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
$cate_name = xss_text($cate_info['description']['category_name'] ?? '');
?>
    <div class="hd-height-15">&nbsp;</div>
    <div id="hd-crumb" class="container">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active"><?php echo $cate_name;?></li>
        </ol>
    </div>
    <div class="container">
        <h2>
            <?php echo $cate_name;?>
            <?php if (!empty($sort_list)) { ?>
                <div class="btn-group pull-right">
                    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        Sort By: <?php echo $sort_list[$sort]['text'] ?? 'Relevance'; ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <?php
                        foreach ($sort_list as $sk => $sv) {
                            if ($sk == $sort) {
                                continue;
                            }
                            echo '<li><a href="?sort=', $sk, '"><i class="', $sv['icon'], ' text-danger"></i> ', $sv['text'], '</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            <?php } ?>
        </h2>
        <?php if (!empty($prod_list)) { ?>
            <div class="row hd-margin-top-bottom-60">
                <?php
                foreach ($prod_list as $prod_info) {
                    $prod_link = $prod_info['product_url'] . '-p' . $prod_info['product_id'] . '.html';
                    $prod_img = $oss_access_host . $prod_info['image_path'] . '/' . $prod_info['image_name'] . '?' . $prod_info['updated_at'];
                    $prod_img = str_replace('_d_d', '_300_300', $prod_img);
                    ?>
                    <div class="col-md-3 hd-prod-box">
                        <div class="thumbnail">
                            <a href="/<?php echo $prod_link; ?>">
                                <img alt="<?php echo xss_text($prod_info['product_name']); ?>"
                                     src="<?php echo $prod_img; ?>"/>
                            </a>
                            <div class="caption">
                                <a href="/<?php echo $prod_link; ?>" class="title"
                                   title="<?php echo xss_text($prod_info['product_name']); ?>">
                                    <?php echo xss_text($prod_info['product_name']); ?>
                                </a>
                                <div class="price"><?php echo format_price($prod_info['price'], $currency, 1, true); ?></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php
            if ($page_total > 1) {
                echo '<div class="row"><ul class="pager">';
                if ($page > 1) {
                    echo '<li class="previous"><a href="', $page_link, 'page=', $page - 1, '"><span>&larr;</span> Previous</a></li>';
                }
                if ($page_total > $page) {
                    echo '<li class="next"><a href="', $page_link, 'page=', $page + 1, '">Next <span>&rarr;</span></a></li>';
                }
                echo '</ul></div>';
            }
        }
        ?>
    </div>
<?php
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
