// 基于 LayUI 框架的图片管理工具
layui.use(['jquery', 'layer', 'element', 'flow', 'upload'], function () {
    let $ = layui.jquery;
    let layer = layui.layer;
    let upload = layui.upload;

    window.hdImg = window.hdImg || {};
    (function (imgObj) {
        imgObj.elem = '';
        imgObj.url = '';
        imgObj.openAlertCfg = {
            title: '提示',
            icon: 0,
            offset: '120px',
            closeBtn: 0
        };
        imgObj.imgBoxIdx = 0;
        imgObj.selectImages = [];
        imgObj.layerIdx = null;
        imgObj.imgTpl = '<img style="width: 60px; height: auto; display: inline-block;" src="-IMG-SRC-" />';
        imgObj.initContent = '<div class="layui-fluid"><div class="layui-row">' +
            '<div class="layui-col-xs9" id="-LIST-IMG-ID-">-IMG-TPL-&nbsp;</div>' +
            '<div class="layui-col-xs3" style="text-align: right;">' +
            '<a class="layui-btn layui-btn-warm hd-btn-open-image" id="-BTN-OPEN-ID-"><i class="layui-icon layui-icon-upload-drag"></i>上传商品图片</a></div>' +
            '</div></div>';
        imgObj.openContent = '<ul class="layui-nav layui-nav-tree layui-nav-side" style="top: 50px; border-radius: 0;">' +
            '<li class="layui-nav-item"><a>默认目录</a></li>' +
            '</ul>' +
            '<div class="layui-row" id="hd-btn-top-row">' +
            '<button class="layui-btn layui-btn-sm" id="hd-btn-add-folder"><i class="layui-icon layui-icon-file"></i> 新建图片目录</button>' +
            '<button class="layui-btn layui-btn-sm layui-btn-warm" id="hd-btn-upload-image"><i class="layui-icon layui-icon-upload"></i> 上传本地图片</button>' +
            '<button class="layui-btn layui-btn-sm layui-btn-normal" id="hd-btn-select-image"><i class="layui-icon layui-icon-ok"></i> 确认选择图片</button>' +
            '<button class="layui-btn layui-btn-sm" id="hd-btn-close-image"><i class="layui-icon layui-icon-close"></i> 关闭图片管理</button></div>' +
            '<div id="hd-box-list-image"><div class="layui-fluid" style="padding: 10px;"><div class="layui-row" id="hd-load-image"></div></div></div>';
        imgObj.init = function (params) {
            imgObj.selectImages = [];
            if ($.isPlainObject(params)) {
                $.extend(imgObj, params);
            }

            if (imgObj.elem == '') {
                layer.alert('请提供需要图片管理初始化的元素', imgObj.openAlertCfg);
                return;
            }
            if (imgObj.url == '') {
                layer.alert('请提供需要图片管理的上传链接', imgObj.openAlertCfg);
                return;
            }

            if ($('#hd-image-css').get().length == 0) {
                $('head').append('<link rel="stylesheet" id="hd-image-css" href="/static/spadmin/hd.image.css?20210802" />');
            }

            $(document).on('click', '#hd-btn-add-folder', function () {
                console.log('add folder');
            }).on('click', '#hd-btn-upload-image', function () {
                console.log('add upload image');
            }).on('click', '#hd-btn-select-image', function () {
                $('.hd-box-image.active').each(function(){

                });
                if(imgObj.selectImages.length == 0){
                    layer.alert('请选择图片', imgObj.openAlertCfg);
                    return;
                }
            }).on('click', '#hd-btn-close-image', function () {
                if (imgObj.layerIdx != null) {
                    layer.close(imgObj.layerIdx);
                }
            }).on('click', '.hd-box-image', function () {
                if($(this).hasClass('active')){
                    $(this).removeClass('active');
                }else{
                    $(this).addClass('active');
                }
            });

            $(imgObj.elem).each(function (idx) {
                // 避免多次重复初始化操作元素
                if ($.trim($(this).html()) != '') {
                    return;
                }

                let listImgId = 'list_img_' + idx;
                let btnOpenId = 'btn_open_' + idx;
                let html = imgObj.initContent.replace('-LIST-IMG-ID-', listImgId).replace('-BTN-OPEN-ID-', btnOpenId).replace('-IMG-TPL-', '');
                $(this).append(html);

                // upload.render({
                //     elem: '#' + btn_id,
                //     url: imgObj.url,
                //     done: function (res) {
                //
                //     },
                //     error: function () {
                //
                //     }
                // });

                // 打开上传图片管理工具弹窗
                $('#' + btnOpenId).on('click', function () {
                    imgObj.imgBoxIdx = $('.hd-btn-open-image').index($(this));

                    let openCfg = $.extend({}, imgObj.openAlertCfg);
                    openCfg.title = ['上传图片管理', 'font-size: 18px; background: #333; color: #fff;'];
                    openCfg.type = 1;
                    openCfg.content = imgObj.openContent;
                    imgObj.layerIdx = layer.open(openCfg);
                    layer.full(imgObj.layerIdx);

                    layui.flow.load({
                        elem: '#hd-load-image',
                        // scrollElem: '#hd-load-image',
                        isAuto: false,
                        done: function (page, next) {
                            console.log('page: ' + page);
                            var lis = [];
                            $.get('/spadmin/upload?page=' + page, function (res) {
                                layui.each(res.data, function (idx, item) {
                                    lis.push('<div class="layui-col-xs3"><div class="hd-box-image"><img src="' + item.src + '" /><div>' + item.name + '</div></div></div>')
                                });
                                next(lis.join(''), page < res.pages);
                            });
                        }
                    });
                });

                // 避免点击上传图标无效、禁止冒泡事件
                $('#' + btnOpenId).on('click', function (e) {
                    $(this).parent('.btn_upload').click();
                    e.stopPropagation();
                    return false;
                });
            });
        };
    })(window.hdImg);
});