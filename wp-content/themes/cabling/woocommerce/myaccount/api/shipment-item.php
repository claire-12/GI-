<?php
if (empty($data))
    return;

usort($data, function ($a, $b) {return strtotime($b['CommittedDeliveryDate']) - strtotime($a['CommittedDeliveryDate']);});

$mainTable = array(
    'CommittedDeliveryDate' => __('Ship Date', 'cabling'),
    'OrderQuantity' => __('Quantity', 'cabling'),
    'PurchaseOrderByCustomer' => __('P.O.', 'cabling'),
    'MaterialByCustomer' => __('Customer Part No.', 'cabling'),
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
                    <?php echo show_value_from_api('BasicMaterial', $datum['BasicMaterial']) ?><br>
                    <?php echo show_value_from_api('Material', $datum['Material']) ?>
                </td>
                <?php foreach ($mainTable as $key => $item): ?>
                    <td><?php echo show_value_from_api($key, $datum[$key]) ?></td>
                <?php endforeach ?>
                <td>
                    <?php echo show_value_from_api('ShippingMethod', $datum['ShippingMethod']) ?><br>
                    <?php echo show_value_from_api('FATrackingID', $datum['FATrackingID']) ?>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
