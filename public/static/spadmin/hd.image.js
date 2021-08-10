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
        imgObj.imgSelected = [];
        imgObj.imgSelectCallback = null;
        imgObj.layerIdx = null;

        imgObj.imgTpl = '<img style="width: 60px; height: auto; display: inline-block;" src="-IMG-SRC-" />';

        imgObj.initContent = '<div class="layui-fluid"><div class="layui-row">' +
            '<div class="layui-col-xs9" id="-LIST-IMG-ID-">-IMG-TPL-</div>' +
            '<div class="layui-col-xs3" style="text-align: right;">' +
            '<a class="layui-btn layui-btn-warm hd-btn-open-image"><i class="layui-icon layui-icon-share"></i> 选择商品图片</a></div>' +
            '</div></div>';

        imgObj.openContent = '<ul class="layui-nav layui-nav-tree layui-nav-side layui-bg-cyan" id="hd-nav-folder" style="top: 50px; border-radius: 0;"></ul>' +
            '<div class="layui-row" id="hd-btn-top-row">' +
            '<button class="layui-btn layui-btn-sm" id="hd-btn-add-folder"><i class="layui-icon layui-icon-file"></i> 新建图片目录</button>' +
            '<button class="layui-btn layui-btn-sm layui-btn-warm" id="hd-btn-upload-image"><i class="layui-icon layui-icon-upload"></i> 上传本地图片</button>' +
            '<button class="layui-btn layui-btn-sm layui-btn-normal" id="hd-btn-select-image">' +
            '<i class="layui-icon layui-icon-ok"></i> 确认选择图片 <input type="checkbox" style="display: inline-block; width: 20px; height: 20px; position: relative; top: 5px; cursor: pointer" /> </button>' +
            // '<button class="layui-btn layui-btn-sm layui-btn-danger" id="hd-btn-del-image"><i class="layui-icon layui-icon-delete"></i> 删除选择图片</button>' +
            '<button class="layui-btn layui-btn-sm" id="hd-btn-close-image"><i class="layui-icon layui-icon-close"></i> 关闭图片管理</button></div>' +
            '<div id="hd-box-list-image"><div class="layui-fluid" style="padding: 10px;"><div class="layui-row" id="hd-load-image"></div></div></div>';

        imgObj.btnClickOption = function () {
            $(document).on('click', '#hd-btn-add-folder', function () {
                let promptCfg = $.extend({}, imgObj.openAlertCfg);
                promptCfg.title = '请输入图片目录 <span class="layui-word-aux">只允许小写字母、数字、-</span>';
                promptCfg.formType = 0;
                layer.prompt(promptCfg, function (folder, idx) {
                    imgObj.folderImages = [];
                    imgObj.showFolderImage(folder);
                    imgObj.upload();
                    $('#hd-nav-folder .layui-nav-item').removeClass('layui-this');
                    $('#hd-nav-folder .layui-nav-item:last-child').addClass('layui-this');
                    layer.close(idx);
                });

            }).on('click', '#hd-btn-del-image', function () {
                if ($('.hd-box-image.active img').length == 0) {
                    layer.alert('请选择图片', imgObj.openAlertCfg);
                    return;
                }
                // 目前删除图片功能为非必要，按钮功能暂不实现
                // TODO
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

                for (let idx in imgObj.imgSelected) {
                    if($('#list_img_' + imgObj.imgBoxIdx + ' img').length >= 10){
                        layer.alert('每项最多可添加10张图片', imgObj.openAlertCfg);
                        break;
                    }

                    if ($('#list_img_' + imgObj.imgBoxIdx + ' img[src="' + imgObj.imgSelected[idx] + '"]').length == 0) {
                        let inputName = $('input.hd-input-sku').eq(imgObj.imgBoxIdx).attr('name')
                            .replace('[sku]', '[image][' + $('#list_img_' + imgObj.imgBoxIdx).find('img').length + ']');

                        $('#list_img_' + imgObj.imgBoxIdx).append(
                            imgHtml.replace('-IMG-SRC-', imgObj.imgSelected[idx])
                                .replace('-INPUT-HIDDEN-', '<input type="hidden" name="' + inputName + '" value="' + imgObj.imgSelected[idx] + '" />')
                        );
                    }
                }

                if(imgObj.imgSelectCallback != null){
                    imgObj.imgSelectCallback();
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
            }).on('click', '#hd-nav-folder .layui-nav-item a', function () {
                let folder = $(this).attr('folder');
                if (folder == undefined || folder == '' || imgObj.folder == folder) {
                    return;
                }

                imgObj.folderImages = [];
                imgObj.showFolderImage(folder);
                imgObj.upload();
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

                let imgFolder = sessionStorage.getItem('image_folder');
                imgFolder = imgFolder ? JSON.parse(imgFolder) : {};
                for (let idx in imgFolder) {
                    imgFolder[idx].isNav = false;
                }
                sessionStorage.setItem('image_folder', JSON.stringify(imgFolder));

                imgObj.folderImages = [];
                imgObj.showFolderImage();

                imgObj.uploader = null;
                imgObj.upload();
            });
        };

        imgObj.uploader = null;
        imgObj.uploaderSize = 2040;
        imgObj.upload = function () {
            if(imgObj.uploader){
                imgObj.uploader.reload({
                    data: {
                        folder: imgObj.folder,
                        hash_tk: $.trim($('input[name=hash_tk]').val())
                    }
                });
                return;
            }

            imgObj.uploader = upload.render({
                elem: '#hd-btn-upload-image',
                multiple: true,
                number: 10,
                size: imgObj.uploaderSize,
                drag: false,
                acceptMime: 'image/jpg, image/jpeg, image/png',
                url: imgObj.url,
                data: {
                    folder: imgObj.folder,
                    hash_tk: $.trim($('input[name=hash_tk]').val())
                },
                done: function (res) {
                    if (res.msg != undefined && res.msg != '') {
                        layer.alert(res.msg, imgObj.openAlertCfg);
                    }

                    if (res.status == 'success') {
                        $('.hd-box-image>div').each(function(){
                            if($.trim($(this).text()) == $.trim(res.name)){
                                $(this).parent('div').parent('div').remove();
                            }
                        });

                        imgObj.folderImages.push(imgObj.boxImgTpl.replace('-IMG-SRC-', res.src).replace('-IMG-NAME-', res.name));
                        if($('#hd-load-image .layui-flow-more').length == 0){
                            $('#hd-load-image').append(imgObj.boxImgTpl.replace('-IMG-SRC-', res.src).replace('-IMG-NAME-', res.name));
                        }else{
                            $('#hd-load-image .layui-flow-more').before(imgObj.boxImgTpl.replace('-IMG-SRC-', res.src).replace('-IMG-NAME-', res.name));
                        }
                    }
                },
                error: function () {
                    layer.alert('未知错误，请稍候刷新页面重试！', imgObj.openAlertCfg);
                }
            });
        };

        imgObj.folder = 'def';
        imgObj.folderImages = [];
        imgObj.showFolderImage = function (folder) {
            folder = folder == undefined ? '' : folder;
            folder = folder.toLocaleLowerCase();
            folder = folder.replace(/[^a-z\d_]+/, '');
            folder = folder == '' ? 'def' : folder;
            imgObj.folder = folder;

            let imgFolder = sessionStorage.getItem('image_folder');
            imgFolder = imgFolder ? JSON.parse(imgFolder) : {};
            if (!imgFolder.hasOwnProperty(folder)) {
                imgFolder[folder] = {
                    isNav: false
                };
            }

            for (let idx in imgFolder) {
                if (imgFolder[idx].isNav) {
                    continue;
                }

                imgFolder[idx].isNav = true;
                if (idx == 'def') {
                    $('#hd-nav-folder').append('<li class="layui-nav-item"><a folder="def">默认目录</a></li>');
                } else {
                    $('#hd-nav-folder').append('<li class="layui-nav-item"><a folder="' + idx + '">' + idx + '</a></li>');
                }
            }

            if ($('#hd-nav-folder .layui-nav-item.layui-this').length == 0) {
                $('#hd-nav-folder .layui-nav-item:eq(0)').addClass('layui-this');
            }

            layui.element.init('nav');
            sessionStorage.setItem('image_folder', JSON.stringify(imgFolder));

            if(imgObj.folderImages.length == 0){
                $('#hd-load-image').html('');
                imgObj.getImage();
            }
        };

        imgObj.boxImgTpl = '<div class="layui-col-xs2">' +
            '<div class="hd-box-image"><img src="-IMG-SRC-" /><div class="layui-elip">-IMG-NAME-</div>' +
            '<i class="layui-icon layui-icon-ok-circle"></i>' +
            '</div></div>';
        imgObj.getImage = function () {
            layui.flow.load({
                elem: '#hd-load-image',
                isAuto: false,
                done: function (page, next) {
                    $.get(imgObj.url + '?folder=' + imgObj.folder + '&page=' + page, function (res) {
                        layui.each(res.data, function (idx, item) {
                            imgObj.folderImages.push(imgObj.boxImgTpl.replace('-IMG-SRC-', item.src).replace('-IMG-NAME-', item.name));
                        });

                        res.pages = res.pages == undefined ? 1 : res.pages;
                        next(imgObj.folderImages.join(''), page < res.pages);
                    });
                }
            });
        };

        imgObj.initFolders = [];
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
                $('head').append('<link rel="stylesheet" id="hd-image-css" href="/static/spadmin/hd.image.css?20210806" />');
            }

            // 初始化目录导航
            if(!$.isEmptyObject(imgObj.initFolders)){
                let imgFolder = {};
                for (let idx in imgObj.initFolders) {
                    imgFolder[imgObj.initFolders[idx]] = {isNav: false};
                }
                sessionStorage.setItem('image_folder', JSON.stringify(imgFolder));
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