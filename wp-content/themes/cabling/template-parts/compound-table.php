<?php
$term = get_queried_object();
$compounds = get_compound_product($term->term_id);
?>
<?php //print_r($term); ?>
<div class="table-responsive">
    <table class="table table-bordered product-variation-table">
		<!--
			<th class="has-text-align-center" data-align="center">Elastomer</th>
            <th class="has-text-align-center" data-align="center">Drawing</th>
            <th class="has-text-align-center" data-align="center">Part Number Format</th>
            <th class="has-text-align-center" data-align="center">Material Specification</th>
            <th class="has-text-align-center" data-align="center">Compound</th>
			<th class="has-text-align-center" data-align="center">Key properties</th>
            <th class="has-text-align-center" data-align="center">Most Recent Approval</th>
            <th class="has-text-align-center" data-align="center">Approval Date</th>
            <th class="has-text-align-center" data-align="center"></th>
		-->
        <thead>
        <tr>
			<?php if($term->slug=='masen' ||$term->slug=='qplen'){ ?>
			<th class="has-text-align-center" data-align="center">Part Number Format</th>
            <th class="has-text-align-center" data-align="center">Drawing</th>
            <th class="has-text-align-center" data-align="center">Material Specification</th>
            <th class="has-text-align-center" data-align="center">Compound</th>
            <th class="has-text-align-center" data-align="center"></th>
			<?php }else{?>
			<th class="has-text-align-center" data-align="center">Elastomer</th>
			<th class="has-text-align-center" data-align="center">Compound</th>
			<th class="has-text-align-center" data-align="center">Durometer</th>
            <th class="has-text-align-center" data-align="center">Color</th>
			<th class="has-text-align-center" data-align="center">Key properties</th>
            <th class="has-text-align-center" data-align="center"></th>
			<?php }?>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($compounds as $compound): ?>
            <?php $link = add_query_arg('certification-compound', $term->slug, home_url('/products-and-services/')) ?>
            <tr>
				<?php if($term->slug=='masen' ||$term->slug=='qplen'){ ?>
				<td class="has-text-align-center" data-align="center"><?php echo get_product_field('_compound_number_format', $compound) ?></td>
				<td class="has-text-align-center" data-align="center"><?php echo get_product_field('_compound_drawing', $compound) ?></td>
				<td class="has-text-align-center" data-align="center"><?php echo get_product_field('_compound_material_specification', $compound) ?></td>
				<td class="has-text-align-center" data-align="center">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#compoundModal<?php echo $compound ?>">
                        <?php echo get_the_title($compound) ?>
                    </a>
                    <?php get_template_part('template-parts/modal/compound', 'modal', ['compound' => $compound]); ?>
                </td>
				<?php }else{?>
				<td class="has-text-align-center" data-align="center"><?php echo get_product_field('_elastomer', $compound) ?></td>
				<td class="has-text-align-center" data-align="center">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#compoundModal<?php echo $compound ?>">
                        <?php echo get_the_title($compound) ?>
                    </a>
                    <?php get_template_part('template-parts/modal/compound', 'modal', ['compound' => $compound]); ?>
                </td>
				<td class="has-text-align-center" data-align="center"><?php echo get_product_field('_durometer', $compound) ?></td>
				<td class="has-text-align-center" data-align="center"><?php echo get_product_field('_colour', $compound) ?></td>
				<td class="has-text-align-center" data-align="center"><?php echo get_product_field('_key_properties', $compound) ?></td>
				<?php }?>
				
				<?php if (1==0){ ?>
				<td class="has-text-align-center" data-align="center">
                    <?php echo get_product_field('_elastomer', $compound) ?>
                </td>
                <td class="has-text-align-center" data-align="center">
                    <?php echo get_product_field('_compound_drawing', $compound) ?>
                </td>
                <td class="has-text-align-center" data-align="center">
                    <?php echo get_product_field('_compound_number_format', $compound) ?>
                </td>
                <td class="has-text-align-center" data-align="center">
                    <?php echo get_product_field('_compound_material_specification', $compound) ?>
                </td>
                <td class="has-text-align-center" data-align="center">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#compoundModal<?php echo $compound ?>">
                        <?php echo get_the_title($compound) ?>
                    </a>
                    <?php get_template_part('template-parts/modal/compound', 'modal', ['compound' => $compound]); ?>
                </td>
                <td class="has-text-align-center" data-align="center">
                    <?php echo get_product_field('_compound_most_recent_approval', $compound) ?>
                </td>
                <td class="has-text-align-center" data-align="center">
                    <?php echo get_product_field('_compound_approval_date', $compound) ?>
                </td>

				<td class="has-text-align-center" data-align="center">
                    <?php echo get_product_field('_key_properties', $compound) ?>
                </td>
				<?php } ?>
                <td><a href="<?php echo esc_url($link) ?>">View Product</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
