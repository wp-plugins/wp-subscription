<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;

//Database
if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}wpsp_subscribers'") != $wpdb->prefix . 'wpsp_subscribers'){
	$wpdb->query("CREATE TABLE {$wpdb->prefix}wpsp_subscribers (
	id INT(11) NOT NULL AUTO_INCREMENT,
	frist_name VARCHAR(100)NULL,
        last_name VARCHAR(100)NULL,	
	created_date TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	created_by INT(11) NULL,
	modify_date DATE NULL, 	
	is_active BOOL NULL DEFAULT '1',	
	email VARCHAR(100) NOT NULL,
	PRIMARY KEY (id)
	);");
}

if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}wpsp_category'") != $wpdb->prefix . 'wpsp_category'){
	$wpdb->query("CREATE TABLE {$wpdb->prefix}wpsp_category (
	id INT(11) NOT NULL AUTO_INCREMENT,
	category_name VARCHAR(100) NOT NULL,	
	created_date TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
        modify_date DATE NULL,	
	PRIMARY KEY (id)
	);");
        
        $wpdb->query("INSERT INTO {$wpdb->prefix}wpsp_category (category_name) VALUES ('General')");
}

if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}wpsp_user_category'") != $wpdb->prefix . 'wpsp_user_category'){
	$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wpsp_user_category (
                id int(11) NOT NULL AUTO_INCREMENT,
                subscriber_id int(11) NOT NULL,
                category_id int(11) NOT NULL,
                PRIMARY KEY (id),
                KEY subscriber_id (subscriber_id),
                KEY category_id (category_id)
              ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
        
          $wpdb->query(" ALTER TABLE {$wpdb->prefix}wpsp_user_category
                        ADD CONSTRAINT {$wpdb->prefix}wpsp_user_category_ibfk_2 FOREIGN KEY (category_id) REFERENCES {$wpdb->prefix}wpsp_category (id) ON DELETE CASCADE ON UPDATE CASCADE,
                        ADD CONSTRAINT {$wpdb->prefix}wpsp_user_category_ibfk_1 FOREIGN KEY (subscriber_id) REFERENCES {$wpdb->prefix}wpsp_subscribers (id) ON DELETE CASCADE ON UPDATE CASCADE;
                      ");
       
}


 add_option('wps_first_name',1);
 add_option('wps_last_name',1);
 add_option('wps_form_title','SUBSCRIBE OUR NEWSLETTER');
 add_option('wps_submit_title','Subscribe');
 add_option('wps_sussesfull_message','Thanks for Subscription');
 add_option('wps_comment_textmessage','Subscribe with your name and email to get updates fresh updates.');
 
 