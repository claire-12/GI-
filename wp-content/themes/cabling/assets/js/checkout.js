function ci_delete_selected_address(elm, type, key) {
    thmaf_delete_selected_address(elm, type, key);
    $(elm).closest('.address-item').remove();
}

(function ($) {

})(jQuery);
