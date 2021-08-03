<script type="text/html" id="tpl_sku_info">
    <tr>
        <td>
            <input type="text" class="layui-input hd-input-sku" name="sku_data[-IDX-][sku]"/>
        </td>
        <td>
            <?php
            $warehouses = empty($warehouses) ? ['无仓库模式' => ''] : $warehouses;
            reset($warehouses);
            foreach ($warehouses as $text => $warehouse) {
                $warehouse = xss_text($warehouse);
                ?>
                <div class="layui-form-item">
                    <div class="layui-input-inline">
                        <select name="sku_data[-IDX-][warehouse][]">
                            <?php echo '<option value="', $warehouse, '">', xss_text($text), '</option>'; ?>
                        </select>
                    </div>
                    <div class="layui-form-mid">>></div>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input hd-int-only"
                               name="sku_data[-IDX-][qty][<?php echo $warehouse; ?>]"
                               maxlength="8"
                               placeholder="库存"/>
                    </div>
                    <div class="layui-form-mid">-</div>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input hd-float-only"
                               name="sku_data[-IDX-][price][<?php echo $warehouse; ?>]"
                               maxlength="12"
                               placeholder="销售价"/>
                    </div>
                    <div class="layui-form-mid">-</div>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input hd-float-only"
                               name="sku_data[-IDX-][list_price][<?php echo $warehouse; ?>]"
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