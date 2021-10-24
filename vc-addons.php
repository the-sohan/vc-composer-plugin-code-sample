<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// show page in list. use for link (a tag)
function wowshop_toolkit_get_page_as_list() {

	$args = wp_parse_args( array(
		'post_type'	=> 'page',
		'numberposts' => -1,
	) );

	$posts = get_posts( $args );

	$post_options = array(esc_html__('-- Select page --', 'wowshop-toolkit')=>'');
	if ( $posts ) {
		foreach ( $posts as $post ) {
			$post_options[ $post->post_title ] = $post->ID;
		}
	}
	return $post_options;
}

function wowshop_toolkit_get_porduct_as_list() {

	$args = wp_parse_args( array(
		'post_type'	=> 'product',
		'numberposts' => -1,
	) );

	$posts = get_posts( $args );

	$post_options = array(esc_html__('-- Select Product --', 'wowshop-toolkit')=>'');
	if ( $posts ) {
		foreach ( $posts as $post ) {
			$post_options[ $post->post_title ] = $post->ID;
		}
	}
	return $post_options;
}





// SLide addon
vc_map( 
	array(
		"name" => __( "WowShop Slides", "wowshop-toolkit" ),
		"base" => "ws_slides",
		"category" => __( "WowShop", "wowshop-toolkit" ),
		"icon" => sohan_wowshop_ACC_URL.'/assets/img/icon.png',
		"params" => array(
			array(
				"type" => "textfield",
				"heading" => __( "Post Count", "wowshop-toolkit" ),
				"param_name" => "count",
				"value" => __( "-1", "wowshop-toolkit" ),
				"description" => esc_html__( "Type how item?", "wowshop-toolkit" )
			)
		)
	)
);





// section title addon
vc_map( 
	array(
		"name" => __( "WowShop Section Title", "wowshop-toolkit" ),
		"base" => "ws_title",
		"category" => __( "WowShop", "wowshop-toolkit" ),
		"icon" => sohan_wowshop_ACC_URL.'/assets/img/icon.png',
		"params" => array(
			array(
				"type" => "textfield",
				"heading" => __( "Title", "wowshop-toolkit" ),
				"param_name" => "title",
				"description" => esc_html__( "Type Setion Title", "wowshop-toolkit" )
			),
			array(
				"type" => "textfield",
				"heading" => __( "Section Sub Title", "wowshop-toolkit" ),
				"param_name" => "sub_title",
				"description" => esc_html__( "Type Setion Sub Title", "wowshop-toolkit" )
			)
		)
	)
);



// Product show in Tab Addons
vc_map( 
	array(
		"name" => __( "WowShop Product Tabs", "wowshop-toolkit" ),
		"base" => "wowshop_product_tab",
		"category" => __( "WowShop", "wowshop-toolkit" ),
		"icon" => sohan_wowshop_ACC_URL.'/assets/img/icon.png',
		"params" => array(
			array(
				"type" => "textfield",
				"heading" => __( "How many product want to show?", "wowshop-toolkit" ),
				"param_name" => "count",
			),
			array(
				"type" => "textfield",
				"heading" => __( "Tab 1 Title", "wowshop-toolkit" ),
				"param_name" => "title_1",
			),
			array(
				"type" => "textfield",
				"heading" => __( "Tab 2 Title", "wowshop-toolkit" ),
				"param_name" => "title_2",
			),
			array(
				"type" => "textfield",
				"heading" => __( "Tab 3 Title", "wowshop-toolkit" ),
				"param_name" => "title_3",
			),
		)
	)
);



// Wow Shop Blog Posts addon
vc_map( 
	array(
		"name" => __( "WowShop Blog Posts", "wowshop-toolkit" ),
		"base" => "ws_posts",
		"category" => __( "WowShop", "wowshop-toolkit" ),
		"icon" => sohan_wowshop_ACC_URL.'/assets/img/icon.png',
		"params" => array(
			array(
				"type" => "textfield",
				"heading" => __( "How many product want to show?", "wowshop-toolkit" ),
				"param_name" => "count",
			),
			array(
				"type" => "dropdown",
				"heading" => __( "Select Columns", "wowshop-toolkit" ),
				"param_name" => "columns",
				"value" => array(
					esc_html__('One Column', 'wowshop-toolkit') => 1,
					esc_html__('Two Columns', 'wowshop-toolkit') => 2,
					esc_html__('Three Columns', 'wowshop-toolkit') => 3,
					esc_html__('Four Columns', 'wowshop-toolkit') => 4,
				),
			),
		)
	)
);






// Product Shop Now CTA addon
vc_map( 
	array(
		"name" => __( "WowShop Shop Now CTA", "wowshop-toolkit" ),
		"base" => "ws_product_cta",
		"category" => __( "WowShop", "wowshop-toolkit" ),
		"icon" => sohan_wowshop_ACC_URL.'/assets/img/icon.png',
		"params" => array(
			array(
				"type" => "attach_image",
				"heading" => __( "Upload Image", "wowshop-toolkit" ),
				"param_name" => "cta_img",
			),
			array(
				"type" => "textarea",
				"heading" => __( "CTA Title", "wowshop-toolkit" ),
				"param_name" => "cta_title",
			),
			array(
				"type" => "textfield",
				"heading" => __( "Button Text", "wowshop-toolkit" ),
				"param_name" => "btn_text",
			),
			array(
				"type" => "dropdown",
				"heading" => esc_html__( "Select Product", "wowshop-toolkit" ),
				"param_name" => "btn_link",
				"value" => wowshop_toolkit_get_porduct_as_list(),
			)
		)
	)
);




// product category addon
// product category gula show korar jonn ei  function

function wowshop_get_product_cat_as_list() {

	$product_categories = get_terms( array(
		'taxonomy' => 'product_cat',
		'hide_empty' => false,
	) );

	$product_category_options = array(esc_html__( '-- Select categories --', 'wowshop-toolkit') => '');

	if ( $product_categories ) {
		foreach ( $product_categories as $product_category ) {
			$product_category_options[ $product_category->name ] = $product_category->term_id;
		}
	} 

	return $product_category_options;
}


// function industry_theme_slide_cat_list() {

//     $slide_categories = get_terms( 'product_cat' );

//     $slide_category_options = array('' => esc_html__('All category', 'wowshop-toolkit'));

//     if( $slide_categories ) {
//         foreach ( $slide_categories as $slide_category ) {
//             $slide_category_options[ $slide_category->name ] = $slide_category->term_id;
//         }
//     }

//     return $slide_category_options;

// }




// vc_map( 
// 	array(
// 		"name" => __( "wowshop product caregory thumbnail", "wowshop-toolkit" ),
// 		"base" => "ws_pct",
// 		"category" => __( "WowShop", "wowshop-toolkit" ),
// 		"icon" => sohan_wowshop_ACC_URL.'/assets/img/icon.png',
// 		"params" => array(
// 			array(
// 				"type" => "checkbox",
// 				"heading" => __( "Categories", "wowshop-toolkit" ),
// 				"param_name" => "cat_ids",
// 				"value" => wowshop_get_product_cat_as_list(),
// 				"description" => esc_html__( "Select Categories?", "wowshop-toolkit" ),
// 			)
// 		)
// 	)
// );

function product_category_addon() {
	vc_map( array(
		"name" => __( "wowshop product cartegory thumbnail", "wowshop-toolkit" ),
		"base" => "ws_pct",
		"category" => __( "WowShop", "wowshop-toolkit" ),
		"params" => array(
			array(
				"type" => "checkbox",
				"heading" => __( "Categories", "wowshop-toolkit" ),
				"param_name" => "cat_ids",
				"value" => wowshop_get_product_cat_as_list(),
			),
			array(
				"type" => "dropdown",
				"heading" => __( "Columns", "wowshop-toolkit" ),
				"std" => 4,
				"param_name" => "columns",
				"value" => array(
					esc_html__('One Column', 'wowshop-toolkit') => 1,
					esc_html__('Two Columns', 'wowshop-toolkit') => 2,
					esc_html__('Three Columns', 'wowshop-toolkit') => 3,
					esc_html__('Four Columns', 'wowshop-toolkit') => 4,
				),
			),
		)
	) );
}
add_action('vc_before_init', 'product_category_addon');






// Promo addon
vc_map( 
	array(
		"name" => esc_html__( "wowshop Promo", "wowshop-toolkit" ),
		"base" => "ws_promo",
		"category" => esc_html__( "WowShop", "wowshop-toolkit" ),
		"icon" => sohan_wowshop_ACC_URL.'/assets/img/icon.png',
		"params" => array(
			array(
				"type" => "attach_image",
				"heading" => esc_html__( "Image", "wowshop-toolkit" ),
				"param_name" => "img",
			),
			array(
				"type" => "textarea_html",
				"heading" => esc_html__( "Content", "wowshop-toolkit" ),
				"param_name" => "content",
			),
			array(
				"type" => "textfield",
				"heading" => esc_html__( "Button Text", "wowshop-toolkit" ),
				"std" => esc_html__( "Shop Now", "wowshop-toolkit" ),
				"param_name" => "btn_text",
			),
			array(
				"type" => "dropdown",
				"heading" => esc_html__( "Enable icon", "wowshop-toolkit" ),
				"std" => 1,
				"param_name" => "icon",
				"value"	=> array(
					esc_html__('Yes', 'wowshop-toolkit') => 1,
					esc_html__('No', 'wowshop-toolkit') => 2,
				)
			),
			array(
				"type" => "iconpicker",
				"heading" => esc_html__( "Select icon", "wowshop-toolkit" ),
				"std" => 'fa fa-long-arrow-right',
				"param_name" => "icon_text",
			),
			array(
				"type" => "dropdown",
				"heading" => esc_html__( "Select page", "wowshop-toolkit" ),
				"param_name" => "link",
				"value" => wowshop_toolkit_get_page_as_list(),
			)
		)
	)
);





// function slide_addon() {
// 	vc_map( array(
// 		"name" => __( "wowshop Slides", "wowshop-toolkit" ),
// 		"base" => "ws_slides",
// 		"category" => __( "WowShop", "wowshop-toolkit" ),
// 		"params" => array(
// 			array(
// 				"type" => "textfield",
// 				"heading" => __( "Post Count", "wowshop-toolkit" ),
// 				"param_name" => "count",
// 				"value" => __( "-1", "wowshop-toolkit" ),
// 				"description" => __( "Type how item?", "wowshop-toolkit" )
// 			)
// 		)
// 	) );
// }
// add_action('vc_before_init', 'slide_addon');




