<?php

	if( ! defined('ABSPATH' ) ) die('-1');

 	// Class started
 	Class sohanVCExtendAddonClass{

 		function __construct(){
 			// we safely integrate with VC with this hook
 			add_action('init', array( $this, 'sohanIntegrateWithVC'));
 		}

 		public function sohanIntegrateWithVC() {
 			if( ! defined( 'WPB_VC_VERSION' ) ){
 				add_action('admin_notices', array( $this, 'sohanShowVcVersionNotice' ));
 				return;
 			}


 			// vissual composer addons
 			include  sohan_wowshop_ACC_PATH . '/vc-addons.php';		

 		}

 		// show visual composer version
 		public function sohanShowVcVersionNotice(){
 			$theme_data = wp_get_theme();
 			echo '
	 			<div class="notice notice-warning">
				    <p>'.sprintf(__('<strong>%s</strong> requires <strong><a href="'.site_url().'/wp-adimn/themes.php?page=tgmpa-install-plugins" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'wow-sohan'), $theme_data->get['Name']).'</p>
				</div>';
 		}
 	}

// Finally initialize code
new sohanVCExtendAddonClass();