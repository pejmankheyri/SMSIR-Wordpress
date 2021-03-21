<?php

/**
 * Notification Page
 * 
 * PHP version 5.6.x | 7.x | 8.x
 * 
 * @category  PLugins
 * @package   Wordpress
 * @author    Pejman Kheyri <pejmankheyri@gmail.com>
 * @copyright 2021 All rights reserved.
 */

if (!defined('ABSPATH')) exit;

// Wordpress new version
if (get_option('wordpress_smsir_notification_new_wp_version')) {

    $update = array();
    $update = get_site_transient('update_core');
    $update = $update->updates;

    if (isset($update[1]) && ($update[1]->current > $wp_version)) {
        if (get_option('wp_last_send_notification') == false) {

            $webservice = get_option('wordpress_smsir_webservice');
            include_once dirname(__FILE__) . "/../classes/wordpress_smsir.class.php";
            include_once dirname(__FILE__) . "/../classes/webservice/{$webservice}.class.php";

            $sms = new $webservice;

            $sms->to = array(get_option('wordpress_smsir_admin_mobile'));
            $sms->msg = sprintf(__('WordPress %s is available! Please update now', 'wordpress_smsir'), $update[1]->current);

            $sms->sendSMS();
            update_option('wp_last_send_notification', true);
        }
    } else {
        update_option('wp_last_send_notification', false);
    }
}

/**
 * Notification New User.
 *
 * @param string $username_id username_id
 * 
 * @return array
 */
function Wordpress_Smsir_Notification_New_user($username_id)
{
    global $wpdb, $table_prefix, $sms, $date;

    $sms->to = array(get_option('wordpress_smsir_admin_mobile'));
    $string = get_option('wordpress_smsir_nrnu_tt');

    $username_info = get_userdata($username_id);
    $user_id = $username_info->ID;
    $get_mobile = $wpdb->get_results("SELECT * FROM `{$table_prefix}usermeta` WHERE `user_id` = '$user_id' AND `meta_key` = 'mobile'");
    $mobile = $get_mobile[0]->meta_value;
	
	$final_message = str_replace('%user_login%', $username_info->user_login, $string);
	$final_message = str_replace('%user_email%', $username_info->user_email, $final_message);
	$final_message = str_replace('%date_register%', $date, $final_message);

    $sms->msg = $final_message;

    $sms->sendSMS();

    if ($mobile) {
        $sms->inserttosmscustomerclub($username_info->user_login, $mobile);
    }
}

if(get_option('wordpress_smsir_nrnu_stats'))
    add_action('user_register', 'Wordpress_Smsir_Notification_New_user', 10, 1);

/**
 * Notification New Comment.
 *
 * @param string $comment_id     comment id
 * @param string $comment_smsect comment smsect
 * 
 * @return array
 */
function Wordpress_Smsir_Notification_New_comment($comment_id, $comment_smsect)
{
    global $sms;
    $sms->to = array(get_option('wordpress_smsir_admin_mobile'));

    $string = get_option('wordpress_smsir_gnc_tt');

	$final_message = str_replace('%comment_author%', $comment_smsect->comment_author, $string);
	$final_message = str_replace('%comment_author_email%', $comment_smsect->comment_author_email, $final_message);
	$final_message = str_replace('%comment_author_url%', $comment_smsect->comment_author_url, $final_message);
	$final_message = str_replace('%comment_author_IP%', $comment_smsect->comment_author_IP, $final_message);
	$final_message = str_replace('%comment_date%', $comment_smsect->comment_date, $final_message);
	$final_message = str_replace('%comment_content%', $comment_smsect->comment_content, $final_message);
		
    $sms->msg = $final_message;
    $sms->sendSMS();
}

if(get_option('wordpress_smsir_gnc_stats'))
    add_action('wp_insert_comment', 'Wordpress_Smsir_Notification_New_comment', 99, 2);

/**
 * Notification Login.
 *
 * @param string $username_login username login
 * @param string $username       username
 * 
 * @return array
 */
function Wordpress_Smsir_Notification_login($username_login, $username)
{
    global $sms;
    $sms->to = array(get_option('wordpress_smsir_admin_mobile'));

    $string = get_option('wordpress_smsir_ul_tt');

	$final_message = str_replace('%user_login%', $username->user_login, $string);
	$final_message = str_replace('%user_email%', $username->user_email, $final_message);
	$final_message = str_replace('%user_registered%', $username->user_registered, $final_message);
	$final_message = str_replace('%display_name%', $username->display_name, $final_message);
		
    $sms->msg = $final_message;
    $sms->sendSMS();
}

if(get_option('wordpress_smsir_ul_stats'))
    add_action('wp_login', 'Wordpress_Smsir_Notification_login', 99, 2);

/**
 * Setup Wpcf7 form.
 *
 * @param string $form form
 * 
 * @return void
 */
function Wordpress_Smsir_Setup_Wpcf7_form($form)
{
    $options = get_option('wpcf7_sms_' . $form->id);
    include_once dirname(__FILE__) . "/../templates/wp-sms-wpcf7-form.php";
}

/**
 * Save Wpcf7 form.
 *
 * @param string $form form
 * 
 * @return void
 */
function Wordpress_Smsir_Save_Wpcf7_form($form)
{
    update_option('wpcf7_sms_' . $form->id, $_POST['wpcf7-sms']);
}

/**
 * Send Wpcf7 sms.
 *
 * @param string $form form
 * 
 * @return void
 */
function Wordpress_Smsir_Send_Wpcf7_sms($form)
{
    global $sms;

    $options = get_option('wpcf7_sms_' . $form->id);
    $options['phone'] = get_option('wordpress_smsir_admin_mobile');
    $options['message'] = get_option('wordpress_smsir_cf7_no_tt');

    $string = get_option('wordpress_smsir_cf7_no_tt');

	$final_message = str_replace('%form_title%', $form->title, $string);
	$final_message = str_replace('%form_id%',  $form->id, $final_message);
		
    if ($options['message'] && $options['phone'] ) {

        // Replace merged Contact Form 7 fields
        /*if( defined( 'WPCF7_VERSION' ) && WPCF7_VERSION < 3.1 ) {
            $regex = '/\[\s*([a-zA-Z_][0-9a-zA-Z:._-]*)\s*\]/';
        } else {
            $regex = '/(\[?)\[\s*([a-zA-Z_][0-9a-zA-Z:._-]*)\s*\](\]?)/';
        }

        $callback = array( &$form, 'mail_callback' );
        $message = preg_replace_callback( $regex, $form, $options['message'] );*/

        $sms->to = array( $options['phone'] );
        $sms->msg = $final_message;

        $sms->sendSMS();
    }
}

// Contact Form 7 Hooks
if (get_option('wordpress_smsir_add_wpcf7')) {
    //add_action('wpcf7_admin_after_form', 'Wordpress_Smsir_Setup_Wpcf7_form'); 
    //add_action('wpcf7_after_save', 'Wordpress_Smsir_Save_Wpcf7_form');
    add_action('wpcf7_before_send_mail', 'Wordpress_Smsir_Send_Wpcf7_sms');
}

/**
 * Woocommerce New Order.
 *
 * @param string $order_id order_id
 * 
 * @return void
 */
function Wordpress_Smsir_Woocommerce_New_order($order_id)
{
    global $sms;
    $sms->to = array(get_option('wordpress_smsir_admin_mobile'));

    $string = get_option('wordpress_smsir_wc_no_tt');
	
	$final_message = str_replace('%order_id%', $order_id, $string);
		
    $sms->msg = $final_message;

    $sms->sendSMS();
}

if(get_option('wordpress_smsir_wc_no_stats'))
    add_action('woocommerce_new_order', 'Wordpress_Smsir_Woocommerce_New_order');

/**
 * Easy Digital Downloads New Order.
 * 
 * @return void
 */
function Wordpress_Smsir_Edd_New_order()
{
    global $sms;
    $sms->to = array(get_option('wordpress_smsir_admin_mobile'));

    $sms->msg = get_option('wordpress_smsir_edd_no_tt');

    $sms->sendSMS();
}

if(get_option('wordpress_smsir_edd_no_stats'))
    add_action('edd_complete_purchase', 'Wordpress_Smsir_Edd_New_order');