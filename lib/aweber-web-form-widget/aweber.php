<?php

if (isset($_GET['action']) and $_GET['action'] == 'error_scrape') {
    die('Sorry, AWeber Web Forms requires PHP 5.2 or higher. Please deactivate AWeber Web Forms.');
}

// Initialize plugin.
if (!class_exists('AWeberWebformPlugin')) {
    require_once(dirname(__FILE__) . '/php/aweber_api/aweber_api.php');
    require_once(dirname(__FILE__) . '/php/aweber_webform_plugin.php');
    global $aweber_webform_plugin;
    $aweber_webform_plugin = new AWeberWebformPlugin();

    $options = get_option('AWeberWebformPluginWidgetOptions');
    if ($options['create_subscriber_comment_checkbox'] == 'ON' && is_numeric($options['list_id_create_subscriber']))
    {
        add_action('comment_form',array(&$aweber_webform_plugin,'add_checkbox'));
         add_action('comment_post',array(&$aweber_webform_plugin,'grab_email_from_comment'));
    }
    if ($options['create_subscriber_registration_checkbox'] == 'ON' && is_numeric($options['list_id_create_subscriber']))
    {
        add_action('register_form',array(&$aweber_webform_plugin,'add_checkbox'));
        add_action('register_post',array(&$aweber_webform_plugin,'grab_email_from_registration'));
    }
    add_action('comment_unapproved_to_approved',array(&$aweber_webform_plugin,'comment_approved'));
    add_action('comment_spam_to_approved',array(&$aweber_webform_plugin,'comment_approved'));
    add_action('delete_comment',array(&$aweber_webform_plugin,'comment_deleted'));
}


if (!function_exists('add_aweber_settings_link')) {
    function add_aweber_settings_link($links) {
            $settings_link = '<a href="admin.php?page=wpsp-autoresponder">Settings</a>';
            array_unshift($links, $settings_link);
            return $links;
    }
}

if (!function_exists('AWeberRegisterSettings')) {

    function AWeberAuthMessage() {
        global $aweber_webform_plugin;
        echo $aweber_webform_plugin->messages['auth_required'];
    }

    function AWeberRegisterSettings() {
        if (is_admin()) {
            global $aweber_webform_plugin;
            register_setting($aweber_webform_plugin->oauthOptionsName, 'aweber_webform_oauth_id');
            register_setting($aweber_webform_plugin->oauthOptionsName, 'aweber_webform_oauth_removed');
            register_setting($aweber_webform_plugin->oauthOptionsName, 'aweber_comment_checkbox_toggle');
            register_setting($aweber_webform_plugin->oauthOptionsName, 'aweber_registration_checkbox_toggle');
            register_setting($aweber_webform_plugin->oauthOptionsName, 'aweber_signup_text_value');

            $pluginAdminOptions = get_option($aweber_webform_plugin->adminOptionsName);
            if ($pluginAdminOptions['access_key'] == '') {
                add_action('admin_notices', 'AWeberAuthMessage');
                return;
            }
        }
    }
}


// Actions and filters.
if (isset($aweber_webform_plugin)) {
    // Actions
    add_action('aweber/aweber.php',  array(&$aweber_webform_plugin, 'init'));
    add_action('admin_init', 'AWeberRegisterSettings');
    
    add_action('admin_print_scripts', array(&$aweber_webform_plugin, 'addHeaderCode'));

}
?>
