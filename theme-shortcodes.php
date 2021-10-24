<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wowshop_slides_shortcode($atts){
	extract( shortcode_atts( array(
		'count' => '-1',

	), $atts ) );

	$args = array(
		'posts_per_page' => $count,
		'post_type' => 'ws-slide',
		'orderby' => 'menu_order',
		'order' => 'ASC'
	);
	
	$q = new WP_Query($args);

	$slide_random_number = rand(686868, 786868);

	$ws_slides_markup = '

    <script>
        jQuery(window).load(function(){
            jQuery("#wowshop-slides-'.$slide_random_number.'").owlCarousel({
                items: 1,
                loop: true,
                dots: false,
                nav: true,
                navText: ["<i class=\'fa fa-angle-left\'>", "<i class=\'fa fa-angle-right\'>"],
                autoplay: false,
            });
        });
    </script>
    
    <div id="wowshop-slides-'.$slide_random_number.'" class="wowshop-slides">';
        while($q->have_posts()) : $q->the_post();
        $idd = get_the_ID();
        if(get_post_meta($idd, 'wowshop_slide_meta', true)) {
        	$slide_meta = get_post_meta($idd, 'wowshop_slide_meta', true);
        } else {
        	$slide_meta = array();
        }
        if(array_key_exists('buttons', $slide_meta)) {
        	$buttons = $slide_meta['buttons'];
        } else {
        	$buttons = '';
        }
        $post_content = get_the_content(); 

        $ws_slides_markup .= '
        <div class="wowshop-single-slide">
        	<div class="container">
        		<div class="row">
        			<div class="col-lg-8">
	        			<div class="wowshop-slide-text-wrapper">
		        			<div class="wowshop-slide-text">
		        				<h2>'.get_the_title().'</h2>
		        				'.wpautop($post_content).' ';

		        				if(!empty($buttons)) {
		        					$ws_slides_markup .= '<div class="slide-buttons">';
		        					foreach($buttons as $button) {
		        						if($button['link_type'] == '1') {
		        							$link = get_page_link($button['link_to_page']);
		        						} else {
		        							$link = $button['link_to_custom'];
		        						}
		        						$ws_slides_markup .='<a href="'.$link.'" class="slide-btn slide-btn-'.$button['style'].'">'.$button['text'].'</a>';
		        					}
		        					$ws_slides_markup .= '</div>';
		        				}


		        			$ws_slides_markup .='
		        			</div>
	        			</div>
        			</div>
        			<div class="col-lg-4">
        			'.get_the_post_thumbnail($idd, 'large', array('class' => 'slide-absolute-img')).'
        			</div>
        		</div>
        	</div>
        </div>
        ';
        endwhile;
    
    $ws_slides_markup .= '</div>';
    
    wp_reset_Query();
          
    return $ws_slides_markup;
}
add_shortcode('ws_slides', 'wowshop_slides_shortcode');



// Section Title short Code

function wowshop_section_title_shortcode($atts, $content=null) {

	extract( shortcode_atts( array(
		'title' => '',
		'sub_title' => '',
	), $atts ) );



	$html = '<div class="section-title">
				<div class="row">
					<div class="col text-center">
						<h4>'.$title.'</h4>
						<p>'.$sub_title.'</p>
					</div>
				</div>
			</div>';

	
	return $html;
}

add_shortcode('ws_title', 'wowshop_section_title_shortcode');







// Product Shop Now CTA short Code

function wowshop_product_shop_now_cta_shortcode($atts, $content=null) {

	extract( shortcode_atts( array(
		'cta_img' => '',
		'cta_title' => '',
		'btn_text' => '',
		'btn_link' => '',
	), $atts ) );


	$args = array(
		'posts_per_page' => 1,
		'post_type' => 'product',
	);
	
	$q = new WP_Query($args);

	$img_array = wp_get_attachment_image_src( $cta_img, 'medium' );

	global $product;

	$html = '<div class="product-shop-now-cta">

				<img src="'.$img_array[0].'" alt="">

				<div class="shop-now-text">
					'.wpautop( $cta_title ).'

					<a href="'.get_permalink( $btn_link ).'" class="shop-now-btn boxed-btn"> '.$btn_text.' </a>

				</div>
			</div>';

	
	return $html;
}

add_shortcode('ws_product_cta', 'wowshop_product_shop_now_cta_shortcode');










// product category Shortcode 

function wowshop_product_category_shortcode($atts, $content=null) {

	extract( shortcode_atts( array(
		'cat_ids' => '',
		'columns' => 4,
	), $atts ) );

	if($columns == 3 ) {
		$column_class = 'col-lg-4';
	} elseif ($columns == 2) {
		$column_class = 'col-lg-6';
	} elseif ($columns == 1) {
		$column_class = 'col';
	} else {
		$column_class = 'col-lg-3';
	}

	$cat_array = explode( ',', $cat_ids);

	if(!empty($cat_array)) {

		$html = '<div class="row">';

		foreach($cat_array as $cat_id) {

			$cat_info = get_term($cat_id, 'product_cat');

			$thumbnail_id = get_woocommerce_term_meta($cat_id, "thumbnail_id", true);
			$image = wp_get_attachment_url( $thumbnail_id );
			

			$html .= '
				<div class="'.esc_attr($column_class).'">
					<a href="'.esc_url(get_category_link($cat_id, 'product_cat')).'" class="single-ws-cat-item text-center" style="background-image: url('.$image.')">
						<div class="cat-title-wrapper">
							<h2>'.esc_html($cat_info->name).'</h2>
						</div>
					</a>
				</div>
			';
		}
		$html.= '</div>';
	}
	
	
	return $html;
}

add_shortcode('ws_pct', 'wowshop_product_category_shortcode');







// Promo Shortcode 

function wowshop_promo_shortcode($atts, $content=null) {

	extract( shortcode_atts( array(
		'img' => '',
		'btn_text' => 'Shop Now',
		'icon' => 1,
		'icon_text' => 'fa fa-long-arrow-right',
		'link' => '',
	), $atts ) );

	$img_array = wp_get_attachment_image_src( $img, 'medium' );
	$link_markup = get_page_link();

	$wowshop_promo_markup = '<div class="wowshop-promo-box">';
	$wowshop_promo_markup .= '
		<div class="promo-icon-wrap">
			<div class="promo-icon-inner">
				<img src="'.$img_array[0].'" alt="">
			</div>
		</div>
		
		<div class="wowshop-promo-text">
			'.wpautop(do_shortcode($content)).'';

			if(!empty($link)) {
			$wowshop_promo_markup .= '
			<a href="'.$link_markup.'" class="promo-more-btn">
				'.$btn_text.'';

				if($icon == 1 && !empty($icon_text)) {
				$wowshop_promo_markup .= '
				<i class="'.$icon_text.'"></i>';
				}

			$wowshop_promo_markup .= '	
			</a>';
			}

			$wowshop_promo_markup .= '
		</div>
	';
	$wowshop_promo_markup .= '</div>';

	return $wowshop_promo_markup;


}

add_shortcode('ws_promo', 'wowshop_promo_shortcode');






// Blog Posts Shortcode 

function wowshop_blog_posts_shortcode($atts, $content=null) {

	extract( shortcode_atts( array(
		'count' => '3',
		'columns' => '3',
	), $atts ) );

	$args = array(
		'posts_per_page' => $count,
		'post_type' => 'post',
	);
	
	$q = new WP_Query($args);

	if ($columns == 1){
		$column_width = 'col';
		$thumb_size = 'large';
	} elseif ($columns == 2) {
		$column_width = 'col-lg-6';
		$thumb_size = 'large';
	} elseif ($columns == 4) {
		$column_width = 'col-lg-3';
	} else {
		$column_width = 'col-lg-4';
		$thumb_size = 'medium';
	}

	$html = '<div class="row">';
	
	while($q->have_posts()) : $q->the_post();
	$idd = get_the_ID();

	$html .= '
	<div class="'.$column_width.'">
		<div class="single-blog-box">
			<div class="blog-thumbnail" style="background-image: url('.get_the_post_thumbnail_url( $idd, $thumb_size ).')"></div>
			<div class="blog-inner-box">
				<div class="blog-box-meta">
					<span class="blog-box-date">
						<i class="fa fa-calendar"></i>
						'.get_the_time( 'j M, Y' ).'
					</span>
					<span class="blog-box-like">
						<i class="fa fa-heart-o"></i>
						'.get_favorites_count( $idd ).' 
					</span>
					<span class="blog-box-comments">
						<i class="fa fa-comments"></i>
						'.get_comments_number( $idd ).' Comments
					</span>
				</div>
				<h3>'.get_the_title().'</h3>
				'.wpautop( get_the_excerpt('') ).'
				<a href="'.get_permalink().'">Read More <i class="fa fa-long-arrow-right"></i></a>
			</div>
		</div>
	</div>';

	endwhile;

	$html .= '</div>';

	return $html;


}

add_shortcode('ws_posts', 'wowshop_blog_posts_shortcode');





// Product Show in TAB Shortcode 

function wowshop_product_tab_shortcode($atts, $content=null) {

	extract( shortcode_atts( array(
		'columns' => '4',
		'count' => '10',
		'title_1' => '',
		'title_2' => '',
		'title_3' => '',
	), $atts ) );



	
	$q = new WP_Query( array(
		'posts_per_page' => $count,
		'post_type' => 'product',
		'orderby' => 'name',
		'order' => 'ASC'
	) );
	
	$q_2 = new WP_Query( array(
		'posts_per_page' => $count,
		'post_type' => 'product',
		'orderby' => 'menu_order',
		'order' => 'ASC'
	) );
	
	$q_3 = new WP_Query( array(
		'posts_per_page' => $count,
		'post_type' => 'product',
		'orderby' => 'date',
		'order' => 'DSC'
	) );






    $slide_random_number = rand(686868, 786868);
    $slide_random_number_2 = rand(234545, 234567);
    $slide_random_number_3 = rand(345465, 347688);

	$html = '
    <script>
        jQuery(window).load(function(){
            jQuery("#wowshop-product-carousel-'.$slide_random_number.'").owlCarousel({
                items: '.$columns.',
                margin: 30,
                loop: true,
                dots: true,
                nav: true,
                navText: ["<i class=\'fa fa-angle-left\'>", "<i class=\'fa fa-angle-right\'>"],
                autoplay: false,
            });

            
        });
    </script> ';





	

	$html .= '<div class="wowshop-product-tab">
	            <div class="row">
	                <div class="col my-auto text-left">
	                    <div class="step-indicator text-center">
			                <ul class="nav nav-tabs" id="myTab" role="tablist">
			                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#step-checkout-1" role="tab" aria-controls="step-checkout-1" aria-selected="true">'.$title_1.'</a></li>
			                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#step-checkout-2" role="tab" aria-controls="step-checkout-2" aria-selected="true">'.$title_2.'</a></li>
			                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#step-checkout-3" role="tab" aria-controls="step-checkout-3" aria-selected="true">'.$title_3.'</a></li>
			                </ul>
						</div>
						
						<div class="tab-content" id="myTabContent">





			                <div class="tab-pane fade show active" id="step-checkout-1" role="tabpanel">
			              

				                <div id="wowshop-product-carousel-'.$slide_random_number.'" class="wowshop-product-carousel">';
							        while($q->have_posts()) : $q->the_post();
							        $idd = get_the_ID();
							        global $woocommerce;
				    				$currency = get_woocommerce_currency_symbol();
								    $price = get_post_meta( $idd, '_regular_price', true );
								    $sale = get_post_meta( $idd, '_sale_price', true );


							        if($sale) {
								    	$price_markup = '<del>'.$price.'</del>'.$sale.'';
								    } else {
								    	$price_markup = ''.$price.'';
								    }


							        if($sale) {
							        	$price_markup = '<del>'.$price.'</del>'.$sale.'';
							        } else {
							        	$price_markup = ''.$price.'';
							        }

							        $html .= '
							        <div class="wowshop-single-product text-center">  
							        	<div class="product-bg" style="background-image:url('.get_the_post_thumbnail_url( $idd, 'medium' ).')">
								    		<div class="product-view-hover">
									    		<div class="product-view-buttons ">
									    			<a href="#" class="button yith-wcqv-button" data-product_id="'.$idd.'"><i class="fa fa-search"></i></a>

									        		<div class="yith-wcwl-add-to-wishlist add-to-wishlist-'.$idd.'  wishlist-fragment on-first-load" data-fragment-ref="'.$idd.'" data-fragment-options="{&quot;base_url&quot;:&quot;&quot;,&quot;in_default_wishlist&quot;:false,&quot;is_single&quot;:true,&quot;show_exists&quot;:false,&quot;product_id&quot;:'.$idd.',&quot;parent_product_id&quot;:'.$idd.',&quot;product_type&quot;:&quot;simple&quot;,&quot;show_view&quot;:true,&quot;browse_wishlist_text&quot;:&quot;  &quot;,&quot;already_in_wishslist_text&quot;:&quot; &quot;,&quot;product_added_text&quot;:&quot;&quot;,&quot;heading_icon&quot;:&quot;fa-heart-o&quot;,&quot;available_multi_wishlist&quot;:false,&quot;disable_wishlist&quot;:false,&quot;show_count&quot;:false,&quot;ajax_loading&quot;:false,&quot;loop_position&quot;:&quot;after_add_to_cart&quot;,&quot;item&quot;:&quot;add_to_wishlist&quot;}">

														<!-- ADD TO WISHLIST -->
											
														<div class="yith-wcwl-add-button">
															<a href="?add_to_wishlist='.$idd.'" rel="nofollow" data-product-id="'.$idd.'" data-product-type="simple" data-original-product-id="'.$idd.'" class="add_to_wishlist single_add_to_wishlist" data-title="Add to wishlist">
																<i class="yith-wcwl-icon fa fa-heart-o"></i>
															</a>
														</div>
															<!-- COUNT TEXT -->
													</div>
									    		</div>
								    		</div>
							        	</div>


							        	<div class="product-details">  
							        		<h4>'.get_the_title().'</h4>
							        		<div class="product-price-wrapper">
							        			'.$currency.''.$price.'
							        		</div>

							        		<a href="/?add-to-cart='.$idd.'" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="'.$idd.'" data-product_sku="" aria-label="Add “'.get_the_title().'” to your cart" rel="nofollow">Add To Cart <i class="fa fa-long-arrow-right"></i></a>

							        	</div>


							        </div>
							        ';
							        endwhile;

							        wp_reset_Query();
							    
							    $html .= '</div>';
						    

			                $html .='</div>



			                <div class="tab-pane fade " id="step-checkout-2" role="tabpanel">
			                    

								<div id="wowshop-product-carousel-'.$slide_random_number.'" class="wowshop-product-carousel">';
							        while($q_2->have_posts()) : $q_2->the_post();
							        $idd = get_the_ID();
							        global $woocommerce;
				    				$currency = get_woocommerce_currency_symbol();
								    $price = get_post_meta( $idd, '_regular_price', true );
								    $sale = get_post_meta( $idd, '_sale_price', true );


							        if($sale) {
								    	$price_markup = '<del>'.$price.'</del>'.$sale.'';
								    } else {
								    	$price_markup = ''.$price.'';
								    }


							        if($sale) {
							        	$price_markup = '<del>'.$price.'</del>'.$sale.'';
							        } else {
							        	$price_markup = ''.$price.'';
							        }

							        $html .= '
							        <div class="wowshop-single-product text-center">  
							        	<div class="product-bg" style="background-image:url('.get_the_post_thumbnail_url( $idd, 'medium' ).')">
								    		<div class="product-view-hover">
									    		<div class="product-view-buttons ">
									    			<a href="#" class="button yith-wcqv-button" data-product_id="'.$idd.'"><i class="fa fa-search"></i></a>

									        		<div class="yith-wcwl-add-to-wishlist add-to-wishlist-'.$idd.'  wishlist-fragment on-first-load" data-fragment-ref="'.$idd.'" data-fragment-options="{&quot;base_url&quot;:&quot;&quot;,&quot;in_default_wishlist&quot;:false,&quot;is_single&quot;:true,&quot;show_exists&quot;:false,&quot;product_id&quot;:'.$idd.',&quot;parent_product_id&quot;:'.$idd.',&quot;product_type&quot;:&quot;simple&quot;,&quot;show_view&quot;:true,&quot;browse_wishlist_text&quot;:&quot;  &quot;,&quot;already_in_wishslist_text&quot;:&quot; &quot;,&quot;product_added_text&quot;:&quot;&quot;,&quot;heading_icon&quot;:&quot;fa-heart-o&quot;,&quot;available_multi_wishlist&quot;:false,&quot;disable_wishlist&quot;:false,&quot;show_count&quot;:false,&quot;ajax_loading&quot;:false,&quot;loop_position&quot;:&quot;after_add_to_cart&quot;,&quot;item&quot;:&quot;add_to_wishlist&quot;}">

														<!-- ADD TO WISHLIST -->
											
														<div class="yith-wcwl-add-button">
															<a href="?add_to_wishlist='.$idd.'" rel="nofollow" data-product-id="'.$idd.'" data-product-type="simple" data-original-product-id="'.$idd.'" class="add_to_wishlist single_add_to_wishlist" data-title="Add to wishlist">
																<i class="yith-wcwl-icon fa fa-heart-o"></i>
															</a>
														</div>
															<!-- COUNT TEXT -->
													</div>
									    		</div>
								    		</div>
							        	</div>


							        	<div class="product-details">  
							        		<h4>'.get_the_title().'</h4>
							        		<div class="product-price-wrapper">
							        			'.$currency.''.$price.'
							        		</div>

							        		<a href="/?add-to-cart='.$idd.'" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="'.$idd.'" data-product_sku="" aria-label="Add “'.get_the_title().'” to your cart" rel="nofollow">Add To Cart <i class="fa fa-long-arrow-right"></i></a>

							        	</div>


							        </div>
							        ';
							        endwhile;

							        wp_reset_Query();
							    
							    $html .= '</div>';
						    

			                $html .='</div>






			                <div class="tab-pane fade " id="step-checkout-3" role="tabpanel">


			                    <div id="wowshop-product-carousel-'.$slide_random_number.'" class="wowshop-product-carousel">';
							        while($q_3->have_posts()) : $q_3->the_post();
							        $idd = get_the_ID();
							        global $woocommerce;
				    				$currency = get_woocommerce_currency_symbol();
								    $price = get_post_meta( $idd, '_regular_price', true );
								    $sale = get_post_meta( $idd, '_sale_price', true );


							        if($sale) {
								    	$price_markup = '<del>'.$price.'</del>'.$sale.'';
								    } else {
								    	$price_markup = ''.$price.'';
								    }


							        if($sale) {
							        	$price_markup = '<del>'.$price.'</del>'.$sale.'';
							        } else {
							        	$price_markup = ''.$price.'';
							        }

							        $html .= '
							        <div class="wowshop-single-product text-center">  
							        	<div class="product-bg" style="background-image:url('.get_the_post_thumbnail_url( $idd, 'medium' ).')">
								    		<div class="product-view-hover">
									    		<div class="product-view-buttons ">
									    			<a href="#" class="button yith-wcqv-button" data-product_id="'.$idd.'"><i class="fa fa-search"></i></a>

									        		<div class="yith-wcwl-add-to-wishlist add-to-wishlist-'.$idd.'  wishlist-fragment on-first-load" data-fragment-ref="'.$idd.'" data-fragment-options="{&quot;base_url&quot;:&quot;&quot;,&quot;in_default_wishlist&quot;:false,&quot;is_single&quot;:true,&quot;show_exists&quot;:false,&quot;product_id&quot;:'.$idd.',&quot;parent_product_id&quot;:'.$idd.',&quot;product_type&quot;:&quot;simple&quot;,&quot;show_view&quot;:true,&quot;browse_wishlist_text&quot;:&quot;  &quot;,&quot;already_in_wishslist_text&quot;:&quot; &quot;,&quot;product_added_text&quot;:&quot;&quot;,&quot;heading_icon&quot;:&quot;fa-heart-o&quot;,&quot;available_multi_wishlist&quot;:false,&quot;disable_wishlist&quot;:false,&quot;show_count&quot;:false,&quot;ajax_loading&quot;:false,&quot;loop_position&quot;:&quot;after_add_to_cart&quot;,&quot;item&quot;:&quot;add_to_wishlist&quot;}">

														<!-- ADD TO WISHLIST -->
											
														<div class="yith-wcwl-add-button">
															<a href="?add_to_wishlist='.$idd.'" rel="nofollow" data-product-id="'.$idd.'" data-product-type="simple" data-original-product-id="'.$idd.'" class="add_to_wishlist single_add_to_wishlist" data-title="Add to wishlist">
																<i class="yith-wcwl-icon fa fa-heart-o"></i>
															</a>
														</div>
															<!-- COUNT TEXT -->
													</div>
									    		</div>
								    		</div>
							        	</div>


							        	<div class="product-details">  
							        		<h4>'.get_the_title().'</h4>
							        		<div class="product-price-wrapper">
							        			'.$currency.''.$price.'
							        		</div>

							        		<a href="/?add-to-cart='.$idd.'" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="'.$idd.'" data-product_sku="" aria-label="Add “'.get_the_title().'” to your cart" rel="nofollow">Add To Cart <i class="fa fa-long-arrow-right"></i></a>

							        	</div>


							        </div>
							        ';
							        endwhile;

							        wp_reset_Query();
							    
							    $html .= '</div>';


							$html .='</div>




			            </div>
	                </div>
            	</div>
			</div>';

	return $html;


}

add_shortcode('wowshop_product_tab', 'wowshop_product_tab_shortcode');



// product price shortcode
// eta stact over flow theke copy korechilam but koj hoy nai.
// pore eta niye research kormu

// function woocommerce_price_shortcode( $atts ){
// 	$atts = shortcode_atts( array(
// 		'id' => null
// 	), $atts, 'bartag' );

// 	$html = '';

// 	if( intval( $atts['id'] ) > 0 && function_exists( 'wc_get_product' ) ) {
// 		$_product = wc_get_product( $atts['id'] );
// 		$html = "price = " . $_product->get_price();
// 	}
// 	return $html;
// }
// add_shortcode('woocommerce_price', 'woocommerce_price_shortcode');




// Popular product shortcode
function wowshop_product_carousel_shortcode($atts){
	extract( shortcode_atts( array(
		'count' => '10',
		'columns' => '4',

	), $atts ) );

	$args = array(
		'posts_per_page' => $count,
		'post_type' => 'product',
		'orderby' => 'menu_order',
		'order' => 'ASC'
	);
	
	$q = new WP_Query($args);

	$slide_random_number = rand(686868, 786868);

	$html = '

    <script>
        jQuery(window).load(function(){
            jQuery("#wowshop-product-carousel-'.$slide_random_number.'").owlCarousel({
                items: '.$columns.',
                margin: 30,
                loop: true,
                dots: true,
                nav: true,
                navText: ["<i class=\'fa fa-angle-left\'>", "<i class=\'fa fa-angle-right\'>"],
                autoplay: false,
            });
        });
    </script>
    
    <div id="wowshop-product-carousel-'.$slide_random_number.'" class="wowshop-product-carousel">';
        while($q->have_posts()) : $q->the_post();
        $idd = get_the_ID();
        global $woocommerce;
        $currency = get_woocommerce_currency_symbol();
        $price = get_post_meta( $idd, '_regular_price', true );
        $sale = get_post_meta( $idd, '_sale_price', true );

        if($sale) {
        	$price_markup = '<del>'.$price.'</del>'.$sale.'';
        } else {
        	$price_markup = ''.$price.'';
        }

        if(get_post_meta($idd, 'wowshop_slide_meta', true)) {
        	$slide_meta = get_post_meta($idd, 'wowshop_slide_meta', true);
        } else {
        	$slide_meta = array();
        }
        if(array_key_exists('buttons', $slide_meta)) {
        	$buttons = $slide_meta['buttons'];
        } else {
        	$buttons = '';
        }
        $post_content = get_the_content(); 

        $html .= '
        <div class="wowshop-single-product text-center">  
        	<div class="product-bg" style="background-image:url('.get_the_post_thumbnail_url( $idd, 'medium' ).')">
	    		<div class="product-view-hover">
		    		<div class="product-view-buttons ">
		    			<a href="#" class="button yith-wcqv-button" data-product_id="'.$idd.'"><i class="fa fa-search"></i></a>

		        		<div class="yith-wcwl-add-to-wishlist add-to-wishlist-'.$idd.'  wishlist-fragment on-first-load" data-fragment-ref="'.$idd.'" data-fragment-options="{&quot;base_url&quot;:&quot;&quot;,&quot;in_default_wishlist&quot;:false,&quot;is_single&quot;:true,&quot;show_exists&quot;:false,&quot;product_id&quot;:'.$idd.',&quot;parent_product_id&quot;:'.$idd.',&quot;product_type&quot;:&quot;simple&quot;,&quot;show_view&quot;:true,&quot;browse_wishlist_text&quot;:&quot;  &quot;,&quot;already_in_wishslist_text&quot;:&quot; &quot;,&quot;product_added_text&quot;:&quot;&quot;,&quot;heading_icon&quot;:&quot;fa-heart-o&quot;,&quot;available_multi_wishlist&quot;:false,&quot;disable_wishlist&quot;:false,&quot;show_count&quot;:false,&quot;ajax_loading&quot;:false,&quot;loop_position&quot;:&quot;after_add_to_cart&quot;,&quot;item&quot;:&quot;add_to_wishlist&quot;}">

							<!-- ADD TO WISHLIST -->
				
							<div class="yith-wcwl-add-button">
								<a href="?add_to_wishlist='.$idd.'" rel="nofollow" data-product-id="'.$idd.'" data-product-type="simple" data-original-product-id="'.$idd.'" class="add_to_wishlist single_add_to_wishlist" data-title="Add to wishlist">
									<i class="yith-wcwl-icon fa fa-heart-o"></i>
								</a>
							</div>
								<!-- COUNT TEXT -->
						</div>
		    		</div>
	    		</div>
        	</div>


        	<div class="product-details">  
        		<h4>'.get_the_title().'</h4>
        		<div class="product-price-wrapper">
        			'.$currency.''.$price.'
        		</div>

        		<a href="/?add-to-cart='.$idd.'" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="'.$idd.'" data-product_sku="" aria-label="Add “'.get_the_title().'” to your cart" rel="nofollow">Add To Cart <i class="fa fa-long-arrow-right"></i></a>

        	</div>


        </div>
        ';
        endwhile;
    
    $html .= '</div>';
    
    wp_reset_Query();
          
    return $html;
}
add_shortcode('ws_product_carousel', 'wowshop_product_carousel_shortcode');
