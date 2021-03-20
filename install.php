<?php

/**
 * Install Function
 * 
 * PHP version 5.6.x | 7.x | 8.x
 * 
 * @category  PLugins
 * @package   Wordpress
 * @author    Pejman Kheyri <pejmankheyri@gmail.com>
 * @copyright 2021 All rights reserved.
 */

/**
 * Install Function.
 *
 * @return void
 */
function Wordpress_Smsir_install() 
{
    global $wordpress_smsir_db_version, $table_prefix, $wpdb;

    $plugin_folder = get_home_path()."wp-content/plugins/WordpressPluginSMSIR-V*.*";
    $file = glob($plugin_folder);

    if (count($file) > 1) {
        $current_plugin_name = 'WordpressPluginSMSIR-V'.WORDPRESS_SMSIR_VERSION;
        foreach ($file as $key => $val) {
            $val = str_replace(get_home_path()."wp-content/plugins/", "", $val);
            if ($val != $current_plugin_name) {
                $old_versions[] = $val;
            }
        }
        $oldver = implode(',', $old_versions);

        echo 'Please Deactivate And Remove SMSIR Old Plugin(s) ('.$oldver.')';
        exit;

    } else {

        $create_sms_subscribes = ("CREATE TABLE IF NOT EXISTS {$table_prefix}smsir_subscribes(
			ID int(10) NOT NULL auto_increment,
			date DATETIME,
			name VARCHAR(100),
			mobile VARCHAR(20) NOT NULL,
			status tinyint(1),
			activate_key INT(11),
			group_ID VARCHAR(100),
			PRIMARY KEY(ID)) CHARSET=utf8
		");

        $create_sms_subscribes_group = ("CREATE TABLE IF NOT EXISTS {$table_prefix}smsir_subscribes_group(
			ID int(10) NOT NULL auto_increment,
			name VARCHAR(100),
			PRIMARY KEY(ID)) CHARSET=utf8
		");

        $insert_sms_subscribe_default_group = ("INSERT INTO {$table_prefix}smsir_subscribes_group() VALUES(1,'default')");

        $create_sms_send = ("CREATE TABLE IF NOT EXISTS {$table_prefix}smsir_send(
			ID int(10) NOT NULL auto_increment,
			date DATETIME,
			sender VARCHAR(100) NOT NULL,
			message TEXT NOT NULL,
			recipient TEXT NOT NULL,
			PRIMARY KEY(ID)) CHARSET=utf8
		");

        $create_sms_verification = ("CREATE TABLE IF NOT EXISTS {$table_prefix}smsir_verification(
			id int(10) NOT NULL auto_increment,
			user_login VARCHAR(60),
			user_email VARCHAR(100),
			mobile VARCHAR(11),
			code INT(1),
			status VARCHAR(50),
			add_time VARCHAR(10),
			PRIMARY KEY(id)) CHARSET=utf8
		");

        include_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $table_name = $wpdb->prefix . "smsir_subscribes";
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
            $sql = "ALTER TABLE " . $table_name .
                " MODIFY name VARCHAR(100) NOT NULL, MODIFY group_ID varchar(100) NOT NULL";
            $wpdb->query($sql);                
        }

        $table_name = $wpdb->prefix . "smsir_subscribes_group";
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
            $sql = "ALTER TABLE " . $table_name .
                " MODIFY name VARCHAR(100) NOT NULL";
            $wpdb->query($sql);                
        }

        dbDelta($create_sms_subscribes);
        dbDelta($create_sms_subscribes_group);
        dbDelta($insert_sms_subscribe_default_group);
        dbDelta($create_sms_send);
        dbDelta($create_sms_verification);

        add_option('wordpress_smsir_db_version', WORDPRESS_SMSIR_VERSION);
    }
}
?>