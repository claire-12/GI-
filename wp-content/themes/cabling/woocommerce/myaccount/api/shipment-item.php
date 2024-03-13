<?php
if (empty($data))
    return;

usort($data, function ($a, $b) {return strtotime($b['ship_date']) - strtotime($a['ship_date']);});

$mainTable = array(
    'ship_date' => __('Ship Date', 'cabling'),
    'ordered_quantity' => __('Quantity', 'cabling'),
    'ponumber' => __('P.O.', 'cabling'),
    'customer_part_number' => __('Customer Part No.', 'cabling'),
    'delivery' => __('Packing List', 'cabling'),
);
?>
<div class="table-responsive">
    <h2 class="table-heading">Shipments</h2>
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>
                    <?php echo __('Part Number', 'cabling') ?><br>
                    <?php echo __('Compound', 'cabling') ?><br>
                    <?php echo __('Material', 'cabling') ?>
                </th>
                <?php foreach ($mainTable as $name): ?>
                    <th><?php echo $name ?></th>
                <?php endforeach ?>
                <th>
                    <?php echo __('Shipping Method', 'cabling') ?><br>
                    <?php echo __('Tracking Number', 'cabling') ?>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $datum): ?>
            <tr>
                <td>
                    <?php echo show_value_from_api('parcomaterial', $datum['parcomaterial']) ?><br>
                    <?php echo show_value_from_api('parcocompound', $datum['parcocompound']) ?><br>
                    <?php echo show_value_from_api('sapMaterial', $datum['sapMaterial']) ?>
                </td>
                <?php foreach ($mainTable as $key => $item): ?>
                    <td><?php echo show_value_from_api($key, $datum[$key]) ?></td>
                <?php endforeach ?>
                <td>
                    <?php echo show_value_from_api('lv_shipping_method', $datum['lv_shipping_method']) ?><br>
                    <?php echo show_value_from_api('tracking_number', $datum['tracking_number']) ?>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
