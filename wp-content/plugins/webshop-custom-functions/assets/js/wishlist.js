const myWishlist = function (options) {


    /*
     * Private method
     * Can only be called inside class
     */
    const myPrivateMethod = function () {
        console.log('accessed private method');
    };
    /*
         * Variables accessible
         * in the class
         */
    let vars = {
        addWishlistClass: '.add-to-wishlist',
        removeWishlistClass: '.remove-wishlist-product',
        updateWishlistTotal: '.quantity-update',
        wishlistToCart: '.wishlist-to-cart',
    };

    /*
     * Can access this.method
     * inside other methods using
     * root.method()
     */
    const root = this;

    const $ = jQuery.noConflict();

    /*
     * Constructor
     */
    this.construct = function (options) {
        $.extend(vars, options);

        root.addWishlistProduct();
        root.removeWishlistProduct();
        root.recalculateWishlistTotal();
        root.wishlistToCart();
    };

    this.addWishlistProduct = function () {
        $(document).on('click', vars.addWishlistClass,function (e) {
            e.preventDefault();
            const thisButton = $(this);
            const productId = thisButton.attr('data-product');
            $.ajax({
                type: 'POST',
                url: wishlist_ajax.ajaxurl,
                data: {
                    action: 'gi_add_to_wishlist',
                    product_id: productId
                },
                success: function (response) {
                    if (response.success){
                        if (response.data === 'remove_wishlist'){
                            thisButton.removeClass('has-wishlist');
                        } else {
                            thisButton.addClass('has-wishlist');
                        }
                    }
                },
                error: function (error) {
                    console.error('Error adding product to wishlist');
                }
            });
        });
    };

    this.removeWishlistProduct = function () {
        $(document).on('click', vars.removeWishlistClass,function (e) {
            e.preventDefault();
            const thisButton = $(this);
            const productId = thisButton.attr('data-product');
            $.ajax({
                type: 'POST',
                url: wishlist_ajax.ajaxurl,
                data: {
                    action: 'gi_add_to_wishlist',
                    product_id: productId
                },
                success: function (response) {
                    if (response.success){
                        if (response.data === 'remove_wishlist'){
                            thisButton.closest('.wishlist_item').parent().remove();
                        }
                    }
                },
                error: function (error) {
                    console.error('Error adding product to wishlist');
                }
            });
        });
    };

    this.recalculateWishlistTotal = function () {
        $(document).on('click', vars.updateWishlistTotal,function (e) {
            e.preventDefault();
            const thisButton = $(this);
            const data = thisButton.closest('form').serialize();
            $.ajax({
                type: 'POST',
                url: wishlist_ajax.ajaxurl,
                data: {
                    action: 'gi_calculate_wishlist_total',
                    data: data
                },
                success: function (response) {
                    if (response.success){
                        $('.wishlist-total').find('td[data-title=Total],td[data-title=Subtotal]').html(response.data)
                    }
                },
                error: function (error) {
                    console.error('Error adding product to wishlist');
                }
            });
        });
    };

    this.wishlistToCart = function () {
        $(document).on('click', vars.wishlistToCart, function (e) {
            e.preventDefault();
            const thisButton = $(this);
            const data = thisButton.closest('form').serialize();
            $.ajax({
                type: 'POST',
                url: wishlist_ajax.ajaxurl,
                data: {
                    action: 'gi_wishlist_to_cart',
                    data: data
                },
                success: function (response) {
                    if (response.success){
                        window.location.href = response.data;
                    }
                },
                error: function (error) {
                    console.error('Error adding product to wishlist');
                }
            });
        });
    };


    /*
     * Pass options when class instantiated
     */
    this.construct(options);

};


/*
 * USAGE
 */

/*
 * Set variable myVar to new value
 */
const newMyClass = new myWishlist();

/*
 * Call myMethod inside myClass
 */
//newMyClass.addWishlistProduct();
