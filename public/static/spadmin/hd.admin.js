// 全局变量
var open_ask_cfg = {
    title: '询问',
    icon: 3,
    offset: '120px',
    skin: 'hd-open-ask'
};


// 后台全局禁用鼠标右键
window.document.oncontextmenu = function () {
    return false;
};

// 后台全局使用 Layui 框架中的 jQuery、Layer 扩展插件
layui.use(['jquery', 'layer'], function () {
    window.jQuery = window.$ = layui.jquery;
    window.layer = layui.layer;
});

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
                layer.alert(res.msg);
            }

            if (res.url != undefined && res.url != '') {
                window.location.href = res.url;
            }

            if (res.status == 'success') {
                // 由于保存操作是在子窗口进行，所以在父窗口刷新页面
                parent.location.reload(true);
            }
        },
        error: function () {
            layer.alert('未知错误，请稍候刷新页面重试！');
        }
    });
}
