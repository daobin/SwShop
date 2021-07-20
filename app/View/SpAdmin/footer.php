<script>
    $(function () {
        $('#hd-main .layui-nav .layui-nav-item a').click(function () {
            if ($(this).attr('iframe') == undefined) {
                return;
            }

            let iframe = $.trim($(this).attr('iframe'));
            let layId = iframe.replace('/', '_');
            if (iframeNavMaps.indexOf(layId) > -1) {
                element.tabChange('iframe', layId);
            } else {
                iframeNavMaps.push(layId);

                let iframeHtml = '<iframe src="/spadmin/' + iframe + '.html" style="width: 100%; height: auto; border: none;"></iframe>';
                element.tabAdd('iframe', {
                    id: layId,
                    title: $(this).html(),
                    content: iframeHtml
                });
                element.tabChange('iframe', layId);
            }
        });

        // 初始化数据表盘
        $('#hd-main .layui-nav .layui-nav-item a[iframe=dashboard]').click();
    });
</script>
</body>
</html>