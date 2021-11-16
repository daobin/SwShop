<?php
if (!empty($qty_price_list)) {
    $sku_idx = 0;
    foreach ($qty_price_list as $sku => $qty_price_data) {
        $attr_group_id = 0;
        $attr_value_id = 0;
        ?>
        <tr>
            <td class="hd-align-center">
                <?php echo $sku; ?>
                <input type="hidden" class="hd-input-sku" name="sku_data[<?php echo $sku; ?>][sku]"
                       value="<?php echo $sku; ?>"/>
            </td>
            <td>
                <div class="hd-attr-list" data-sku="<?php echo $sku; ?>"></div>
                <hr/>
                <?php
                foreach ($warehouses as $code => $name) {
                    $qty_price = $qty_price_data[$code] ?? [];
                    ?>
                    <div class="layui-form-item">
                        <div class="layui-input-inline">
                            <select name="sku_data[<?php echo $sku; ?>][warehouse][]">
                                <?php echo '<option value="', $code, '">', $name, '</option>'; ?>
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input hd-int-only"
                                   name="sku_data[<?php echo $sku; ?>][qty][<?php echo $code; ?>]"
                                   maxlength="8" value="<?php echo $qty_price['qty'] ?? ''; ?>"
                                   placeholder="库存"/>
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input hd-float-only"
                                   name="sku_data[<?php echo $sku; ?>][price][<?php echo $code; ?>]"
                                   maxlength="12" value="<?php echo $qty_price['price'] ?? ''; ?>"
                                   placeholder="销售价"/>
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input hd-float-only"
                                   name="sku_data[<?php echo $sku; ?>][list_price][<?php echo $code; ?>]"
                                   maxlength="12" value="<?php echo $qty_price['list_price'] ?? ''; ?>"
                                   placeholder="市场价"/>
                        </div>
                    </div>
                    <hr/>
                    <?php
                }
                ?>
                <div class="layui-form-item">
                    <div class="sku_images">
                        <div class="layui-fluid">
                            <div class="layui-row">
                                <div class="layui-col-xs9" id="list_img_<?php echo $sku_idx; ?>">&nbsp;
                                    <?php
                                    if (!empty($image_list[$sku])) {
                                        $imgSort = 0;
                                        foreach ($image_list[$sku] as $img) {
                                            $imgSrc = str_replace('_d_d', '_100_100', $img['image_name']) . '?' . $img['updated_at'];
                                            ?>
                                            <div style="position: relative; display: inline-block; margin: 10px; border: 1px solid #ccc;">
                                                <input type="hidden"
                                                       name="sku_data[<?php echo $sku; ?>][image][<?php echo $imgSort; ?>]"
                                                       value="<?php echo $img['image_path'] . '/' . $img['image_name']; ?>"/>
                                                <img src="<?php echo $oss_access_host . $img['image_path'] . '/' . $imgSrc; ?>"/>
                                                <i class="layui-icon layui-icon-close-fill hd-btn-del-image"
                                                   style="position: absolute; top: 0; right: 0; font-size: 20px; color: #FF5722; cursor: pointer;"></i>
                                            </div>
                                            <?php
                                            $imgSort++;
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="layui-col-xs3" style="text-align: right;">
                                    <a class="layui-btn layui-btn-warm hd-btn-open-image">
                                        <i class="layui-icon layui-icon-share"></i> 选择商品图片
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            <td class="hd-align-center">
                <i class="layui-icon layui-icon-delete btn_del_sku"></i>
            </td>
        </tr>
        <?php
        $sku_idx++;
    }
}