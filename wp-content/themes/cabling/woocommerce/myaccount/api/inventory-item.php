<?php
if (empty($data))
    return;

$backlogSingleTable = array(
    'OrderQuantity' => __('Quantity Ordered', 'cabling'),
    'OpenConfdDelivQtyInBaseUnit' => __('Quantity Remaining', 'cabling'),
    'NetPriceAmount' => __('Price/Unit', 'cabling'),
    'NetPriceQuantity' => __('Price', 'cabling'),
    'RemainingValue' => __('Remaining Value', 'cabling'),
    'RequestedDeliveryDate' => __('Due Date', 'cabling'),
    'ShippingMethod' => __('Shipping Method', 'cabling'),
);
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
                        $totalQty += floatval($s['StockQuantity']);
                    }
                    echo $totalQty;
                    ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <p><strong>Looking for a specific cure date? Please contact your assigned CSR to confirm material availability to meet your production requirements.</strong></p>
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
                        <?php
                        if (isset($previous_date) && $lead['EstimatedShipDate'] === $previous_date){continue;}
                        $previous_date = $lead['EstimatedShipDate'] ?? null;
                        ?>
                        <tr>
                            <td><?php echo show_value_from_api('LeadTimeInWeeks', $lead['LeadTimeInWeeks']) ?></td>
                            <td><?php echo show_value_from_api('EstimatedShipDate', $lead['EstimatedShipDate']) ?></td>
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
                    <?php $stock = 0; ?>
                    <?php foreach ($data['stock'] as $cure): ?>
                        <?php
                        $cumulative_quantity = get_cumulative_quantity($stock, $cure['StockQuantity']);
                        $stock = $cumulative_quantity;
                        ?>
                        <tr>
                            <td><?php echo show_value_from_api('CureDate', $cure['CureDate']) ?></td>
                            <td><?php echo show_value_from_api('StockQuantity', $cure['StockQuantity']) ?></td>
                            <td><?php echo $cumulative_quantity ?></td>
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
                    <td><?php echo show_value_from_api('ScaleFrom', $datum['ScaleFrom']) ?></td>
                    <td><?php echo show_value_from_api('ScaleTo', $datum['ScaleTo']) ?></td>
                    <td><?php echo show_value_from_api('ScalePrice', $datum['ScalePrice']) ?></td>
                    <td><?php echo show_value_from_api('MinPrice', $datum['MinPrice']) ?></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php endif ?>
