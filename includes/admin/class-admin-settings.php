<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



class WPSubscriptionSetting {


       public function __construct() {
		
		add_action('init', array('WPSubscriptionSetting', 'init'));
		

		
	}
    static function version () {
        return VERSION;
    }

    static function init() {   
      
      	        add_action( 'admin_menu', array('WPSubscriptionSetting', 'adminPage') );
      
        
}
   
    static function adminPage() {
      
        add_menu_page('WP Subscription', 'WP Subscription', 'update_plugins', 'wpsp-dashboard', array('WPSubscriptionSetting','renderAdminPage'),WPSP_PLUGIN_URL.'/assets/images/wpsubscription.png' );
		
	add_submenu_page( 'wpsp-dashboard', 'Autoresponder', 'Autoresponder', 'manage_options', 'wpsp-autoresponder', array('WPSubscriptionSetting','autoresponderAdminPage'));
        add_submenu_page( 'wpsp-dashboard', 'Subscribers Categoty', 'Subscribers Categoty', 'manage_options', 'wpsp-subscriberscategoty', array('WPSubscriptionSetting','subscribersCategotyAdminPage'));
        add_submenu_page( 'wpsp-dashboard', 'Subscribers', 'Subscribers', 'manage_options', 'wpsp-subscribers', array('WPSubscriptionSetting','subscribersAdminPage'));
        add_submenu_page( 'wpsp-dashboard', 'Settings', 'Settings', 'manage_options', 'wpsp-setting', array('WPSubscriptionSetting','settingAdminPage'));

}


   


   static function renderAdminPage() {
      
       include( 'views/html-admin-views.php' );
     
    } 
    
    static function autoresponderAdminPage() {
      
       include( 'views/autoresponder-admin-views.php' );
     
    } 
    static function subscribersCategotyAdminPage() {
      
       include( 'views/subscribersCategoty-admin-views.php' );
     
    } 
     static function subscribersAdminPage() {
      
       include( 'views/subscribers-admin-views.php' );
     
    } 
    static function settingAdminPage() {
      
       include( 'views/setting-admin-views.php' );
     
    } 
	
	
} // end WPSubscriptionSetting



$WPSubscriptionSetting= new WPSubscriptionSetting();?>