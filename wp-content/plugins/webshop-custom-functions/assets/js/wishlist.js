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
        addWishlistClass: '.add-to-wishlist'
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
    };

    /*
     * Public method
     * Can be called outside class
     */
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
