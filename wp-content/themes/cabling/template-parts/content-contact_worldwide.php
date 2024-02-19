<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cabling
 */
$contact_types = get_terms(array('taxonomy' => 'contact_type','parent' => 0));
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 style="margin-bottom: 0;"><?php printf( __('Contacts %s','cabling'), get_the_title()) ?></h1>
	</header><!-- .entry-header -->

	<div class="entry-content" style="margin-top: 0;">
		<div class="row">
			<div class="col-md-8"><?php the_content(); ?></div>
		</div>		
	</div><!-- .entry-content -->

	<div id="accordion">
		<?php 
		if( $contact_types )
		{
			foreach($contact_types as $type )
			{
				//cabling_get_contact_wordwide( $type, get_the_ID(), 'contact-parent' );
				
				$contact_children = get_terms(array('taxonomy' => 'contact_type','parent' => $type->term_id ));
				if( $contact_children )
				{
					foreach($contact_children as $child )
					{
						cabling_get_contact_wordwide( $child, get_the_ID(), 'contact-parent' );
					}
				} 
			}
		} 
		?>
	</div>

</article><!-- #post-<?php the_ID(); ?> -->