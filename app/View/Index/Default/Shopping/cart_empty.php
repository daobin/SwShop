<div class="container">
    <div class="page-header">
        <h2>Shopping Cart</h2>
    </div>
    <div class="row hidden-xs hidden-sm hd-margin-top-130">
        <div class="col-md-5 text-right">
            <img src="/static/index/default/cart.png"/>
        </div>
        <div class="col-md-7">
            <div class="text-danger hd-font-size-24">Your shopping cart is empty</div>
            <?php if (empty($customer_id)) { ?>
                <div class="hd-margin-top-30">
                    <a href="/login.html" class="text-warning">Sign In</a> to see what you left in shopping cart
                    last time.
                </div>
            <?php } ?>
            <div class="hd-margin-top-15">
                Go to <a href="/" class="text-warning">Home Page</a> and select items.
            </div>
        </div>
    </div>
    <div class="row visible-xs visible-sm hd-margin-top-60">
        <div class="col-md-5 text-center">
            <img src="/static/index/default/cart.png"/>
        </div>
        <div class="col-md-7 text-center">
            <div class="text-danger hd-font-size-24">Your shopping cart is empty</div>
            <?php if (empty($customer_id)) { ?>
                <div class="hd-margin-top-30">
                    <a href="/login.html" class="text-warning">Sign In</a> to see what you left in shopping cart
                    last time.
                </div>
            <?php } ?>
            <div class="hd-margin-top-15">
                Go to <a href="/" class="text-warning">Home Page</a> and select items.
            </div>
        </div>
    </div>
</div>
