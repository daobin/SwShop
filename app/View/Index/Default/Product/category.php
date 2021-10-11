<?php
$widget_params['keywords'] = $keywords ?? '';
$cate_name = xss_text($cate_info['description']['category_name'] ?? '');

\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
?>
    <div class="hd-height-15">&nbsp;</div>
    <div id="hd-crumb" class="container">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <?php
            if (!empty($cate_level)) {
                foreach ($cate_level as $level_info) {
                    $level_name = xss_text($level_info['category_name']);
                    if ($cate_name == $level_name) {
                        break;
                    }

                    $cate_link = $level_info['category_url'] . '-c' . $level_info['product_category_id'] . '.html';
                    echo '<li><a href="/', $cate_link, '">', $level_name, '</a></li>';
                }
                echo '<li class="active">', $cate_name, '</li>';
            } else if (isset($keywords)) {
                $keywords = xss_text($keywords);
                echo '<li class="active">Search</li>';
            }
            ?>
        </ol>
    </div>
    <div class="container">
        <h2>
            <?php
            if (isset($keywords)) {
                echo 'Search for "', mb_substr($keywords, 0, 16);
                if (mb_strlen($keywords) > 16) {
                    echo '...';
                }
                echo '"';
            } else {
                echo $cate_name;
            }
            ?>
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
                            if (isset($keywords)) {
                                echo '<li><a href="?keywords=', $keywords, '&sort=', $sk, '"><i class="', $sv['icon'], ' text-danger"></i> ', $sv['text'], '</a></li>';
                            } else {
                                echo '<li><a href="?sort=', $sk, '"><i class="', $sv['icon'], ' text-danger"></i> ', $sv['text'], '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
            <?php } ?>
        </h2>
        <?php
        if (empty($prod_list)) {
            if (isset($keywords)) {
                ?>
                <div class="row hd-margin-top-bottom-60">
                    <div class="col-md-12">
                        <div class="hd-font-size-24">Sorry, no results were found for the keyword</div>
                        <hr/>
                        <div>
                            <p class="hd-font-size-18">Suggestions:</p>
                            <ul>
                                <li class="hd-margin-top-10">Make sure all words are spelled correctly.</li>
                                <li class="hd-margin-top-10">Try again with other keywords.</li>
                                <li class="hd-margin-top-10 text-danger">
                                    <a href="/customer-service.html">Tell us what products you need.</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                // Nothing
            }
        } else {
            ?>
            <div class="row hd-margin-top-bottom-60">
                <?php
                foreach ($prod_list as $prod_info) {
                    $prod_name = xss_text($prod_info['product_name']);
                    $prod_link = $prod_info['product_url'] . '-p' . $prod_info['product_id'] . '.html';
                    $prod_img = $oss_access_host . $prod_info['image_path'] . '/' . $prod_info['image_name'] . '?' . $prod_info['updated_at'];
                    $prod_img = str_replace('_d_d', '_300_300', $prod_img);
                    ?>
                    <div class="col-md-3 hd-prod-box">
                        <div class="thumbnail">
                            <a href="/<?php echo $prod_link; ?>">
                                <img alt="<?php echo $prod_name; ?>"
                                     src="<?php echo $prod_img; ?>"/>
                            </a>
                            <div class="caption">
                                <a href="/<?php echo $prod_link; ?>" class="title" title="<?php echo $prod_name; ?>">
                                    <?php echo $prod_name; ?>
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
