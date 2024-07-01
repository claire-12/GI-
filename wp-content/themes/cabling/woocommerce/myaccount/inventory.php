<h2>Inventory, Lead Time and Pricing</h2>
<form id="webservice-api-form">
    <p class="form-error-text alert alert-danger mb-4 hidden"><i class="fa-solid fa-triangle-exclamation me-2"></i>Please fill out the filter</p>
    <p class="parcocompound-text alert alert-danger mb-4 hidden"><i class="fa-solid fa-triangle-exclamation me-2"></i>Please fill out the Part Number and Compound Number</p>
    <p><strong>For products from Vandalia, please use the product’s SKU / material number; for Ontario products, you can use either the SKU / material number or part and compound number</strong></p>
    <div class="form-group">
        <input type="text" class="form-control" name="api[SoldToParty]" id="SoldToParty"
               value="<?php echo get_user_meta(get_current_user_id(), 'sap_customer', true)?>"
               disabled
        >
        <label for="SoldToParty" class="form-label">Customer</label>
    </div>
    <p><strong>Search by Material Number</strong></p>
    <div class="form-group">
        <input type="text" class="form-control" name="api[Material]" id="parcomaterial">
        <label for="parcomaterial" class="form-label">Material / SKU</label>
    </div>
    <p><strong>Or search both Part and Compound Numbers. The Part Number is normally 7-8 digits, but If you’re searching for a Parco AS568 O-ring, you can just enter the 3- or 4-digit size number.</strong></p>
    <div class="form-group">
        <input type="text" class="form-control" name="api[MaterialOldNumber]" id="sapMaterial">
        <label for="sapmaterial" class="form-label">Part Number</label>
    </div>
    <div class="form-group mb-1">
        <input type="text" class="form-control" name="api[BasicMaterial]" id="parcocompound">
        <label for="parcocompound" class="form-label">Compound Number</label>
    </div>
    <!--<div class="form-group hidden">
        <label for="date" class="form-label">Date Interval</label>
        <input type="date" class="form-control date-picker" name="api[due_date]" id="date">
    </div>-->
    <button type="submit" class="block-button mt-3">Submit</button>
    <input type="hidden" name="api_service" value="GET_DATA_PRICE">
    <input type="hidden" name="api_page" value="<?php echo CABLING_INVENTORY ?>">
</form>
<hr>
<div id="api-results"></div>
