<?php

/**
 * Upgrade Module
 * 
 * PHP version 5.6.x | 7.x | 8.x
 * 
 * @category  PLugins
 * @package   Wordpress
 * @author    Pejman Kheyri <pejmankheyri@gmail.com>
 * @copyright 2021 All rights reserved.
 */

if (is_admin()) {
    $installer_wpsms_ver = get_option('wordpress_smsir_db_version');
    if ($installer_wpsms_ver < WORDPRESS_SMSIR_VERSION ) {
        global $wp_statistics_db_version, $table_prefix;

        $create_sms_subscribes = ("CREATE TABLE {$table_prefix}smsir_subscribes(
			ID int(10) NOT NULL auto_increment,
			date DATETIME,
			name VARCHAR(100),
			mobile VARCHAR(20) NOT NULL,
			status tinyint(1),
			activate_key INT(11),
			group_ID VARCHAR(100),
			PRIMARY KEY(ID)) CHARSET=utf8
		");

        $alter_sms_subscribes = ("ALTER TABLE {$table_prefix}smsir_subscribes MODIFY name VARCHAR(100) NOT NULL, MODIFY group_ID VARCHAR(100) NOT NULL");

        $create_sms_subscribes_group = ("CREATE TABLE {$table_prefix}smsir_subscribes_group(
			ID int(10) NOT NULL auto_increment,
			name VARCHAR(100),
			PRIMARY KEY(ID)) CHARSET=utf8
		");

        $alter_sms_subscribes_group = ("ALTER TABLE {$table_prefix}smsir_subscribes_group MODIFY name VARCHAR(100) NOT NULL");

        $create_sms_send = ("CREATE TABLE {$table_prefix}smsir_send(
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
        dbDelta($create_sms_subscribes);
        dbDelta($alter_sms_subscribes);
        dbDelta($create_sms_subscribes_group);
        dbDelta($alter_sms_subscribes_group);
        dbDelta($create_sms_send);
        dbDelta($create_sms_verification);

        update_option('wordpress_smsir_db_version', WORDPRESS_SMSIR_VERSION);
    }
}
?>