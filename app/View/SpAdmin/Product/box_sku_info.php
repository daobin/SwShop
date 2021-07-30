<?php
if (!empty($qty_price_list)) {
    foreach ($qty_price_list as $sku => $qty_price_data) {
        ?>
        <tr>
            <td>
                <input type="text" class="layui-input" name="sku_data[<?php echo $sku; ?>][sku]"
                       value="<?php echo $sku; ?>"/>
            </td>
            <td>
                <?php
                foreach ($qty_price_data as $warehouse_code => $qty_price) {
                    $warehouse = array_search($warehouse_code, $warehouses, true);
                    $warehouse = $warehouse ? $warehouse : ($warehouse_code ? $warehouse_code : '无仓库模式');
                    ?>
                    <div class="layui-form-item">
                        <div class="layui-input-inline">
                            <select name="sku_data[<?php echo $sku; ?>][warehouse][]">
                                <?php echo '<option value="', $warehouse_code, '">', xss_text($warehouse), '</option>'; ?>
                            </select>
                        </div>
                        <div class="layui-form-mid">>></div>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input hd-int-only"
                                   name="sku_data[<?php echo $sku; ?>][qty][<?php echo $warehouse_code; ?>]"
                                   maxlength="8" value="<?php echo $qty_price['qty']; ?>"
                                   placeholder="库存"/>
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input hd-float-only"
                                   name="sku_data[<?php echo $sku; ?>][price][<?php echo $warehouse_code; ?>]"
                                   maxlength="12" value="<?php echo $qty_price['price']; ?>"
                                   placeholder="销售价"/>
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input hd-float-only"
                                   name="sku_data[<?php echo $sku; ?>][list_price][<?php echo $warehouse_code; ?>]"
                                   maxlength="12" value="<?php echo $qty_price['list_price']; ?>"
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
        <?php
    }
}