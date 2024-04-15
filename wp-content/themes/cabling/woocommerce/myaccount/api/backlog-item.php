<?php
if (empty($data))
    return;
usort($data, function ($a, $b) {return strtotime($a['ponumber']) - strtotime($b['ponumber']);});
$backlogMainTable = array(
    'ponumber' => __('Number', 'cabling'),
    'customer_part_number' => __('Customer Part', 'cabling'),
    'parcomaterial' => __('Part Number', 'cabling'),
    'parcocompound' => __('Compound', 'cabling'),
    'sapMaterial' => __('Material', 'cabling'),
    'ship_date' => __('Ship Date', 'cabling'),
    'open_quantity' => __('Quantity Remaining', 'cabling'),
    'remaining_value' => __('Remaining Value', 'cabling'),
);

$backlogSingleTable = array(
    'ordered_quantity' => __('Quantity Ordered', 'cabling'),
    'open_quantity' => __('Quantity Remaining', 'cabling'),
    'price_per_unit' => __('Price/Unit', 'cabling'),
    'price_unit' => __('Units', 'cabling'),
    'remaining_value' => __('Remaining Value', 'cabling'),
    'due_date' => __('Due Date', 'cabling'),
    'lv_shipping_method' => __('Shipping Method', 'cabling'),
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
            <tr class="backlog-row row-<?php echo $datum['ponumber'] ?>">
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
            <tr class="hidden single-<?php echo $datum['ponumber'] ?>">
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
