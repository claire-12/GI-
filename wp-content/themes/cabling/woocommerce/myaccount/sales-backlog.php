<h2>Sales Backlog</h2>
<form id="webservice-api-form" class="backlog-form" method="post">
    <p class="form-error-text alert alert-danger mb-4 hidden"><i class="fa-solid fa-triangle-exclamation me-2"></i>Please fill out the filter</p>
    <p class="parcocompound-text alert alert-danger mb-4 hidden"><i class="fa-solid fa-triangle-exclamation me-2"></i>Please fill out the Part Number and Compound Number</p>
    <div class="form-group">
        <input type="text" class="form-control" name="api[sapcustomer]" id="sapcustomer"
               value="<?php echo get_user_meta(get_current_user_id(), 'sap_customer', true)?>"
               disabled
        >
        <label for="sapcustomer" class="form-label">Customer</label>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="api[sapMaterial]" id="sapMaterial1">
        <label for="sapMaterial1" class="form-label">Material</label>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="api[ponumber]" id="ponumber1">
        <label for="ponumber1" class="form-label">Purchase Order</label>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="api[parcomaterial]" id="parcomaterial1">
        <label for="parcomaterial1" class="form-label">Part Number</label>
    </div>
    <div class="form-group mb-1">
        <input type="text" class="form-control" name="api[parcocompound]" id="parcocompound1">
        <label for="parcocompound1" class="form-label">Compound Number</label>
    </div>
    <!--<div class="form-group hidden">
        <label for="date" class="form-label">Date Interval</label>
        <input type="date" class="form-control date-picker" name="api[due_date]" id="date">
    </div>-->
    <button type="submit" class="block-button mt-3">Submit</button>
    <input type="hidden" name="api_service" value="GET_DATA_BACKLOG">
    <input type="hidden" name="show_ponumber" value="">
    <input type="hidden" name="api_page" value="<?php echo CABLING_BACKLOG ?>">
</form>
<hr>
<div id="api-results"></div>
