<div id="hd-footer" class="hd-margin-top-60">
    <div class="container">
        <div class="payment text-center hd-margin-top-bottom-15">
            <img src="/static/index/default/payment-list.png" style="max-width: 80%; height: auto;" />
        </div>
        <div class="copyright text-center">
            <span>Copyright</span>
            <span>&copy;</span>
            <span><?php echo $year;?></span>
            <span><?php echo $website_name;?>.</span>
            <span>All Rights Reserved.</span>
        </div>
    </div>
</div>
<div class="modal" id="hd-dialog-tip">
    <div class="modal-dialog modal-sm" style="margin-top: 10%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tips</h4>
            </div>
            <div class="modal-body">
               <p class="text-warning hd-font-size-16">
                   <i class="glyphicon glyphicon-info-sign"></i>
                   <span></span>
               </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-warning" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="hd-dialog-box">
    <div class="modal-dialog" style="margin-top: 10%;">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<div class="modal" id="hd-dialog-processing">
    <div class="modal-dialog modal-sm" style="margin-top: 25%;">
        <div class="modal-content">
            <div class="modal-body" style="padding: 0;">
                <img src="/static/index/default/loading.gif" style="display: block; width: 60%; margin: 0 auto;"/>
            </div>
        </div>
    </div>
</div>
<div id="hd-back-top" class="btn btn-sm btn-warning hd-display-none"><i class="glyphicon glyphicon-triangle-top"></i></div>
<script src="/static/jquery/jquery-form.min.js"></script>
<script src="/static/index/default/common.js<?php echo $timestamp ?? ''; ?>"></script>
</body>
</html>