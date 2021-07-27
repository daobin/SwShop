<script>
    window.onload = function () {
        $('#hd-top-line a.hd-opt-add').click(function () {
            let layer_idx = layer.open({
                type: 2,
                title: '新增',
                skin: 'hd-open-edit',
                content: $.trim($(this).attr('href'))
            });
            layer.full(layer_idx);
            return false;
        });

        $('#hd-top-line a.hd-opt-refresh').click(function () {
            window.location.reload(true);
        });
    };
</script>
</body>
</html>