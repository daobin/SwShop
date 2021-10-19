<?php
$widget_params['tkd_title'] = 'Customer Service - ' . $website_name;
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
?>
    <div class="hd-height-15">&nbsp;</div>
    <div id="hd-crumb" class="container">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">Customer Service</li>
        </ol>
    </div>
    <div class="container">
        <ul class="nav nav-pills nav-justified bg-info hd-border-radius-4">
            <li class="hd-nav-tag active"><a>Pre-sales Service</a></li>
            <li class="hd-nav-tag"><a>After-sales Service</a></li>
        </ul>
        <form class="hd-form form-horizontal hd-margin-top-30" method="post" autocomplete="off">
            <div class="form-group">
                <label class="control-label col-md-2">Your Name <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="your_name" maxlength="60"
                           value="<?php echo $customer_name ?? ''; ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Your Email <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="your_email" maxlength="100"
                           value="<?php echo $customer_email ?? ''; ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Your Question <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <textarea name="your_question" class="form-control" rows="8"></textarea>
                </div>
            </div>
            <div class="form-group hd-margin-top-30">
                <div class="col-md-offset-2 col-md-10">
                    <input type="hidden" name="hash_tk" value="<?php echo $hash_tk ?? ''; ?>"/>
                    <input type="hidden" name="service" value="pre"/>
                    <input type="submit" class="btn btn-warning" value="Submit"/>
                </div>
            </div>
        </form>
        <form class="hd-form form-horizontal hd-margin-top-30 hd-display-none" method="post" autocomplete="off">
            <?php
            if (empty($customer_email)) {
                ?>
                <div class="hd-padding-10 text-warning hd-error-tip hd-font-size-18">
                    <i class="glyphicon glyphicon-remove"></i>
                    &nbsp;
                    After you <a href="/login.html">sign in</a>,
                    you'll be able to choose an order to submit your problem.
                </div>
                <?php
            } else {
                ?>
                <div class="form-group">
                    <label class="control-label col-md-2">Your Email <span class="text-danger">*</span></label>
                    <div class="col-md-8"
                         style="height: 34px; line-height: 34px;"><?php echo $customer_email ?? ''; ?></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2">Your Name <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="your_name" maxlength="60"
                               value="<?php echo $customer_name ?? ''; ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2">Your Order Time <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <select class="form-control" name="order_time">
                            <option value="0">--</option>
                            <option value="30">Orders in last 30 days</option>
                            <option value="90">Orders in last 90 days</option>
                            <option value="180">Orders in last 180 days</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2">Your Order Number <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <select class="form-control" name="order_number">
                            <option value="">--</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2">Your Question <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <textarea name="your_question" class="form-control" rows="8"></textarea>
                    </div>
                </div>
                <div class="form-group hd-margin-top-30">
                    <div class="col-md-offset-2 col-md-10">
                        <input type="hidden" name="hash_tk" value="<?php echo $hash_tk ?? ''; ?>"/>
                        <input type="hidden" name="service" value="after"/>
                        <input type="submit" class="btn btn-warning" value="Submit"/>
                    </div>
                </div>
            <?php } ?>
        </form>
    </div>
    <script>
        var orderNumbers = [];
        function resetOrderNumbers(days) {
            $('select[name="order_number"]').html('<option value="">--</option>');

            if (days == '0') {
                return;
            }

            if (orderNumbers[days] != undefined) {
                orderNumbers[days].forEach(function (number) {
                    $('select[name="order_number"]').append('<option value="' + number + '">' + number + '</option>');
                });
                return;
            }

            $.ajax({
                url: '/order-numbers.html',
                data: {days: days}
            }).then(function (res) {
                if (res.order_numbers == undefined) {
                    return;
                }

                orderNumbers[days] = res.order_numbers;
                orderNumbers[days].forEach(function (number) {
                    $('select[name="order_number"]').append('<option value="' + number + '">' + number + '</option>');
                });
            }, function () {
                alert('Unknown error, please refresh the page later and try again!');
            });
        }

        $('select[name="order_time"]').change(function () {
            resetOrderNumbers($(this).val());
        });

    </script>
<?php
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
