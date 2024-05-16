<?php
if (empty($data))
    return;
usort($data, function ($a, $b) {return strtotime($a['PurchaseOrderByCustomer']) - strtotime($b['PurchaseOrderByCustomer']);});
$backlogMainTable = array(
    'PurchaseOrderByCustomer' => __('Number', 'cabling'),
    'MaterialByCustomer' => __('Customer Part', 'cabling'),
    'OldMaterialNumber' => __('Part Number', 'cabling'),
    'BasicMaterial' => __('Compound', 'cabling'),
    'Material' => __('Material', 'cabling'),
    'CommittedDeliveryDate' => __('Ship Date', 'cabling'),
    'OpenConfdDelivQtyInBaseUnit' => __('Quantity Remaining', 'cabling'),
    'RemainingValue' => __('Remaining Value', 'cabling'),
);

$backlogSingleTable = array(
    'OrderQuantity' => __('Quantity Ordered', 'cabling'),
    'OpenConfdDelivQtyInBaseUnit' => __('Quantity Remaining', 'cabling'),
    'NetPriceAmount' => __('Price/Unit', 'cabling'),
    'NetPriceQuantity' => __('Units', 'cabling'),
    'RemainingValue' => __('Remaining Value', 'cabling'),
    'RequestedDeliveryDate' => __('Due Date', 'cabling'),
    'ShippingMethod' => __('Shipping Method', 'cabling'),
);
?>
<div class="table-responsive">
    <h2 class="table-heading">Purchase orders</h2>
    <table class="table table-bordered text-center">
        <thead>
        <tr>
            <?php foreach ($backlogMainTable as $name): ?>
                <th><?php echo $name ?></th>
            <?php endforeach ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $datum): ?>
            <tr class="backlog-row row-<?php echo $datum['PurchaseOrderByCustomer'] ?>">
                <?php foreach ($backlogMainTable as $key => $item): ?>
                    <td
                        class="<?php echo $key ?>"
                        data-name="<?php echo $key ?>"
                        data-<?php echo $key ?>="<?php echo show_value_from_api($key, $datum[$key]) ?>"
                    >
                        <?php echo show_value_from_api($key, $datum[$key]) ?>
                    </td>
                <?php endforeach ?>
            </tr>
            <tr class="hidden single-<?php echo $datum['PurchaseOrderByCustomer'] ?>">
                <?php foreach ($backlogSingleTable as $keyS => $itemS): ?>
                    <td><?php echo show_value_from_api($keyS, $datum[$keyS]) ?></td>
                <?php endforeach ?>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
<div id="table-order-detail" class="table-responsive hidden">
    <h2 class="table-heading">Sale Backlog - For Order/PO <span></span></h2>
    <table class="table table-bordered text-center">
        <thead>
        <tr>
            <?php foreach ($backlogSingleTable as $nameSingle): ?>
                <th><?php echo $nameSingle ?></th>
            <?php endforeach ?>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
