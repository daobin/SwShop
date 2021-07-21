<script>
    window.document.oncontextmenu = function () {
        return false;
    };

    window.onload = function(){
        $('#hd-top-line a.layui-btn').click(function(){
            window.location.reload(true);
        });
    };
</script>
</body>
</html>