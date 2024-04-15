<?php
/**
 * Product Loop End
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-end.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php if (is_tax( 'product_cat') || is_tax( 'compound_cat') || is_tax( 'product_custom_type')): ?>
 </tbody>
        </table>
    </div>
</div>
<div><span>* for more information please send a Request for Quote</span></div>
<?php else: ?>
</ul>
<?php endif ?>
