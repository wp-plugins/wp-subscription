<?php

define( 'CMDR_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'CMDR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Global variables
global $cmdr_fields_to_hide;

register_deactivation_hook( __FILE__, 'cms_webhook_remove' );

add_action( 'admin_menu', 'cmdr_plugin_menu' );
add_action( 'init', 'cmdr_api_init' );
add_action( 'wp_ajax_cmdr-cm-sync', 'cmdr_cm_sync' );
add_action( 'wp_ajax_nopriv_cmdr-cm-sync', 'cmdr_cm_sync' );

add_filter( 'update_user_metadata', 'cmdr_user_meta_update', 1000, 5 );

require_once CMDR_PLUGIN_PATH . 'classes/CMDR_Dual_Synchronizer.php';

// Remove the webhook if needed
function cms_webhook_remove() {
	if ( ! class_exists( 'CS_REST_Lists' ) ) {
		require_once CMDR_PLUGIN_PATH . 'campaignmonitor-createsend-php/csrest_lists.php';
	}

	$auth = array( 'api_key' => get_option( 'cmdr_api_key' ) );
	$wrap_l = new CS_REST_Lists( get_option( 'cmdr_list_id' ), $auth );

	$c = false;
	$result = $wrap_l->get_webhooks();
	foreach( $result->response as $hook ) {
		if ( $hook->Url == admin_url( 'admin-ajax.php?action=cmdr-cm-sync' ) ) {
			$c = $hook->WebhookID;
			break;
		}
	}

	if ( $c ) {
		$result = $wrap_l->delete_webhook( $c );
	}
}

function cmdr_plugin_menu() {
	if ( basename( $_SERVER['SCRIPT_FILENAME'] ) == 'admin.php' && isset( $_GET['page'] ) && $_GET['page'] == 'wpsp-autoresponder' && isset( $_GET['action'] ) && $_GET['action']=='aweberprocess') {
		// Check permissions
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'wpsp-autoresponder' ) );
		}
		
		if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
			if ( isset( $_POST[ 'cmdr_user_fields' ] ) ) {
				update_option( 'cmdr_user_fields', base64_encode( serialize( ( array ) $_POST[ 'cmdr_user_fields' ] ) ) );
			} else {
				update_option( 'cmdr_user_fields', base64_encode( serialize( array() ) ) );
			}
			update_option( 'cmdr_api_key', $_POST[ 'cmdr_api_key' ] );
			update_option( 'cmdr_list_id', $_POST[ 'cmdr_list_id' ] );
			
			if ( ! class_exists( 'CS_REST_Lists' ) ) {
				require_once CMDR_PLUGIN_PATH . 'campaignmonitor-createsend-php/csrest_lists.php';
			}

			$auth = array( 'api_key' => get_option( 'cmdr_api_key' ) );
			$wrap_l = new CS_REST_Lists( get_option( 'cmdr_list_id' ), $auth );

			
				
				$c = false;
				$result = $wrap_l->get_webhooks();
				if ( ! $result->was_successful() ) {
				
				}
				foreach( $result->response as $hook ) {
					if ( $hook->Url == admin_url( 'admin-ajax.php?action=cmdr-cm-sync' ) ) {
						$c = $hook->WebhookID;
						break;
					}
				}

				if ( $c ) {
					$result = $wrap_l->delete_webhook( $c );
					if ( ! $result->was_successful() ) {
						
					}
				}
			if ( $result ) {
				wp_redirect( home_url( '/wp-admin/admin.php?page=wpsp-autoresponder&saved=true' ) );
			} else {
				
			}
		}
	}
	
//add_plugins_page( 'Campaign Monitor Dual Registration Options', 'CM Dual Registration', 'manage_options', 'wpsp-autoresponder', 'cmdr_plugin_page' );
}

function cmdr_plugin_page() {
	global $wpdb;
	global $cmdr_fields_to_hide;
	
	// Check permissions
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'wpsp-autoresponder' ) );
	}

	// Get user meta keys
	$querystr = "
		SELECT DISTINCT umeta.meta_key
		FROM $wpdb->users as u, $wpdb->usermeta as umeta
		WHERE u.id = umeta.user_id
		ORDER BY umeta.meta_key
	";
	$items = $wpdb->get_results( $querystr, OBJECT );
	
	$cmdr_user_fields = ( array ) unserialize( base64_decode( get_option( 'cmdr_user_fields' ) ) );
	
	if ( isset( $_REQUEST['saved'] ) )
		echo '<div id="message" class="updated fade"><p><strong> ' . __( 'Settings saved.', 'wpsp-autoresponder' ) . '</strong></p></div>';
	if ( isset( $_REQUEST['error'] ) )
		echo '<div id="message" class="updated fade"><p><strong> ' . __( 'Campaign Monitor synchronization error.', 'wpsp-autoresponder' ) . '<br />' . __( urldecode( $_REQUEST['error'] ), 'wpsp-autoresponder' ) . '</strong></p></div>';
	?>
	<div class="wrap">
		<div id="icon-themes" class="icon32">
			<br>
		</div>
		<form method="post" action="admin.php?page=wpsp-autoresponder&action=aweberprocess">
			<h2><?php _e( 'Campaign Monitor Integration', 'wpsp-autoresponder' ); ?></h2>
			<div class="inside">
				<table border="0">
					<tbody>
												<tr>
							<td colspan="2"><h3><?php _e( 'API', 'wpsp-autoresponder' );?></h3></td>
						</tr>
						<tr>
							<td style="vertical-align: top;"><label for="cmdr_api_key"><?php _e( 'API key', 'wpsp-autoresponder' ); ?>:</label></td>
							<td><input type="text" name="cmdr_api_key" id="cmdr_api_key" value="<?php echo esc_attr( get_option( 'cmdr_api_key' ) ); ?>" size="70" /></td>
						</tr>
						
						<tr>
							<td style="vertical-align: top;"><label for="cmdr_list_id"><?php _e( 'List ID', 'wpsp-autoresponder' ); ?>:</label></td>
							<td><input type="text" name="cmdr_list_id" id="cmdr_list_id" value="<?php echo esc_attr( get_option( 'cmdr_list_id' ) ); ?>" size="70" /></td>
						</tr>
						
						
						<tr>
						<tr>
							<td></td>
							<td><input class="button-primary" type="submit" value="Save Changes" /></td>
						</tr>
					</tbody>
				</table>
			</div>
		</form>
	</div>
	<?php
}

function cmdr_api_init() {
	global $cmdr_fields_to_hide;
	
	$cmdr_fields_to_hide = array(
		'admin_color',
		'closedpostboxes_nav-menus',
		'comment_shortcuts',
		'dismissed_wp_pointers',
		'managenav-menuscolumnshidden',
		'metaboxhidden_nav-menus',
		'nav_menu_recently_edited',
		'rich_editing',
		'show_admin_bar_front',
		'show_welcome_panel',
		'use_ssl',
		'wp_capabilities',
		'wp_dashboard_quick_press_last_post_id',
		'wp_user-settings',
		'wp_user-settings-time',
		'wp_user_level'
	);
	$cmdr_fields_to_hide = apply_filters( 'cmdr_edit_fileds_to_hide', $cmdr_fields_to_hide );
}

function cmdr_load_translation_file() {
	load_plugin_textdomain( 'wpsp-autoresponder', '', CMDR_PLUGIN_PATH . 'translations' );
}

function cmdr_user_update( $user_id, $old_user_data ) {
	$user = get_userdata( $user_id );
	if ( ! $user ) {
		return;
	}

	$args = array();
	if ( $user->user_email != $old_user_data->user_email ) {
		$args[ 'EmailAddress' ] = $user->user_email;
	}
	if ( $user->first_name != $user->first_name || $user->last_name != $user->last_name ) {
		$args[ 'Name' ] = $user->first_name . ' ' . $user->last_name;
	}

	if ( count( $args ) ) {
		// Make user sync
		CMDR_Dual_Synchronizer::cmdr_user_update( $user_id, $args, $old_user_data->user_email );
	}
}

function cmdr_user_insert( $user_id ) {
	// Make new user sync
	CMDR_Dual_Synchronizer::cmdr_user_update( $user_id, null, null, true );
}

function cmdr_cm_sync() {
	global $cmdr_fields_to_hide;

	if ( ! class_exists( 'CS_REST_SERIALISATION_get_available' ) ) {
		require_once CMDR_PLUGIN_PATH . 'campaignmonitor-createsend-php/class/serialisation.php';
	}
	if ( ! class_exists( 'CS_REST_Log' ) ) {
		require_once CMDR_PLUGIN_PATH . 'campaignmonitor-createsend-php/class/log.php';
	}

	// Get a serialiser for the webhook data - We assume here that we're dealing with json
	$serialiser = CS_REST_SERIALISATION_get_available( new CS_REST_Log( CS_REST_LOG_NONE ) );

	// Read all the posted data from the input stream
	$raw_post = file_get_contents("php://input");

	// And deserialise the data
	$deserialised_data = $serialiser->deserialise( $raw_post );

	// List ID check
	$list_id = $deserialised_data->ListID;
	if ( trim( $list_id ) == trim( get_option( 'cmdr_list_id' ) ) ) {
		$cmdr_user_fields = ( array ) unserialize( base64_decode( get_option( 'cmdr_user_fields' ) ) );
	
		remove_action( 'init', 'cmdr_api_init' );
		remove_action( 'profile_update', 'cmdr_user_update', 10 );
		remove_action( 'user_register', 'cmdr_user_insert' );
		remove_filter( 'update_user_metadata', 'cmdr_user_meta_update', 1000 );
		
		foreach( $deserialised_data->Events as $subscriber ) {
			if ( ! empty( $subscriber->OldEmailAddress ) ) {
				$user = get_user_by( 'email', $subscriber->OldEmailAddress );
			} else {
				$user = get_user_by( 'email', $subscriber->EmailAddress );
			}
			
			if ( $user ) {
				if ( $user->user_email != $subscriber->EmailAddress ) {
					wp_update_user( array ( 'ID' => $user->ID, 'user_email' => $subscriber->EmailAddress ) );
				}
				if ( $user->first_name . ' ' . $user->last_name != $subscriber->Name ) {
					$n = explode( ' ', $subscriber->Name );
					$fn = array_shift( $n );
					$ln = implode( ' ', $n );
					if ( $fn ) {
						update_user_meta( $user->ID, 'first_name', $fn );
					}
					if ( $ln ) {
						update_user_meta( $user->ID, 'last_name', $ln );
					}
					foreach( $subscriber->CustomFields as $key => $field ) {
						if ( in_array( $field->Key, $cmdr_user_fields ) && ! in_array( $field->Key, $cmdr_fields_to_hide ) ) {
							update_user_meta( $user->ID, $field->Key, $field->Value );
						}
					}
				}
			}
		}
	}
	
	echo 'ok';
	die();
}

function cmdr_user_meta_update( $temp, $user_id, $meta_key, $meta_value ) {
	global $cmdr_fields_to_hide;
	
	$cmdr_user_fields = ( array ) unserialize( base64_decode( get_option( 'cmdr_user_fields' ) ) );
	
	// The same value, no needs to update
	if ( $meta_value == get_user_meta( $user_id, $meta_key, true ) )
		return;
	
	// Field should not be updated
	if ( ! in_array( $meta_key, $cmdr_user_fields ) || in_array( $meta_key, $cmdr_fields_to_hide ) )
		return;

	$args = array();
	$args[ 'CustomFields' ][] = array( 'Key' => $meta_key, 'Value' => $meta_value );
	CMDR_Dual_Synchronizer::cmdr_user_update( $user_id, $args );
}
