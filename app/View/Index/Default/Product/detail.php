<?php
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
?>
    <div class="hd-height-15">&nbsp;</div>
    <div id="hd-crumb" class="container">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="/guitar-c1.html">Guitar</a></li>
            <li class="active">My Guitar</li>
        </ol>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div id="hd-main-image-loop" class="carousel">
                    <div class="carousel-inner">
                        <div class="item active">
                            <img src="https://sw-shop.oss-cn-hongkong.aliyuncs.com/sp_1/prod_img/gitar/5cca9f4f0701acbbe99de13236b249dc_800_800.jpg?1628158151"/>
                        </div>
                        <div class="item">
                            <img src="https://sw-shop.oss-cn-hongkong.aliyuncs.com/sp_1/prod_img/gitar/7e85d77b18a22f68e2e84e318fe8ea6a_800_800.jpg?1628158151"/>
                        </div>
                    </div>

                    <a class="left carousel-control" data-slide="prev" href="#hd-main-image-loop">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" data-slide="next" href="#hd-main-image-loop">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="col-md-6">

            </div>
        </div>
    </div>
<?php if ($device == 'PC') { ?>
    <script src="/static/jquery/jquery.zoom.min.js"></script>
    <script>
        $('#hd-main-image-loop .item img').click(function () {
            let imgSrc = $(this).attr('src');

            $('#hd-main-image-loop').zoom({
                url: imgSrc,
                callback: function () {
                    $('#hd-main-image-loop .zoomImg').css('opacity', '1');
                },
                onZoomOut: function () {
                    $('#hd-main-image-loop .zoomImg').remove();
                }
            });
            $(document).on('click', '#hd-main-image-loop .zoomImg', function () {
                $('#hd-main-image-loop .zoomImg').remove();
            });
        });
    </script>
    <?php
}
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
