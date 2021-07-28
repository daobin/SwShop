// 基于 LayUI 框架的图片管理工具
layui.use(['jquery', 'layer', 'element', 'upload'], function () {
    let $ = layui.jquery;
    let layer = layui.layer;
    let upload = layui.upload;

    window.hdImage = window.hdImage || {};
    (function (imgObj) {
        imgObj.elem = '';
        imgObj.url = '';
        imgObj.init = function (params) {
            if ($.isPlainObject(params)) {
                $.extend(imgObj, params);
            }

            if (imgObj.elem == '') {
                layer.alert('请提供需要图片管理初始化的元素', open_alert_cfg || {});
                return;
            }
            if (imgObj.url == '') {
                layer.alert('请提供需要图片管理的上传链接', open_alert_cfg || {});
                return;
            }

            $(imgObj.elem).each(function (idx) {
                // 避免多次重复添加操作元素
                if ($.trim($(this).html()) != '') {
                    return;
                }

                let btn_id = 'btn_upload_' + idx;
                let html = '<div class="layui-fluid"><div class="layui-row">' +
                    '<div class="layui-col-xs9"><img src="http://t.cn/RCzsdCq" /></div>' +
                    '<div class="layui-col-xs3 hd-align-right">' +
                    '<a class="layui-btn layui-btn-warm" id="' + btn_id + '">' +
                    '<i class="layui-icon layui-icon-upload-drag"></i>上传商品图片</a></div> ' +
                    '</div></div>';
                $(this).append(html);

                upload.render({
                    elem: '#' + btn_id,
                    url: imgObj.url,
                    done: function(res){

                    },
                    error: function () {

                    }
                });

                // $(this).find('.btn_upload').on('click', function () {
                //
                // });
                //
                // // 避免点击上传图标无效、禁止冒泡事件
                // $(this).find('.btn_upload i').on('click', function (e) {
                //     $(this).parent('.btn_upload').click();
                //     e.stopPropagation();
                //     return false;
                // });
            });
        };
    })(window.hdImage);
});