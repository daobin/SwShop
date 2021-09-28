<?php
if (!empty($error)) {
    echo '<div class="hd-padding-10 bg-danger">', xss_text($error), '</div>';
}