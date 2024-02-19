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
        <input type="text" class="form-control" name="api[sapmaterial]" id="sapmaterial">
        <label for="sapmaterial" class="form-label">Material</label>
    </div>
    <!--<div class="form-group hidden">
        <label for="date" class="form-label">Date Interval</label>
        <input type="date" class="form-control date-picker" name="api[due_date]" id="date">
    </div>-->
    <button type="submit" class="block-button">Submit</button>
    <input type="hidden" name="api_service" value="GET_DATA_PRICE">
    <input type="hidden" name="api_page" value="<?php echo CABLING_INVENTORY ?>">
</form>
<hr>
<div id="api-results"></div>