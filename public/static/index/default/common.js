var prodScrollWidth = 0;
var prodScrollTop = 0;
if ($('#hd-prod-detail-scroll').length == 1) {
    prodScrollWidth = $('#hd-prod-detail-scroll').width();
    prodScrollTop = $('#hd-prod-detail-scroll').offset().top;
}
var winHalfHeight = $(window).height() / 2;

$(document).scroll(function () {
    let winScrollTop = $(this).scrollTop();

    // 商品详情定位
    if (prodScrollTop > 0) {
        if (winScrollTop >= prodScrollTop) {
            $('#hd-prod-detail-scroll').width(prodScrollWidth).addClass('hd-position-fixed');
            $('#hd-prod-desc').addClass('hd-margin-top-130');
        } else {
            $('#hd-prod-detail-scroll').removeClass('hd-position-fixed');
            $('#hd-prod-desc').removeClass('hd-margin-top-130');
        }
    }

    // 返回顶端
    if (winScrollTop >= winHalfHeight) {
        $('#hd-back-top').show();
    } else {
        $('#hd-back-top').hide();
    }
});

// Box Dialog
var boxDialog = $('#hd-dialog-box').modal({
    show: false,
    backdrop: 'static'
});

// Tip Dialog
var tipDialog = $('#hd-dialog-tip').modal({
    show: false
});
window.alert = function (msg) {
    if (msg != undefined && msg != '') {
        $('#hd-dialog-tip .modal-body').html('<p>' + msg + '</p>');
        tipDialog.modal('show');
    }
};

// 类目导航悬停下拉
$('#hd-header-navbar li.dropdown').hover(function () {
    $(this).find('a[data-toggle="dropdown"]').click();
}, function () {
    $(this).click();
});

// 返回顶端
$('#hd-back-top').click(function () {
    $('html').animate({
        scrollTop: '0'
    }, 600);
});

// 商品详情定位
$('#hd-prod-detail-scroll li a').click(function () {
    let idx = $('#hd-prod-detail-scroll li a').index($(this));
    let scrollTop = $('.hd-prod-detail-content').eq(idx).offset().top - 70;
    $('html').animate({
        scrollTop: scrollTop + 'px'
    }, 600);
});

// 更新购物车商品
function upCartProd(eleObj) {
    if (!eleObj.parent('div').hasClass('hd-up-cart-prod')) {
        return;
    }

    let qty = eleObj.parent('div').find('.hd-prod-qty-val').val();
    let sku = eleObj.parent('div').siblings('.hd-sku').val();

    $.ajax({
        type: 'post',
        url: '/update-cart-product.html',
        data: {
            sku: sku,
            prod_qty: qty,
            hash_tk: $.trim($('#hd-cart-tk').val())
        },
        success: function (res) {
            alert(res.msg);
            if (res.status == 'success') {
                $('#hd-cart-sub-total').text(res.cart_price);
            }
        },
        error: function () {
            alert('Unknown error, please refresh the page later and try again!');
        }
    });
}

// 商品数量、添加购物车
$(document).on('keyup', '.hd-prod-qty-val', function () {
    let qty = $(this).val().replace(/[^\d]+/, '');
    if (qty == '') {
        return;
    }

    let max_qty = parseInt($(this).data('qty'));
    qty = parseInt(qty) > max_qty ? max_qty : qty;
    $(this).val(qty);

}).on('blur', '.hd-prod-qty-val', function () {
    if ($.trim($(this).val()) == '') {
        $(this).val(1);
    }

    if ($(this).data('val') == $(this).val()) {
        return;
    }
    $(this).data('val', $(this).val());

    upCartProd($(this));

}).on('click', '.hd-prod-qty-minus', function () {
    let idx = $('.hd-prod-qty-minus').index($(this));
    let qty = $.trim($('.hd-prod-qty-val').eq(idx).val());
    qty = parseInt(qty) - 1;
    qty = qty > 0 ? qty : 1;

    $('.hd-prod-qty-val').eq(idx).val(qty);

    if ($('.hd-prod-qty-val').eq(idx).data('val') == $('.hd-prod-qty-val').eq(idx).val()) {
        return;
    }
    $('.hd-prod-qty-val').eq(idx).data('val', $('.hd-prod-qty-val').eq(idx).val());

    upCartProd($(this));

}).on('click', '.hd-prod-qty-plus', function () {
    let idx = $('.hd-prod-qty-plus').index($(this));
    let qty = $.trim($('.hd-prod-qty-val').eq(idx).val());
    qty = parseInt(qty) + 1;

    let max_qty = parseInt($(this).data('qty'));
    qty = parseInt(qty) > max_qty ? max_qty : qty;

    $('.hd-prod-qty-val').eq(idx).val(qty);

    if ($('.hd-prod-qty-val').eq(idx).data('val') == $('.hd-prod-qty-val').eq(idx).val()) {
        return;
    }
    $('.hd-prod-qty-val').eq(idx).data('val', $('.hd-prod-qty-val').eq(idx).val());

    upCartProd($(this));

}).on('click', '#hd-add-to-cart', function () {
    let idx = $('.hd-add-to-cart').index($(this));
    let sku = $('#hd-sku').val();
    let qty = $('#hd-prod-qty-val').val();
    $.ajax({
        type: 'post',
        url: '/add-to-cart.html',
        data: {
            sku: sku,
            prod_qty: qty,
            hash_tk: $.trim($('#hd-cart-tk').val())
        },
        success: function (res) {
            alert(res.msg);
            if (res.status == 'success') {
                let cartQty = res.cart_qty == undefined ? 0 : res.cart_qty;
                $('#hd-nav-icon .cart .badge, #hd-nav-icon .cart2 .badge').text(cartQty);

                let modalTitle = 'Add to Cart Successfully';
                let modalBody = '<div class="form-group hd-prod-box hd-font-size-18">' +
                    '<i class="glyphicon glyphicon-ok text-success"></i>&nbsp;' +
                    '<b class="hd-color-888 hd-display-inline-block hd-margin-right-15">' + res.add_qty + ' item(s) added to cart</b>' +
                    '<b>Total: <span class="price">' + res.add_price + '</span></b></div>' +
                    '<div class="form-group hd-width-100">' +
                    '<img style="width: 100%; border: 1px solid #ddd;" src="' + first_img_src + '" /></div>';

                let modalFooter = '<a href="/shopping/cart.html" class="btn btn-warning hd-display-inline-block hd-margin-right-15">View Cart & Checkout</a>' +
                    '<a data-dismiss="modal" class="hd-display-inline-block">Continue Shopping &gt;&gt;</a>';

                $('#hd-dialog-box .modal-title').html(modalTitle);
                $('#hd-dialog-box .modal-body').html(modalBody);
                $('#hd-dialog-box .modal-footer').html(modalFooter);

                boxDialog.modal('show');
            }
        },
        error: function () {
            alert('Unknown error, please refresh the page later and try again!');
        }
    });
}).on('click', 'a.hd-cart-remove-yes', function () {
    $('div.popover').parent('td').parent('tr').remove();
}).on('click', 'a.hd-cart-remove-no', function () {
    $('div.popover').siblings('a[data-toggle="popover"]').click();
});

// Tip Tool
$('a[data-toggle="tooltip"]').tooltip({
    placement: 'bottom',
    trigger: 'hover'
});

// Remove Cart Product Popover Tool
$('#hd-cart-products a[data-toggle="popover"]').each(function () {
    $(this).popover({
        placement: 'bottom',
        trigger: 'click',
        html: true,
        title: 'Remove from your cart ?',
        content: function () {
            return '<div class="text-right">' +
                '<a class="btn btn-sm btn-danger hd-cart-remove-yes" data-sku="' + $(this).data('sku') + '">Yes</a>&nbsp;&nbsp;' +
                '<a class="btn btn-sm btn-default hd-cart-remove-no">No</a>' +
                '</div>';
        },
        whiteList: {
            div: ['class'],
            a: ['class', 'data-sku']
        }
    })
});


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
            if (res.url != undefined && res.url != '') {
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
            if (res.url != undefined && res.url != '') {
                window.location.href = res.url;
            }
        },
        error: function () {
            alert('Unknown error, please refresh the page later and try again!');
        }
    });

    return false;
});