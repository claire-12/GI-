<h2>Inventory, Lead Time and Pricing</h2>
<form id="webservice-api-form">
    <div class="form-group">
        <input type="text" class="form-control" name="api[sapcustomer]" id="sapcustomer"
               value="<?php echo get_user_meta(get_current_user_id(), 'sap_customer', true)?>"
               disabled
        >
        <label for="sapcustomer" class="form-label">Customer</label>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="api[sapmaterial]" id="parcomaterial">
        <label for="parcomaterial" class="form-label">Material</label>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="api[parcomaterial]" id="sapMaterial">
        <label for="sapmaterial" class="form-label">Part Number</label>
    </div>
    <div class="form-group mb-1">
        <input type="text" class="form-control" name="api[parcocompound]" id="parcocompound">
        <label for="parcocompound" class="form-label">Compound Number</label>
    </div>
    <p class="help-text parcocompound-text text-danger hidden">Please fill out the Part Number and Compound Number</p>
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
