<?php
function cptui_register_my_cpts() {
	/**
	 * Post Type: News.
	 */

	$labels = [
		"name" => __( "News", "cabling" ),
		"singular_name" => __( "News", "cabling" ),
	];

	$args = [
		"label" => __( "News", "cabling" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"menu_icon" => "dashicons-format-aside",
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => true,
		"rewrite" => [ "slug" => "news", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail", "page-attributes", "excerpt" ],
	];

//	register_post_type( "company_news", $args );

	/**
	 * Post Type: News.
	 */

	$labels = [
		"name" => __( "Press", "cabling" ),
		"singular_name" => __( "Press", "cabling" ),
	];

	$args = [
		"label" => __( "Press", "cabling" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"menu_icon" => "dashicons-format-aside",
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => true,
		"rewrite" => [ "slug" => "company-press", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail", "page-attributes", "excerpt" ],
	];

	register_post_type( "company_press", $args );

	/**
	 * Post Type: Events.
	 */

	$labels = [
		"name" => __( "Events", "cabling" ),
		"singular_name" => __( "Event", "cabling" ),
		"menu_name" => __( "Events", "cabling" ),
		"all_items" => __( "All Events", "cabling" ),
		"add_new" => __( "Add new", "cabling" ),
		"add_new_item" => __( "Add new Event", "cabling" ),
		"edit_item" => __( "Edit Event", "cabling" ),
		"new_item" => __( "New Event", "cabling" ),
		"view_item" => __( "View Event", "cabling" ),
		"view_items" => __( "View Events", "cabling" ),
		"search_items" => __( "Search Events", "cabling" ),
		"not_found" => __( "No Events found", "cabling" ),
		"not_found_in_trash" => __( "No Events found in trash", "cabling" ),
		"parent" => __( "Parent Event:", "cabling" ),
		"featured_image" => __( "Featured image for this Event", "cabling" ),
		"set_featured_image" => __( "Set featured image for this Event", "cabling" ),
		"remove_featured_image" => __( "Remove featured image for this Event", "cabling" ),
		"use_featured_image" => __( "Use as featured image for this Event", "cabling" ),
		"archives" => __( "Event archives", "cabling" ),
		"insert_into_item" => __( "Insert into Event", "cabling" ),
		"uploaded_to_this_item" => __( "Upload to this Event", "cabling" ),
		"filter_items_list" => __( "Filter Events list", "cabling" ),
		"items_list_navigation" => __( "Events list navigation", "cabling" ),
		"items_list" => __( "Events list", "cabling" ),
		"attributes" => __( "Events attributes", "cabling" ),
		"name_admin_bar" => __( "Event", "cabling" ),
		"item_published" => __( "Event published", "cabling" ),
		"item_published_privately" => __( "Event published privately.", "cabling" ),
		"item_reverted_to_draft" => __( "Event reverted to draft.", "cabling" ),
		"item_scheduled" => __( "Event scheduled", "cabling" ),
		"item_updated" => __( "Event updated.", "cabling" ),
		"parent_item_colon" => __( "Parent Event:", "cabling" ),
	];

	$args = [
		"label" => __( "Events", "cabling" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => true,
		"rewrite" => [ "slug" => "event", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail", "page-attributes" ],
	];

	register_post_type( "event", $args );


	/**
	 * Post Type: Seminars.
	 */

	$labels = [
		"name" => __( "Seminars", "cabling" ),
		"singular_name" => __( "Seminar", "cabling" ),
	];

	$args = [
		"label" => __( "Seminars", "cabling" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "seminar", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail" ],
	];

	register_post_type( "seminar", $args );

	/**
	 * Post Type: Trainings.
	 */

	$labels = [
		"name" => __( "Training", "cabling" ),
		"singular_name" => __( "Training", "cabling" ),
		"menu_name" => __( "My Trainings", "cabling" ),
		"all_items" => __( "All Trainings", "cabling" ),
		"add_new" => __( "Add new", "cabling" ),
		"add_new_item" => __( "Add new Training", "cabling" ),
		"edit_item" => __( "Edit Training", "cabling" ),
		"new_item" => __( "New Training", "cabling" ),
		"view_item" => __( "View Training", "cabling" ),
		"view_items" => __( "View Trainings", "cabling" ),
		"search_items" => __( "Search Trainings", "cabling" ),
		"not_found" => __( "No Trainings found", "cabling" ),
		"not_found_in_trash" => __( "No Trainings found in trash", "cabling" ),
		"parent" => __( "Parent Training:", "cabling" ),
		"featured_image" => __( "Featured image for this Training", "cabling" ),
		"set_featured_image" => __( "Set featured image for this Training", "cabling" ),
		"remove_featured_image" => __( "Remove featured image for this Training", "cabling" ),
		"use_featured_image" => __( "Use as featured image for this Training", "cabling" ),
		"archives" => __( "Training archives", "cabling" ),
		"insert_into_item" => __( "Insert into Training", "cabling" ),
		"uploaded_to_this_item" => __( "Upload to this Training", "cabling" ),
		"filter_items_list" => __( "Filter Trainings list", "cabling" ),
		"items_list_navigation" => __( "Trainings list navigation", "cabling" ),
		"items_list" => __( "Trainings list", "cabling" ),
		"attributes" => __( "Trainings attributes", "cabling" ),
		"name_admin_bar" => __( "Training", "cabling" ),
		"item_published" => __( "Training published", "cabling" ),
		"item_published_privately" => __( "Training published privately.", "cabling" ),
		"item_reverted_to_draft" => __( "Training reverted to draft.", "cabling" ),
		"item_scheduled" => __( "Training scheduled", "cabling" ),
		"item_updated" => __( "Training updated.", "cabling" ),
		"parent_item_colon" => __( "Parent Training:", "cabling" ),
	];

	$args = [
		"label" => __( "Training", "cabling" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "training", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail", "excerpt", "page-attributes" ],
	];

	register_post_type( "training", $args );
	/**
	 * Post Type: FAQ.
	 */

	$labels = [
		"name" => __( "FAQ", "cabling" ),
		"singular_name" => __( "FAQ", "cabling" ),
		"menu_name" => __( "FAQ", "cabling" ),
	];

	$args = [
		"label" => __( "FAQ", "cabling" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"menu_icon" => 'dashicons-list-view',
		"rewrite" => [ "slug" => "faq", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "page-attributes"],
	];

	register_post_type( "faq", $args );
}

//add_action( 'init', 'cptui_register_my_cpts' );

//taxonomy
function cabling_register_my_taxes() {

	/**
	 * Taxonomy: Training Locations.
	 */

	$training_labels = [
		"name" => __( "Locations", "cabling" ),
		"singular_name" => __( "Location", "cabling" ),
		"menu_name" => __( "Locations", "cabling" ),
		"all_items" => __( "All Locations", "cabling" ),
		"edit_item" => __( "Edit Location", "cabling" ),
		"view_item" => __( "View Location", "cabling" ),
		"update_item" => __( "Update Location name", "cabling" ),
		"add_new_item" => __( "Add new Location", "cabling" ),
		"new_item_name" => __( "New Location name", "cabling" ),
		"parent_item" => __( "Parent Location", "cabling" ),
		"parent_item_colon" => __( "Parent Location:", "cabling" ),
		"search_items" => __( "Search Locations", "cabling" ),
		"popular_items" => __( "Popular Locations", "cabling" ),
		"separate_items_with_commas" => __( "Separate Locations with commas", "cabling" ),
		"add_or_remove_items" => __( "Add or remove Locations", "cabling" ),
		"choose_from_most_used" => __( "Choose from the most used Locations", "cabling" ),
		"not_found" => __( "No Locations found", "cabling" ),
		"no_terms" => __( "No Locations", "cabling" ),
		"items_list_navigation" => __( "Locations list navigation", "cabling" ),
		"items_list" => __( "Locations list", "cabling" ),
	];

	$training_args = [
		"label" => __( "Locations", "cabling" ),
		"labels" => $training_labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'training-location', 'with_front' => true,  /*'hierarchical' => true,*/ ],
		"show_admin_column" => true,
		"show_in_rest" => true,
		"rest_base" => "training_cat",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"show_in_quick_edit" => true,
		];
	register_taxonomy( "training_cat", [ "training" ], $training_args );
}
add_action( 'init', 'cabling_register_my_taxes' );
