<ul class="layui-nav layui-nav-tree layui-nav-side" lay-shrink="all" style="top: 60px; border-radius: 0;">
    <?php
    if (!empty($navs)) {
        foreach ($navs as $top_nav) {
            $nav_html = '<li class="layui-nav-item" lay-unselect>';
            $nav_html .= '<a %s>%s%s</a>';
            $nav_html = sprintf(
                $nav_html,
                isset($top_nav['iframe']) ? 'iframe="' . $top_nav['iframe'] . '"' : '',
                isset($top_nav['icon']) ? '<i class="layui-icon ' . $top_nav['icon'] . '"></i> ' : '',
                $top_nav['name']??''
            );
            if (!empty($top_nav['sub_navs'])) {
                $nav_html .= '<dl class="layui-nav-child">';
                foreach ($top_nav['sub_navs'] as $sub_nav) {
                    $nav_html .= '<dd lay-unselect><a %s>%s%s</a></dd>';
                    $nav_html = sprintf(
                        $nav_html,
                        isset($sub_nav['iframe']) ? 'iframe="' . $sub_nav['iframe'] . '"' : '',
                        isset($sub_nav['icon']) ? '<i class="layui-icon ' . $sub_nav['icon'] . '"></i> ' : '',
                        $sub_nav['name']??''
                    );
                }
                $nav_html .= '</dl>';
            }
            echo $nav_html . '</li>';
        }
    }
    ?>
</ul>