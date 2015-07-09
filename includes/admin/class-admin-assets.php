<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WPSPAdmin_Assets' ) ) :

class WPSPAdmin_Assets {

	public function __construct() {
		add_action( 'init', array( $this, 'admin_scripts' ) );
		add_action( 'init', array( $this, 'load_scripts_frontend' ) );
                add_action('the_posts',  array($this,'check_for_shortcode'));
	}

	 // Enqueue scripts
	public function admin_scripts() {		 
         if (isset($_GET['page'])) { 
			
            if ($_GET['page'] == "wpsp-dashboard" || $_GET['page'] == "wpsp-autoresponder" || $_GET['page'] == "wpsp-subscribers" || $_GET['page'] == "wpsp-subscriberscategoty" || $_GET['page'] == "wpsp-setting") {  
				
		wp_enqueue_script('jquery');
				
               
				
                wp_enqueue_style('wpsbootstrapcss',WPSP_PLUGIN_URL."assets/css/bootstrap.min.css" );
                wp_enqueue_style('wpsbootstrapcss'); 
                

                wp_enqueue_script('wpsbootstrapjs',WPSP_PLUGIN_URL."assets/js/bootstrap.min.js" );
                wp_enqueue_script('wpsbootstrapjs');
            }
            if ($_GET['page'] =="wpsp-dashboard"){
                wp_enqueue_script('wpbussinessdir',WPSP_PLUGIN_URL."assets/js/wp_bussiness_dir.js" );
                wp_enqueue_script('wpbussinessdir');
                //<!-- Morris Charts CSS -->
               wp_enqueue_style('wpmorris',WPSP_PLUGIN_URL."assets/plugins/morris.css" );
               wp_enqueue_style('wpmorris'); 
               
               wp_enqueue_script('wpraphael',WPSP_PLUGIN_URL."assets/plugins/morris/raphael.min.js");
               wp_enqueue_script('wpraphael');
               
               wp_enqueue_script('morrismorris',WPSP_PLUGIN_URL."assets/plugins/morris/morris.min.js");
               wp_enqueue_script('morrismorris');
               
             
           }
            if ($_GET['page'] == "wpsp-subscribers" ){
                wp_enqueue_script('wpsubscription',WPSP_PLUGIN_URL."assets/js/wp_bussiness_dir.js" );
                wp_enqueue_script('wpsubscription');
                
                wp_enqueue_script('dataTables',WPSP_PLUGIN_URL."assets/js/dataTables/jquery.dataTables.js" );
                wp_enqueue_script('dataTables');   
                
                wp_enqueue_script('jqdataTables',WPSP_PLUGIN_URL."assets/js/dataTables/dataTables.bootstrap.js" );
                wp_enqueue_script('jqdataTables');
                
                wp_enqueue_script('wpscustom',WPSP_PLUGIN_URL."assets/js/custom.js" );
                wp_enqueue_script('wpscustom');  
               
            }
		   // <!-- DataTables JavaScript -->
         }
	}

public function load_scripts_frontend() {
   
}

function check_for_shortcode($posts) {
    
    if ( empty($posts) )
        return $posts;

    // false because we have to search through the posts first
    
      $found = false;
    foreach ($posts as $post) {
        // check the post content for the short code
        if ( stripos($post->post_content, 'wp_business_directory') 
                || stripos($post->post_content, 'wp_business_directory_login') 
                || stripos($post->post_content, 'wp_business_directory_categories')
                || stripos($post->post_content, 'wp_business_directory_search')
                || stripos($post->post_content, 'wp_business_directory_submit_listing'))
        {
                       $found = true;
         }
            // stop the search
            break;
        }

     if ($found){
         
			    wp_enqueue_script('jquery');


                wp_enqueue_script('wpsbootstrapjs',WPSP_PLUGIN_URL."assets/js/bootstrap.min.js" );
                wp_enqueue_script('wpsbootstrapjs');

               
    }
    return $posts;
}
// perform the check when the_posts() function is called
	

}

endif;

$obj= new WPSPAdmin_Assets();