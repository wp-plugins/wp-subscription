<?php
 function wp_subscribe_aweber_new_signup_user($full_target_list_name,$fname,$lname,$email_to_subscribe){
	
	$target_list_id=$full_target_list_name;
	$wp_aff_aweber_access_keys = get_option('AWeberWebformPluginAdminOptions');
	

	if (!class_exists('AWeberAPI')){//TODO - change the class name to "WP_AFF_AWeberAPI" to avoid conflict with others
		include_once(WP_PLUGIN_URL.'aweber-web-form-widget/php/aweber_api/aweber_api.php');
	
	}
	
	$aweber = new AWeberAPI($wp_aff_aweber_access_keys['consumer_key'], $wp_aff_aweber_access_keys['consumer_secret']);
	
	
	$account = $aweber->getAccount($wp_aff_aweber_access_keys['access_key'], $wp_aff_aweber_access_keys['access_secret']);//Get Aweber account

	$account_id = $account->id;
	$mylists = $account->lists;
	
	
	$target_list_name = str_replace("@aweber.com", "", $full_target_list_name);
	

	$list_name_found = false;
	foreach ($mylists as $list) {
		if($list->id == $target_list_id){
			$list_name_found = true;
			try {//Create a subscriber			    
			    $params = array(
			        'email' => $email_to_subscribe,
			        'name' => $fname.' '.$lname,
			    );
			    $subscribers = $list->subscribers;
			    $new_subscriber = $subscribers->create($params);
			   		}catch (Exception $exc) {
				
				
			}    
		}
	}
	return;
	
}
>?