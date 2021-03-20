<?php

/**
 * Subscribe Activation Page
 * 
 * PHP version 5.6.x | 7.x | 8.x
 * 
 * @category  PLugins
 * @package   Wordpress
 * @author    Pejman Kheyri <pejmankheyri@gmail.com>
 * @copyright 2021 All rights reserved.
 */

require_once "../../../../../wp-load.php";

$mobile = trim($_REQUEST['mobile']);
$activation = trim($_REQUEST['activation']);

if ($activation) {

    $check_mobile = $wpdb->get_row($wpdb->prepare("SELECT * FROM `{$table_prefix}smsir_subscribes` WHERE `mobile` = '%s'", $mobile));

    if ($activation == $check_mobile->activate_key) {

        $result = $wpdb->update(
            "{$table_prefix}smsir_subscribes", array('status' => '1'), array('mobile' => $mobile) 
        );

        if ($result) {
            do_action('wordpress_smsir_subscribe', $check_mobile->name, $mobile);
            echo 'success-1';
            exit(0);
        }
    } else {
        _e('Security Code is wrong', 'wordpress_smsir');
    }
} else {
    _e('Please complete all fields', 'wordpress_smsir');
}
?>