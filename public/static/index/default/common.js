var prodScrollWidth = 0;
var prodScrollTop = 0;
if ($('#hd-prod-detail-scroll').length == 1) {
    prodScrollWidth = $('#hd-prod-detail-scroll').width();
    prodScrollTop = $('#hd-prod-detail-scroll').offset().top;
}
var winHeight = $(window).height();
var winHalfHeight = winHeight / 2;
var countryZoneList = [];

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

// Loading / Processing
var processingDialog = $('#hd-dialog-processing').modal({
    show: false,
    backdrop: 'static'
});

function fixedFooter() {
    if ($('#hd-footer').offset().top <= (winHeight - $('#hd-footer').innerHeight())) {
        $('#hd-footer').addClass('fixed');
    } else {
        $('#hd-footer').removeClass('fixed');
    }
}

function getCountryZoneList(countryId, init) {
    if (countryId == '0') {
        $('select[name="state_id"]').html('').addClass('hd-display-none');
        $('input[name="state"]').val('').removeClass('hd-display-none').prop('disabled', true);
        return;
    }

    if (countryZoneList[countryId] != undefined) {
        if (countryZoneList[countryId].length > 0) {
            $('input[name="state"]').val('').addClass('hd-display-none').prop('disabled', false);
            $('select[name="state_id"]').html(function () {
                var html = '';
                var stateId = $(this).data('id');
                for (let zone of countryZoneList[countryId]) {
                    if (stateId == zone['zone_id']) {
                        $(this).data('id', '');
                        html += '<option value="' + zone['zone_id'] + '" selected>' + zone['zone_name'] + '</option>';
                    } else {
                        html += '<option value="' + zone['zone_id'] + '">' + zone['zone_name'] + '</option>';
                    }
                }
                return html;
            }).removeClass('hd-display-none');
        } else {
            $('select[name="state_id"]').html('').addClass('hd-display-none');
            $('input[name="state"]').val('').removeClass('hd-display-none').prop('disabled', false);
        }
        return;
    }

    $.ajax({
        url: '/zones.html?country_id=' + countryId,
        success: function (res) {
            if (res.zone_list != undefined) {
                countryZoneList[countryId] = res.zone_list;
                if (res.zone_list.length > 0) {
                    $('input[name="state"]').val('').addClass('hd-display-none').prop('disabled', false);
                    $('select[name="state_id"]').html(function () {
                        var html = '';
                        var stateId = $(this).data('id');
                        for (let zone of countryZoneList[countryId]) {
                            if (stateId == zone['zone_id']) {
                                $(this).data('id', '');
                                html += '<option value="' + zone['zone_id'] + '" selected>' + zone['zone_name'] + '</option>';
                            } else {
                                html += '<option value="' + zone['zone_id'] + '">' + zone['zone_name'] + '</option>';
                            }
                        }
                        return html;
                    }).removeClass('hd-display-none');
                } else {
                    $('select[name="state_id"]').html('').addClass('hd-display-none');
                    if (init !== true) {
                        $('input[name="state"]').val('');
                    }
                    $('input[name="state"]').removeClass('hd-display-none').prop('disabled', false);
                }
            }
        },
        error: function () {
            alert('Unknown error, please refresh the page later and try again!');
        }
    });
}

// Get country's zone list
getCountryZoneList($.trim($('select[name="country_id"]').val()), true);

$('select[name="country_id"]').change(function () {
    getCountryZoneList($.trim($(this).val()));
});

fixedFooter();

$(document).scroll(function () {
    var winScrollTop = $(this).scrollTop();

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

// Category Nav Hover
$('#hd-header-navbar li.dropdown').hover(function () {
    $(this).find('a[data-toggle="dropdown"]').click();
}, function () {
    $(this).click();
});

// Back to Top
$('#hd-back-top').click(function () {
    $('html').animate({
        scrollTop: '0'
    }, 600);
});

// Prod desc position
$('#hd-prod-detail-scroll li a').click(function () {
    $('#hd-prod-detail-scroll li').removeClass('active');
    $(this).parent('li').addClass('active');

    var idx = $('#hd-prod-detail-scroll li a').index($(this));
    var scrollTop = $('.hd-prod-detail-content').eq(idx).offset().top - 70;
    $('html').animate({
        scrollTop: scrollTop + 'px'
    }, 600);
});

function upCartProd(eleObj) {
    if (!eleObj.parent('div').hasClass('hd-up-cart-prod')) {
        return;
    }

    var qty = eleObj.parent('div').find('.hd-prod-qty-val').val();
    var sku = eleObj.parent('div').siblings('.hd-sku').val();

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
                console.log(eleObj.parent('div.hd-up-cart-prod').parent('td').siblings('.hd-prod-total'));
                eleObj.parent('div.hd-up-cart-prod').parent('td').siblings('.hd-prod-total').text(res.prod_price);
                $('#hd-cart-sub-total').text(res.cart_price);
            }
        },
        error: function () {
            alert('Unknown error, please refresh the page later and try again!');
        }
    });
}

$(document).on('click', '.hd-btn-search', function () {
    var keywords = $.trim($(this).siblings('input.hd-search').val());
    if (keywords == '') {
        return false;
    }

    window.location.href = '/search.html?keywords=' + encodeURIComponent(keywords);

}).on('keyup', '.hd-prod-qty-val', function () {
    var qty = $(this).val().replace(/[^\d]+/, '');
    if (qty == '') {
        return;
    }

    var max_qty = parseInt($(this).data('qty'));
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
    var idx = $('.hd-prod-qty-minus').index($(this));
    var qty = $.trim($('.hd-prod-qty-val').eq(idx).val());
    qty = parseInt(qty) - 1;
    qty = qty > 0 ? qty : 1;

    $('.hd-prod-qty-val').eq(idx).val(qty);

    if ($('.hd-prod-qty-val').eq(idx).data('val') == $('.hd-prod-qty-val').eq(idx).val()) {
        return;
    }
    $('.hd-prod-qty-val').eq(idx).data('val', $('.hd-prod-qty-val').eq(idx).val());

    upCartProd($(this));

}).on('click', '.hd-prod-qty-plus', function () {
    var idx = $('.hd-prod-qty-plus').index($(this));
    var qty = $.trim($('.hd-prod-qty-val').eq(idx).val());
    qty = parseInt(qty) + 1;

    var max_qty = parseInt($(this).data('qty'));
    qty = parseInt(qty) > max_qty ? max_qty : qty;

    $('.hd-prod-qty-val').eq(idx).val(qty);

    if ($('.hd-prod-qty-val').eq(idx).data('val') == $('.hd-prod-qty-val').eq(idx).val()) {
        return;
    }
    $('.hd-prod-qty-val').eq(idx).data('val', $('.hd-prod-qty-val').eq(idx).val());

    upCartProd($(this));

}).on('click', '#hd-add-to-cart', function () {
    var idx = $('.hd-add-to-cart').index($(this));
    var sku = $('#hd-sku').val();
    var qty = $('#hd-prod-qty-val').val();
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
                var cartQty = res.cart_qty == undefined ? 0 : res.cart_qty;
                $('#hd-nav-icon .cart .badge, #hd-nav-icon .cart2 .badge').text(cartQty);

                var modalTitle = 'Add to Cart Successfully';
                var modalBody = '<div class="form-group hd-prod-box hd-font-size-18">' +
                    '<i class="glyphicon glyphicon-ok text-success"></i>&nbsp;' +
                    '<b class="hd-color-888 hd-display-inline-block hd-margin-right-15">' + res.add_qty + ' item(s) added to cart</b>' +
                    '<b>Total: <span class="price">' + res.add_price + '</span></b></div>' +
                    '<div class="form-group hd-width-100">' +
                    '<img style="width: 100%; border: 1px solid #ddd;" src="' + first_img_src + '" /></div>';

                var modalFooter = '<a href="/shopping/cart.html" class="btn btn-warning hd-display-inline-block hd-margin-right-15">View Cart & Checkout</a>' +
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
    processingDialog.modal('show');
    $.ajax({
        url: '/delete-cart-product.html',
        type: 'post',
        data: {
            sku: $.trim($(this).data('sku')),
            hash_tk: $.trim($('#hd-cart-tk').val())
        },
        success: function (res) {
            processingDialog.modal('hide');
            alert(res.msg);
            if (res.status != 'success') {
                return;
            }

            $('div.popover').parent('td').parent('tr').remove();
            if ($('#hd-cart-products tr').length <= 1) {
                var empty = $('#hd-cart-products').parent('div.container').siblings('div.hd-display-none');
                $('#hd-cart-products').parent('div.container').remove();
                empty.removeClass('hd-display-none');
                $('#hd-back-top').hide();
            }

            fixedFooter();
        },
        error: function () {
            processingDialog.modal('hide');
            alert('Unknown error, please refresh the page later and try again!');
        }
    });
}).on('click', 'a.hd-cart-remove-no', function () {
    $('div.popover').siblings('a[data-toggle="popover"]').click();
}).on('click', 'a.hd-addr-remove-yes', function () {
    processingDialog.modal('show');
    $.ajax({
        url: '/delete-address.html',
        type: 'post',
        data: {
            addr: $.trim($(this).data('addr')),
            hash_tk: $.trim($('#hd-addr-tk').val())
        },
        success: function (res) {
            processingDialog.modal('hide');
            alert(res.msg);
            if (res.status != 'success') {
                return;
            }

            $('div.popover').parent('div.hd-address-opt').parent('div.hd-address').remove();
            fixedFooter();
        },
        error: function () {
            processingDialog.modal('hide');
            alert('Unknown error, please refresh the page later and try again!');
        }
    });
}).on('click', 'a.hd-addr-remove-no', function () {
    $('div.popover').siblings('a[data-toggle="popover"]').click();
}).on('click', '.hd-set-default input[type="radio"]', function () {
    if ($('#hd-addr-def').val() == $(this).val()) {
        return false;
    }

    $('.hd-set-default input[type="checkbox"]').prop('checked', false);
    $(this).prop('checked', true);

    processingDialog.modal('show');
    $.ajax({
        url: '/default-address.html',
        type: 'post',
        data: {
            addr: $.trim($(this).val()),
            hash_tk: $.trim($('#hd-addr-tk').val())
        },
        success: function (res) {
            processingDialog.modal('hide');
            alert(res.msg);
            if (res.status != 'success') {
                $('.hd-set-default input[value="' + $('#hd-addr-def').val() + '"]').prop('checked', true);
                return;
            }
        },
        error: function () {
            processingDialog.modal('hide');
            $('.hd-set-default input[value="' + $('#hd-addr-def').val() + '"]').prop('checked', true);

            alert('Unknown error, please refresh the page later and try again!');
        }
    });
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
            h3: ['class'],
            div: ['class'],
            a: ['class', 'data-sku']
        }
    })
});

// Remove Address Popover Tool
$('#hd-address-list a[data-toggle="popover"]').each(function () {
    $(this).popover({
        placement: 'bottom',
        trigger: 'click',
        html: true,
        title: 'Remove from your address book ?',
        content: function () {
            return '<div class="text-right">' +
                '<a class="btn btn-sm btn-danger hd-addr-remove-yes" data-addr="' + $(this).data('addr') + '">Yes</a>&nbsp;&nbsp;' +
                '<a class="btn btn-sm btn-default hd-addr-remove-no">No</a>' +
                '</div>';
        },
        whiteList: {
            h3: ['class'],
            div: ['class'],
            a: ['class', 'data-addr']
        }
    })
});

// Text to pwd
$(document).on('focus', '.hd-password', function () {
    $(this).prop('type', 'password');
});

$('li.hd-nav-tag').click(function () {
    $('li.hd-nav-tag').removeClass('active');
    $(this).parent('ul').siblings('form.hd-form').hide();

    $(this).addClass('active');
    $(this).parent('ul').siblings('form.hd-form').eq($('li.hd-nav-tag').index($(this))).show();
});

// Form Submit
$('.hd-form').submit(function () {
    $(this).ajaxSubmit({
        dataType: 'json',
        success: function (res) {
            if (res.url != undefined && res.url != '') {
                window.location.href = res.url;
            } else {
                alert(res.msg);
            }
        },
        error: function () {
            alert('Unknown error, please refresh the page later and try again!');
        }
    });

    return false;
});

