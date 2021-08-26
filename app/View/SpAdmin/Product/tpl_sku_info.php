<script type="text/html" id="tpl_sku_info">
    <tr>
        <td>
            <input type="text" class="layui-input hd-input-sku" name="sku_data[-IDX-][sku]"/>
        </td>
        <td>
            <?php
            reset($warehouses);
            foreach ($warehouses as $code => $name) {
                ?>
                <div class="layui-form-item">
                    <div class="layui-input-inline">
                        <select name="sku_data[-IDX-][warehouse][]">
                            <?php echo '<option value="', $code, '">', $name, '</option>'; ?>
                        </select>
                    </div>
                    <div class="layui-form-mid">>></div>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input hd-int-only"
                               name="sku_data[-IDX-][qty][<?php echo $code; ?>]"
                               maxlength="8"
                               placeholder="库存"/>
                    </div>
                    <div class="layui-form-mid">-</div>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input hd-float-only"
                               name="sku_data[-IDX-][price][<?php echo $code; ?>]"
                               maxlength="12"
                               placeholder="销售价"/>
                    </div>
                    <div class="layui-form-mid">-</div>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input hd-float-only"
                               name="sku_data[-IDX-][list_price][<?php echo $code; ?>]"
                               maxlength="12"
                               placeholder="市场价"/>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="layui-form-item">
                <div class="sku_images"></div>
            </div>
        </td>
        <td class="hd-align-center">
            <i class="layui-icon layui-icon-delete btn_del_sku"></i>
        </td>
    </tr>
</script>