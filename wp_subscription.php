<?php
/* Plugin Name: WP Subscription
Plugin URI: http://www.wpseeds.com/product/wp_subscription
Description: WP Subscribe is a simple but powerful subscription plugin which supports MailChimp, Aweber and Campaign Monitor.
Author: Prashant Walke
Author URI: http://www.wpseeds.com/wp-subscription/
Version: 1.0
Text Domain: wp-subscription
Domain Path: /lang
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

 if ( ! class_exists( 'WPSubscription' ) ) :
final class WPSubscription {
	
	public $version = '1.0.0';
	public $wpbdp_prefix="wpbdp";
	
	protected static $_instance = null;

	public $query = null;

		public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0' );
	}

	public function __construct() {	
		
		// Define constants
		$this->define_constants();
                register_activation_hook( __FILE__, array($this,'installation') );
                register_activation_hook( __FILE__, array($this,'my_plugin_install_function'));
		add_action( 'plugins_loaded', array($this,'load_textdomain') );	
		$this->installation();
		// Include required files		
		$this->includes();


		
	}
	function my_plugin_install_function()
            {
             
            }
            
	private function define_constants() {
		define( 'WPSP_PLUGIN_FILE', __FILE__ );
                define( 'WPSP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );               
	        define( 'WPSP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );    	
		define( 'WPSP_VERSION', $this->version );
		define( 'WPSP_PREFIX', $this->wpbdp_prefix );
		}
		
	 function includes() {
	             include_once( 'includes/admin/class-admin-assets.php' );
		         include_once( 'includes/admin/class-admin-settings.php' );
                     include_once( 'lib/mailchimp-sync/mailchimp-sync.php' );
                     include_once( 'lib/campaign-monitor-dual-registration/campaign-monitor-dual-registration.php' );
                     include_once( 'lib/aweber-web-form-widget/aweber.php' );
                     include_once( 'includes/front_end/shortcode.php' );  	
		             include_once( 'includes/front_end/widget.php' ); 
				 

	}  

	function installation(){
		include('includes/admin/installation.php');
	}

	function load_textdomain(){
		load_plugin_textdomain( 'wp-subscription',plugin_dir_path( __FILE__ ).'/lang' , 'wp-subscription/lang' );
	}
}
endif;
function WPSubscriptionBD() {
	return WPSubscription::instance();
}
$GLOBALS['WPSubscriptionBDplugin'] = WPSubscriptionBD();?>