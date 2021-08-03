// 基于 LayUI 框架的图片管理工具
layui.use(['jquery', 'layer', 'flow', 'upload'], function () {
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
        imgObj.imgSelected = [];
        imgObj.layerIdx = null;

        imgObj.imgTpl = '<img style="width: 60px; height: auto; display: inline-block;" src="-IMG-SRC-" />';

        imgObj.initContent = '<div class="layui-fluid"><div class="layui-row">' +
            '<div class="layui-col-xs9" id="-LIST-IMG-ID-">-IMG-TPL-</div>' +
            '<div class="layui-col-xs3" style="text-align: right;">' +
            '<a class="layui-btn layui-btn-warm hd-btn-open-image"><i class="layui-icon layui-icon-upload-drag"></i>上传商品图片</a></div>' +
            '</div></div>';

        imgObj.openContent = '<ul class="layui-nav layui-nav-tree layui-nav-side" style="top: 50px; border-radius: 0;">' +
            '<li class="layui-nav-item"><a>默认目录</a></li>' +
            '</ul>' +
            '<div class="layui-row" id="hd-btn-top-row">' +
            '<button class="layui-btn layui-btn-sm" id="hd-btn-add-folder"><i class="layui-icon layui-icon-file"></i> 新建图片目录</button>' +
            '<button class="layui-btn layui-btn-sm layui-btn-warm" id="hd-btn-upload-image"><i class="layui-icon layui-icon-upload"></i> 上传本地图片</button>' +
            '<button class="layui-btn layui-btn-sm layui-btn-normal" id="hd-btn-select-image">' +
            '<i class="layui-icon layui-icon-ok"></i> 确认选择图片 <input type="checkbox" style="display: inline-block; width: 20px; height: 20px; position: relative; top: 5px; cursor: pointer" /> </button>' +
            '<button class="layui-btn layui-btn-sm" id="hd-btn-close-image"><i class="layui-icon layui-icon-close"></i> 关闭图片管理</button></div>' +
            '<div id="hd-box-list-image"><div class="layui-fluid" style="padding: 10px;"><div class="layui-row" id="hd-load-image"></div></div></div>';

        imgObj.btnClickOption = function () {
            $(document).on('click', '#hd-btn-add-folder', function () {
                let promptCfg = $.extend({}, imgObj.openAlertCfg);
                promptCfg.title = '请输入图片目录 <span class="layui-word-aux">只允许小写字母、数字、-</span>';
                promptCfg.formType = 0;
                layer.prompt(promptCfg, function (folder, idx) {
                    imgObj.showFolderImage(folder);
                    layer.close(idx);
                });

            }).on('click', '#hd-btn-upload-image', function () {
                console.log('add upload image');

            }).on('click', '#hd-btn-select-image input', function (e) {
                e.stopPropagation();

                if ($(this).prop('checked')) {
                    $('.hd-box-image').addClass('active');
                } else {
                    $('.hd-box-image').removeClass('active');
                }
                imgObj.imgSelected = [];

            }).on('click', '#hd-btn-select-image', function () {
                if ($('.hd-box-image.active img').length == 0) {
                    layer.alert('请选择图片', imgObj.openAlertCfg);
                    return;
                }

                let imgHtml = '<div style="position: relative; display: inline-block; margin: 10px; border: 1px solid #ccc;">' +
                    '-INPUT-HIDDEN-<img src="-IMG-SRC-" /><i class="layui-icon layui-icon-close-fill hd-btn-del-image" ' +
                    ' style="position: absolute; top: 0; right: 0; font-size: 20px; color: #FF5722; cursor: pointer;"></i></div>';
                if (imgObj.imgSelected.length == 0) {
                    $('.hd-box-image.active img').each(function () {
                        let imgSrc = $.trim($(this).attr('src'));
                        if ($('#list_img_' + imgObj.imgBoxIdx + ' img[src="' + imgSrc + '"]').length == 0) {
                            let inputName = $('input.hd-input-sku').eq(imgObj.imgBoxIdx).attr('name')
                                .replace('[sku]', '[image][' + $('#list_img_' + imgObj.imgBoxIdx).find('img').length + ']');

                            $('#list_img_' + imgObj.imgBoxIdx).append(
                                imgHtml.replace('-IMG-SRC-', imgSrc)
                                    .replace('-INPUT-HIDDEN-', '<input type="hidden" name="' + inputName + '" value="' + imgSrc + '" />')
                            );
                        }
                    });
                } else {
                    for (let idx in imgObj.imgSelected) {
                        if ($('#list_img_' + imgObj.imgBoxIdx + ' img[src="' + imgObj.imgSelected[idx] + '"]').length == 0) {
                            let inputName = $('input.hd-input-sku').eq(imgObj.imgBoxIdx).attr('name')
                                .replace('[sku]', '[image][' + $('#list_img_' + imgObj.imgBoxIdx).find('img').length + ']');

                            $('#list_img_' + imgObj.imgBoxIdx).append(
                                imgHtml.replace('-IMG-SRC-', imgObj.imgSelected[idx])
                                    .replace('-INPUT-HIDDEN-', '<input type="hidden" name="' + inputName + '" value="' + imgObj.imgSelected[idx] + '" />')
                            );
                        }
                    }
                }

                if (imgObj.layerIdx != null) {
                    layer.close(imgObj.layerIdx);
                }

            }).on('click', '#hd-btn-close-image', function () {
                if (imgObj.layerIdx != null) {
                    layer.close(imgObj.layerIdx);
                }

            }).on('click', '.hd-box-image', function () {
                if ($(this).hasClass('active')) {
                    $(this).removeClass('active');
                    for (let idx in imgObj.imgSelected) {
                        if ($.trim($(this).find('img')[0].src) == imgObj.imgSelected[idx]) {
                            imgObj.imgSelected.splice(idx, 1);
                        }
                    }
                } else {
                    $(this).addClass('active');
                    imgObj.imgSelected.push($.trim($(this).find('img')[0].src));
                }

            }).on('click', '.hd-btn-del-image', function () {
                $(this).parent('div').remove();
            });

            // 打开上传图片管理工具弹窗
            $('.hd-btn-open-image').on('click', function () {
                imgObj.imgBoxIdx = $('.hd-btn-open-image').index($(this));

                let openCfg = $.extend({}, imgObj.openAlertCfg);
                openCfg.title = ['上传图片管理', 'font-size: 18px; background: #333; color: #fff;'];
                openCfg.type = 1;
                openCfg.content = imgObj.openContent;
                imgObj.layerIdx = layer.open(openCfg);
                layer.full(imgObj.layerIdx);

                imgObj.showFolderImage();
            });
        };

        imgObj.folder = 'def';
        imgObj.showFolderImage = function (folder) {
            folder = folder == undefined ? '' : folder;
            folder = folder.replace(/[^a-z\d\-]+/, '');
            folder = folder == '' ? 'def' : folder;

            let folderImgList = sessionStorage.getItem('folder_image');
            folderImgList = folderImgList ? JSON.parse(folderImgList) : {};

            folder = folder.replace(/[^a-z\d\-]+/, '');
            imgObj.folder = folder;

            if (folderImgList.hasOwnProperty(folder)) {
                return;
            }

            folderImgList[folder] = {};
            sessionStorage.setItem('folder_image', JSON.stringify(folderImgList));
            imgObj.getImage();
        };

        imgObj.getImage = function () {
            layui.flow.load({
                elem: '#hd-load-image',
                isAuto: false,
                done: function (page, next) {
                    let its = [];
                    $.get(imgObj.url + '?folder=' + imgObj.folder + '&page=' + page, function (res) {
                        layui.each(res.data, function (idx, item) {
                            its.push('<div class="layui-col-xs2"><div class="hd-box-image">' +
                                '<img src="' + item.src + '" />' +
                                '<div>' + item.name + '</div>' +
                                '<i class="layui-icon layui-icon-ok-circle"></i>' +
                                '</div></div>')
                        });

                        res.pages = res.pages == undefined ? 1 : res.pages;
                        next(its.join(''), page < res.pages);
                    });
                }
            });
        };

        imgObj.uploadImage = function () {
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
        };

        imgObj.init = function (params) {
            imgObj.imgBoxIdx = 0;
            imgObj.imgSelected = [];

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

            $(imgObj.elem).each(function (idx) {
                // 避免多次重复初始化操作元素
                if ($.trim($(this).html()) != '') {
                    imgObj.btnClickOption();
                    return;
                }

                let listImgId = 'list_img_' + idx;
                let html = imgObj.initContent.replace('-LIST-IMG-ID-', listImgId).replace('-IMG-TPL-', '&nbsp;');
                $(this).append(html);
                imgObj.btnClickOption();
            });
        };
    })(window.hdImg);
});