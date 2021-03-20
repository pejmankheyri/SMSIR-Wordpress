<?php

/**
 * Newsletter Page
 * 
 * PHP version 5.6.x | 7.x | 8.x
 * 
 * @category  PLugins
 * @package   Wordpress
 * @author    Pejman Kheyri <pejmankheyri@gmail.com>
 * @copyright 2021 All rights reserved.
 */

if (!defined('ABSPATH')) exit;

/**
 * Subscribe Meta Box.
 *
 * @return void
 */
function Wordpress_Smsir_Subscribe_Meta_box()
{
    add_meta_box('subscribe-meta-box', __('Subscribe SMS', 'wordpress_smsir'), 'Wordpress_Smsir_Subscribe_post', 'post', 'normal', 'high');
}

if(get_option('wordpress_smsir_subscribes_send'))
    add_action('add_meta_boxes', 'Wordpress_Smsir_Subscribe_Meta_box');

/**
 * Subscribe Post.
 *
 * @param string $post post
 * 
 * @return array
 */
function Wordpress_Smsir_Subscribe_post($post)
{
    $values = get_post_custom($post->ID);
    $selected = isset($values['subscribe_post']) ? esc_attr($values['subscribe_post'][0]) : '';
    wp_nonce_field('subscribe_box_nonce', 'meta_box_nonce');
    include_once dirname(__FILE__) . "/../templates/settings/meta-box.php";
}

/**
 * Subscribe Post Save.
 *
 * @param string $post_id post_id
 * 
 * @return array
 */
function Wordpress_Smsir_Subscribe_Post_save($post_id)
{
    if(!current_user_can('edit_post')) return;
    if(isset($_POST['subscribe_post']))
        update_post_meta($post_id, 'subscribe_post', esc_attr($_POST['subscribe_post']));
}
add_action('save_post', 'Wordpress_Smsir_Subscribe_Post_save');

/**
 * Subscribe Send.
 *
 * @param string $post_ID post_ID
 * 
 * @return array
 */
function Wordpress_Smsir_Subscribe_send($post_ID)
{
    if ($_REQUEST['subscribe_post'] == 'yes') {
        global $wpdb, $table_prefix, $sms;
        $sms->to = $wpdb->get_col("SELECT mobile FROM {$table_prefix}smsir_subscribes");

        $string = get_option('wordpress_smsir_text_template');
        
        $final_message = str_replace('%title_post%', get_the_title($post_ID), $string);
        $final_message = str_replace('%url_post%', wp_get_shortlink($post_ID), $final_message);
        $final_message = str_replace('%date_post%', get_post_time(get_option('date_format'), true, $post_ID), $final_message);
        
        if (get_option('wordpress_smsir_text_template')) {
            $sms->msg = $final_message;
        } else {
            $sms->msg = get_the_title($post_ID);
        }

        $sms->sendSMS();
        return $post_ID;
    }
}

if(get_option('wordpress_smsir_subscribes_send'))
    add_action('publish_post', 'Wordpress_Smsir_Subscribe_send');

/**
 * Register New Subscribe.
 *
 * @param string $name   name
 * @param string $mobile mobile
 * 
 * @return array
 */
function Wordpress_Smsir_Register_New_subscribe($name, $mobile)
{
    global $sms;
    $string = get_option('wordpress_smsir_subscribes_text_send');
    
    $final_message = str_replace('%subscribe_name%', $name, $string);
    $final_message = str_replace('%subscribe_mobile%', $mobile, $final_message);

    $sms->to = array($mobile);
    $sms->msg = $final_message;

    $sms->sendSMS();
}
if(get_option('wordpress_smsir_subscribes_send_sms'))
    add_action('wordpress_smsir_subscribe', 'Wordpress_Smsir_Register_New_subscribe', 10, 2);