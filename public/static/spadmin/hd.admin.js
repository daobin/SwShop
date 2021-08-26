// 全局变量
var open_alert_cfg = {
    title: '提示',
    icon: 0,
    offset: '120px',
    closeBtn: 0
};
var open_ask_cfg = {
    title: '询问',
    icon: 3,
    offset: '120px',
    skin: 'hd-open-ask'
};


// 后台全局禁用鼠标右键
// window.document.oncontextmenu = function () {
//     return false;
// };

// 后台全局使用 Layui 框架中的 jQuery、Layer 扩展插件
layui.use(['jquery', 'layer'], function () {
    window.jQuery = window.$ = layui.jquery;
    window.layer = layui.layer;

    // 只允许正整数
    $(document).on('keyup', '.hd-int-only', function () {
        $(this).val($(this).val().replace(/[^\d]+/, ''));
    });

    // 只允许正数，含小数
    $(document).on('keyup', '.hd-float-only', function () {
        let val = $(this).val().replace(/[^\d\.]+/, '');
        val = val.replace(/\.{2,}/, '.');
        $(this).val(val);
    });

    // Text to pwd
    $(document).on('focus', '.hd-password', function () {
        $(this).prop('type', 'password');
    });

    // 关闭当前 iframe 弹出层
    $(document).on('click', '.hd-layer-close', function () {
        parent.layer.close(parent.layer.getFrameIndex(window.name));
    });
});

// 当前时间展示
function show_date_time(elem) {
    if (elem == undefined || $.trim(elem) == '') {
        return;
    }

    let date = new Date();
    setInterval(function () {
        date.setSeconds(date.getSeconds() + 1);

        var date_time = date.getFullYear() + '-';
        if (date.getMonth() + 1 < 10) {
            date_time += '0';
        }
        date_time += (date.getMonth() + 1) + '-';

        if (date.getDate() < 10) {
            date_time += '0';
        }
        date_time += date.getDate() + ' ';

        if (date.getHours() < 10) {
            date_time += '0';
        }
        date_time += date.getHours() + ':';

        if (date.getMinutes() < 10) {
            date_time += '0';
        }
        date_time += date.getMinutes() + ':';

        if (date.getSeconds() < 10) {
            date_time += '0';
        }
        date_time += date.getSeconds();

        $(elem).text('[ ' + date_time + ' ]');
    }, 999);
}

// 表单提交
function form_submit(url, data) {
    if (url == undefined || data == undefined) {
        return false;
    }

    $.ajax({
        type: 'post',
        url: url,
        data: data,
        success: function (res) {
            if (res.msg != undefined && res.msg != '') {
                layer.alert(res.msg, open_alert_cfg, function (idx) {
                    if (res.url != undefined && res.url != '') {
                        window.location.href = res.url;
                    } else if (res.status == 'success') {
                        // 由于保存操作是在子窗口进行，所以在父窗口刷新页面
                        parent.location.reload(true);
                    }else{
                        layer.close(idx);
                    }
                });
            } else {
                if (res.url != undefined && res.url != '') {
                    window.location.href = res.url;
                } else if (res.status == 'success') {
                    // 由于保存操作是在子窗口进行，所以在父窗口刷新页面
                    parent.location.reload(true);
                }
            }
        },
        error: function () {
            layer.alert('未知错误，请稍候刷新页面重试！', open_alert_cfg);
        }
    });
}
