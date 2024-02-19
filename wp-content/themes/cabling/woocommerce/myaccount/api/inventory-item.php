<?php
if (empty($data))
    return;

$backlogSingleTable = array(
    'ordered_quantity' => __('Quantity Ordered', 'cabling'),
    'open_quantity' => __('Quantity Remaining', 'cabling'),
    'price_per_unit' => __('Price/Unit', 'cabling'),
    'price_unit' => __('Unit', 'cabling'),
    'remaining_value' => __('Remaining Value', 'cabling'),
    'due_date' => __('Due Date', 'cabling'),
    'lv_shipping_method' => __('Shipping Method', 'cabling'),
);
$backlogMainTable = array(
    'ponumber' => __('Number', 'cabling'),
    'customer_part_number' => __('Customer Part', 'cabling'),
    'parcomaterial' => __('Parco Part', 'cabling'),
    'parcocompound' => __('Parco Compound', 'cabling'),
    'sapMaterial' => __('SAP Part', 'cabling'),
    'ship_date' => __('Parco Ship Date', 'cabling'),
    'open_quantity' => __('Quantity Remaining', 'cabling'),
    'remaining_value' => __('Remaining Value', 'cabling'),
);
?>
<?php if (is_array($data['stock'])): ?>
    <div class="table-responsive">
        <h2 class="table-heading">Inventory</h2>
        <table id="inventory-table" class="table table-bordered text-center">
            <thead>
            <tr>
                <th><?php echo __('Due Date', 'cabling') ?></th>
                <th><?php echo __('Purchase Order', 'cabling') ?></th>
                <th><?php echo __('Remaining Quantity', 'cabling') ?></th>
                <th><?php echo __('Available Inventory', 'cabling') ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <?php
                    $totalQty = 0;
                    foreach ($data['stock'] as $s) {
                        $totalQty += floatval($s['quantity']);
                    }
                    echo $totalQty;
                    ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="table-responsive">
                <h2 class="table-heading">Lead time</h2>
                <table id="lead-table" class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th><?php echo __('Weeks', 'cabling') ?></th>
                        <th><?php echo __('Est Ship Date', 'cabling') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data['stock'] as $lead): ?>
                        <tr>
                            <td><?php echo show_value_from_api('lead_time', $lead['lead_time']) ?></td>
                            <td><?php echo show_value_from_api('estimated_ship_date', $lead['estimated_ship_date']) ?></td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="table-responsive">
                <h2 class="table-heading">Inventory By Cure Date</h2>
                <table id="inventory-cure-table" class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th><?php echo __('Cure Date', 'cabling') ?></th>
                        <th><?php echo __('Quantity', 'cabling') ?></th>
                        <th><?php echo __('Cumulative Quantity', 'cabling') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data['stock'] as $cure): ?>
                        <tr>
                            <td><?php echo show_value_from_api('cure_date', $cure['cure_date']) ?></td>
                            <td><?php echo show_value_from_api('quantity', $cure['quantity']) ?></td>
                            <td><?php echo show_value_from_api('cumulative_quantity', $cure['cumulative_quantity']) ?></td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif ?>
<?php if (is_array($data['price'])): ?>
    <div class="table-responsive">
        <h2 class="table-heading">Pricing</h2>
        <table id="price-table" class="table table-bordered text-center">
            <thead>
            <tr>
                <th colspan="2">Quantity</th>
                <th rowspan="2">Price <br> Per 100</th>
                <th rowspan="2">Minimum <br> Release</th>
            </tr>
            <tr>
                <th>From</th>
                <th>To</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data['price'] as $datum): ?>
                <tr>
                    <td><?php echo show_value_from_api('scale_from', $datum['scale_from']) ?></td>
                    <td><?php echo show_value_from_api('scale_to', $datum['scale_to']) ?></td>
                    <td><?php echo show_value_from_api('price100', $datum['price100']) ?></td>
                    <td><?php echo show_value_from_api('minimum_price', $datum['minimum_price']) ?></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php endif ?>