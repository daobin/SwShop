// Tip Tool
$('a[data-toggle="tooltip"]').tooltip({
    placement: 'bottom',
    trigger: 'hover'
});

// Tip Dialog
var tipDialog = $('#hd-dialog-tip').modal({
    show: false,
    // backdrop: 'static'
});
window.alert = function(msg){
    if (msg != undefined && msg != '') {
        $('#hd-dialog-tip .modal-body').html('<p>' + msg + '</p>');
        tipDialog.modal('show');
    }
};

// Text to pwd
$(document).on('focus', '.hd-password', function () {
    $(this).prop('type', 'password');
});

// Register
$('#hd-form-register').submit(function () {
    $(this).ajaxSubmit({
        dataType: 'json',
        success: function (res) {
            alert(res.msg);
            if(res.url != undefined && res.url != ''){
                window.location.href = res.url;
            }
        },
        error: function () {
            alert('Unknown error, please refresh the page later and try again!');
        }
    });

    return false;
});

// Login
$('#hd-form-login').submit(function () {
    $(this).ajaxSubmit({
        dataType: 'json',
        success: function (res) {
            alert(res.msg);
            if(res.url != undefined && res.url != ''){
                window.location.href = res.url;
            }
        },
        error: function () {
            alert('Unknown error, please refresh the page later and try again!');
        }
    });

    return false;
});