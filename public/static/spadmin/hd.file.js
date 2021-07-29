// 基于 LayUI 框架的图片管理工具
layui.use(['jquery', 'layer', 'element', 'upload'], function () {
    let $ = layui.jquery;
    let layer = layui.layer;
    let upload = layui.upload;

    window.hdFile = window.hdFile || {};
    (function (fileObj) {
        fileObj.elem = '';
        fileObj.url = '';
        fileObj.openAlertCfg = {
            title: '提示',
            icon: 0,
            offset: '120px',
            closeBtn: 0
        };
        fileObj.imgTpl = '<img style="width: 60px; height: auto; display: inline-block;" src="-IMG-SRC-" />';
        fileObj.initContent = '<div class="layui-fluid"><div class="layui-row">' +
            '<div class="layui-col-xs9" id="-LIST-IMG-ID-">-IMG-TPL-&nbsp;</div>' +
            '<div class="layui-col-xs3" style="text-align: right;">' +
            '<a class="layui-btn layui-btn-warm" id="-BTN-OPEN-ID-"><i class="layui-icon layui-icon-upload-drag"></i>上传商品图片</a></div>' +
            '</div></div>';
        fileObj.openContent = '<div class="layui-fluid"><div class="layui-row">' +
            '' +
            '</div></div>';
        fileObj.init = function (params) {
            if ($.isPlainObject(params)) {
                $.extend(fileObj, params);
            }

            if (fileObj.elem == '') {
                layer.alert('请提供需要图片管理初始化的元素', fileObj.openAlertCfg);
                return;
            }
            if (fileObj.url == '') {
                layer.alert('请提供需要图片管理的上传链接', fileObj.openAlertCfg);
                return;
            }

            $(fileObj.elem).each(function (idx) {
                // 避免多次重复初始化操作元素
                if ($.trim($(this).html()) != '') {
                    return;
                }

                let listImgId = 'list_img_' + idx;
                let btnOpenId = 'btn_open_' + idx;
                let html = fileObj.initContent.replace('-LIST-IMG-ID-', listImgId).replace('-BTN-OPEN-ID-', btnOpenId).replace('-IMG-TPL-', '');
                $(this).append(html);

                // upload.render({
                //     elem: '#' + btn_id,
                //     url: fileObj.url,
                //     done: function (res) {
                //
                //     },
                //     error: function () {
                //
                //     }
                // });

                // 打开上传图片管理工具弹窗
                $('#' + btnOpenId).on('click', function () {
                    let openCfg = fileObj.openAlertCfg;
                    openCfg.title = ['上传图片管理', 'font-size: 18px;'];
                    openCfg.closeBtn = 1;
                    openCfg.type = 1;
                    openCfg.content = fileObj.openContent;

                    layer.full(layer.open(openCfg));
                });

                // 避免点击上传图标无效、禁止冒泡事件
                $('#' + btnOpenId).on('click', function (e) {
                    $(this).parent('.btn_upload').click();
                    e.stopPropagation();
                    return false;
                });
            });
        };
    })(window.hdFile);
});