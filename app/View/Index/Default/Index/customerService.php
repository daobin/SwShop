<?php
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
                    <input type="text" class="form-control" name="your_name" maxlength="60" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Your Email <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="your_email" maxlength="100" />
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
            <div class="form-group">
                <label class="control-label col-md-2">Your Email <span class="text-danger">*</span></label>
                <div class="col-md-8" style="height: 34px; line-height: 34px;">shouwenlai@foxmail.com</div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Your Name <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="your_name" maxlength="60" />
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
                    <select class="form-control" name="order_time">
                        <option value="">--</option>
                        <option value="HD202109280907GYMD">HD202109280907GYMD</option>
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
        </form>
    </div>
<?php
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
