<?php

/**
 * Modules Main File
 * 
 * PHP version 5.6.x | 7.x | 8.x
 * 
 * @category  PLugins
 * @package   Wordpress
 * @author    Pejman Kheyri <pejmankheyri@gmail.com>
 * @copyright 2021 All rights reserved.
 */

/*
Plugin Name: Wordpress SMS
Description: Send a SMS via sms.ir, Subscribe for sms newsletter and send verification code via mobile to users.
Version: 1.0.0
Author: pejmankheyri@gmail.com
Author URI: https://ipe.ir/
Contributors: pejmankheyri
WC requires at least: 3.0.0
WC tested up to: 5.0.0
*/

if (!in_array('ob_gzhandler', ob_list_handlers())) {
    ob_start('ob_gzhandler');
} else {
    ob_start();
}

define('WORDPRESS_SMSIR_VERSION', '1.0.0');
define('WORDPRESS_SMSIR_DIR_PLUGIN', plugin_dir_url(__FILE__));

define('WORDPRESS_SMSIR_MOBILE_REGEX', '/^9(0[1-5]|1[0-9]|3[0-9]|2[0-2]|9[0-1])-?[0-9]{3}-?[0-9]{4}$/');
define('WORDPRESS_SMSIR_MOBILE_REGEX_ZERO', '/^09(0[1-5]|1[0-9]|3[0-9]|2[0-2]|9[0-1])-?[0-9]{3}-?[0-9]{4}$/');

require_once dirname(__FILE__) . '/different-versions.php';
require_once dirname(__FILE__) . '/install.php';
require_once dirname(__FILE__) . '/upgrade.php';

register_activation_hook(__FILE__, 'Wordpress_Smsir_install');

load_plugin_textdomain('wordpress_smsir', false, dirname(plugin_basename(__FILE__)) . '/languages');
__('Send a SMS via WordPress, Subscribe for sms newsletter and send verification code via mobile to users.', 'wordpress_smsir');

global $wordpress_smsir_db_version, $wpdb;
date_default_timezone_set('Asia/Tehran');
$date = date('Y-m-d H:i:s', current_time('timestamp', 0));

/**
 * Define Pages.
 *
 * @return array
 */
function Wordpress_Smsir_page()
{
    if (function_exists('add_options_page')) {
        add_menu_page(__('Wordpress SMS', 'wordpress_smsir'), __('Wordpress SMS', 'wordpress_smsir'), 'manage_options', __FILE__, 'Wordpress_Smsir_Sendsms_page');
        add_submenu_page(__FILE__, __('Send SMS', 'wordpress_smsir'), __('Send SMS', 'wordpress_smsir'), 'manage_options', __FILE__, 'Wordpress_Smsir_Sendsms_page');
        add_submenu_page(__FILE__, __('Posted SMS', 'wordpress_smsir'), __('Posted', 'wordpress_smsir'), 'manage_options', 'wordpress_smsir/posted', 'Wordpress_Smsir_Posted_Sms_page');
        add_submenu_page(__FILE__, __('Verifications', 'wordpress_smsir'), __('Verifications', 'wordpress_smsir'), 'manage_options', 'wordpress_smsir/verifications', 'Wordpress_Smsir_Verifications_Sms_page');
        add_submenu_page(__FILE__, __('Members Newsletter', 'wordpress_smsir'), __('Subscribers', 'wordpress_smsir'), 'manage_options', 'wordpress_smsir/subscribe', 'Wordpress_Smsir_Subscribes_page');
        add_submenu_page(__FILE__, __('Setting', 'wordpress_smsir'), __('Setting', 'wordpress_smsir'), 'manage_options', 'wordpress_smsir/setting', 'Wordpress_Smsir_Setting_page');
        add_submenu_page(__FILE__, __('About', 'wordpress_smsir'), __('About', 'wordpress_smsir'), 'manage_options', 'wordpress_smsir/about', 'Wordpress_Smsir_About_page');
    }
}
add_action('admin_menu', 'Wordpress_Smsir_page');

/**
 * Define Icons.
 *
 * @return array
 */
function Wordpress_Smsir_icon() 
{
    global $wp_version;

    if (version_compare($wp_version, '3.8-RC', '>=') || version_compare($wp_version, '3.8', '>=') ) {
        wp_enqueue_style('wps-admin-css', plugin_dir_url(__FILE__) . 'assets/css/admin.css', true, '1.0');
    } else {
        wp_enqueue_style('wps-admin-css', plugin_dir_url(__FILE__) . 'assets/css/admin-old.css', true, '1.0');
    }
}
add_action('admin_head', 'Wordpress_Smsir_icon');

$webservice = 'smsir';
require_once dirname(__FILE__) . "/includes/classes/wordpress_smsir.class.php";
require_once dirname(__FILE__) . "/includes/classes/webservice/{$webservice}.class.php";

$sms = new $webservice;

$sms->username = get_option('wordpress_smsir_username');
$sms->password = get_option('wordpress_smsir_password');
$sms->apidomain = get_option('wordpress_smsir_apidomain');

if ($sms->has_key && get_option('wordpress_smsir_key')) {
    $sms->has_key = get_option('wordpress_smsir_key');
}

$sms->from = get_option('wordpress_smsir_number');

if ($sms->unitrial == true) {
    $sms->unit = __('Credit', 'wordpress_smsir');
} else {
    $sms->unit = __('SMS', 'wordpress_smsir');
}

if(!get_option('wordpress_smsir_mcc'))
    update_option('wordpress_smsir_mcc', '09');

/**
 * Creating Subscribes.
 *
 * @return void
 */
function Wordpress_Smsir_subscribes() 
{
    global $wpdb, $table_prefix;
    $get_group_result = $wpdb->get_results("SELECT * FROM `{$table_prefix}smsir_subscribes_group`");
    include_once dirname(__FILE__) . "/includes/templates/wordpress_smsir-subscribe-form.php";
}
add_shortcode('subscribe', 'Wordpress_Smsir_subscribes');

/**
 * Creating Loader.
 *
 * @return void
 */
function Wordpress_Smsir_loader()
{
    wp_enqueue_style('wpsms-css', plugin_dir_url(__FILE__) . 'assets/css/style.css', true, '1.1');
    if(get_option('wordpress_smsir_call_jquery') )
        wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'Wordpress_Smsir_loader');

/**
 * Creating Adminbar.
 *
 * @return void
 */
function Wordpress_Smsir_adminbar()
{
    global $wp_admin_bar;
    $get_last_credit = get_option('wordpress_smsir_last_credit');
    if (is_super_admin() && is_admin_bar_showing()) {
        if ($get_last_credit) {
            global $sms;
            $wp_admin_bar->add_menu(
                array(
                    'id' => 'wp-credit-sms',
                    'title' => sprintf(__('Your Credit: %s %s', 'wordpress_smsir'), number_format($get_last_credit), $sms->unit),
                    'href' => get_bloginfo('url').'/wp-admin/admin.php?page=wordpress_smsir/setting'
                )
            );
        }

        $wp_admin_bar->add_menu(
            array(
                'id' => 'wp-send-sms',
                'parent' => 'new-content',
                'title' => __('SMS', 'wordpress_smsir'),
                'href' => get_bloginfo('url').'/wp-admin/admin.php?page=wordpress_smsir/wordpress_smsir.php'
            )
        );
    } else {
        return false;
    }
}
add_action('admin_bar_menu', 'Wordpress_Smsir_adminbar');

/**
 * Creating Rightnow Discussion.
 *
 * @return void
 */
function Wordpress_Smsir_Rightnow_discussion()
{
    global $sms;
    echo "<tr><td class='b'><a href='".get_bloginfo('url')."/wp-admin/admin.php?page=wordpress_smsir/wordpress_smsir.php'>".number_format(get_option('wordpress_smsir_last_credit'))."</a></td><td><a href='".get_bloginfo('url')."/admin.php?page=wordpress_smsir/wordpress_smsir.php'>".__('Credit', 'wordpress_smsir')." (".$sms->unit.")</a></td></tr>";
}
add_action('right_now_discussion_table_end', 'Wordpress_Smsir_Rightnow_discussion');

/**
 * Creating Rightnow Content.
 *
 * @return void
 */
function Wordpress_Smsir_Rightnow_content()
{
    global $wpdb, $table_prefix;
    $usernames = $wpdb->get_var("SELECT COUNT(*) FROM {$table_prefix}smsir_subscribes");
    echo "<tr><td class='b'><a href='".get_bloginfo('url')."/wp-admin/admin.php?page=wordpress_smsir/subscribe'>".$usernames."</a></td><td><a href='".get_bloginfo('url')."/wp-admin/admin.php?page=wordpress_smsir/subscribe'>".__('Newsletter Subscriber', 'wordpress_smsir')."</a></td></tr>";
}
add_action('right_now_content_table_end', 'Wordpress_Smsir_Rightnow_content');

/**
 * Creating Rightnow Glance.
 *
 * @return void
 */
function Wordpress_Smsir_glance()
{
    global $wpdb, $table_prefix;
    $admin_url = get_bloginfo('url')."/wp-admin";
    $subscribe = $wpdb->get_var("SELECT COUNT(*) FROM {$table_prefix}smsir_subscribes");

    echo "<li class='wpsms-subscribe-count'><a href='{$admin_url}/admin.php?page=wordpress_smsir/subscribe'>".sprintf(__('%s Subscriber', 'wordpress_smsir'), $subscribe)."</a></li>";
    echo "<li class='wpsms-credit-count'><a href='{$admin_url}/admin.php?page=wordpress_smsir/setting&tab=web-service'>".sprintf(__('%s SMS Credit', 'wordpress_smsir'), number_format(doubleval(get_option('wordpress_smsir_last_credit'))))."</a></li>";
}
add_action('dashboard_glance_items', 'Wordpress_Smsir_glance');

/**
 * Enable Function.
 *
 * @return void
 */
function Wordpress_Smsir_enable()
{
    $get_bloginfo_url = get_admin_url() . "admin.php?page=wordpress_smsir/setting&tab=web-service";
    echo '<div class="error"><p>'.sprintf(__('Please check the <a href="%s">SMS credit</a> the settings', 'wordpress_smsir'), $get_bloginfo_url).'</p></div>';
}

if(!get_option('wordpress_smsir_username') || !get_option('wordpress_smsir_password'))
    add_action('admin_notices', 'Wordpress_Smsir_enable');

/**
 * Widget Function.
 *
 * @return void
 */
function Wordpress_Smsir_widget()
{
    wp_register_sidebar_widget('wordpress_smsir', __('Subscribe to SMS', 'wordpress_smsir'), 'Wordpress_Smsir_Subscribe_Show_widget', array('description' => __('Subscribe to SMS', 'wordpress_smsir')));
    wp_register_widget_control('wordpress_smsir', __('Subscribe to SMS', 'wordpress_smsir'), 'Wordpress_Smsir_Subscribe_Control_widget');
}
add_action('plugins_loaded', 'Wordpress_Smsir_widget');

/**
 * Show Widget Function.
 *
 * @param string $args arguments
 * 
 * @return array
 */
function Wordpress_Smsir_Subscribe_Show_widget($args)
{
    extract($args);
    echo $before_title . get_option('wordpress_smsir_widget_name') . $after_title;
    Wordpress_Smsir_subscribes();
}

/**
 * Widget Function.
 *
 * @return void
 */
function Wordpress_Smsir_Subscribe_Control_widget()
{
    if ($_POST['wordpress_smsir_submit_widget']) {
        update_option('wordpress_smsir_widget_name', $_POST['wordpress_smsir_widget_name']);
    }
    include_once dirname(__FILE__) . "/includes/templates/wordpress_smsir-widget.php";
}

/**
 * Pointer Function.
 *
 * @param string $hook_suffix hook suffix
 * 
 * @return array
 */
function Wordpress_Smsir_pointer($hook_suffix)
{
    wp_enqueue_style('wp-pointer');
    wp_enqueue_script('wp-pointer');
    wp_enqueue_script('utils');
}
add_action('admin_enqueue_scripts', 'Wordpress_Smsir_pointer');

/**
 * Creates Send Sms Page.
 *
 * @return void
 */
function Wordpress_Smsir_Sendsms_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    global $wpdb, $table_prefix;

    wp_enqueue_style('wpsms-css', plugin_dir_url(__FILE__) . 'assets/css/style.css', true, '1.1');
    wp_enqueue_script('functions', plugin_dir_url(__FILE__) . 'assets/js/functions.js', true, '1.0');

    $get_group_result = $wpdb->get_results("SELECT * FROM `{$table_prefix}smsir_subscribes_group`");

    include_once dirname(__FILE__) . "/includes/templates/settings/send-sms.php";
}

/**
 * Creates Posted Sms Page.
 *
 * @return void
 */
function Wordpress_Smsir_Posted_Sms_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    global $wpdb, $table_prefix;

    wp_enqueue_style('pagination-css', plugin_dir_url(__FILE__) . 'assets/css/pagination.css', true, '1.0');
    include_once dirname(__FILE__) . '/includes/classes/pagination.class.php';

    if (isset($_POST['doaction']) && isset($_POST['column_ID'])) {
        $get_IDs = implode(",", $_POST['column_ID']);

        $check_ID = $wpdb->query($wpdb->prepare("SELECT * FROM {$table_prefix}smsir_send WHERE ID IN (%s)", $get_IDs));
        switch($_POST['action']) {
        case 'trash':
            if ($check_ID) {
                foreach ($_POST['column_ID'] as $items) {
                    $wpdb->delete("{$table_prefix}smsir_send", array('ID' => $items));
                }
                echo "<div class='updated'><p>" . __('With success was removed', 'wordpress_smsir') . "</div></p>";
            } else {
                echo "<div class='error'><p>" . __('Not Found', 'wordpress_smsir') . "</div></p>";
            }
            break;
        }
    }
    $total = $wpdb->get_results("SELECT * FROM `{$table_prefix}smsir_send`");

    include_once dirname(__FILE__) . "/includes/templates/settings/posted.php";
}

/**
 * Creates Verifications Sms Page.
 *
 * @return void
 */
function Wordpress_Smsir_Verifications_Sms_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    global $wpdb, $table_prefix;

    wp_enqueue_style('pagination-css', plugin_dir_url(__FILE__) . 'assets/css/pagination.css', true, '1.0');
    include_once dirname(__FILE__) . '/includes/classes/pagination.class.php';

    $total = $wpdb->get_results("SELECT * FROM `{$table_prefix}smsir_verification`");
    include_once dirname(__FILE__) . "/includes/templates/settings/verifications.php";
}

/**
 * Creates Subscribes Sms Page.
 *
 * @return void
 */
function Wordpress_Smsir_Subscribes_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    global $wpdb, $table_prefix, $date;

    wp_enqueue_style('css', plugin_dir_url(__FILE__) . 'assets/css/style.css', true, '1.0');
    if (isset($_POST['wpsms_group_name'])) {
        $group_array = $_POST['wpsms_group_name'];
        if (is_array($group_array)) {
            $group = implode(",", $group_array);
        } else {
            $group = trim($group_array);
        }
    }

    if (isset($_POST['wpsms_add_group'])) {

        if ($group) {
            $check_group = $wpdb->query($wpdb->prepare("SELECT * FROM `{$table_prefix}smsir_subscribes_group` WHERE `name` = '%s'", $group));

            if (!$check_group) {
                if (strpos($group, ',') == false) {
                    $check = $wpdb->insert(
                        "{$table_prefix}smsir_subscribes_group", 
                        array(
                            'name' => $group
                        )
                    );

                    if ($check) {
                        echo "<div class='updated'><p>" . sprintf(__('Group <strong>%s</strong> was added successfully.', 'wordpress_smsir'), $group) . "</div></p>";
                    }
                }
            } else {
                echo "<div class='error'><p>" . __('Group name is repeated', 'wordpress_smsir') . "</div></p>";
            }
        } else {
            echo "<div class='error'><p>" . __('Please complete field', 'wordpress_smsir') . "</div></p>";
        }
    }

    if(isset($_POST['wp_subscribe_name']))
        $name = trim($_POST['wp_subscribe_name']);

    if(isset($_POST['wp_group_name']))
        $grp_name = trim($_POST['wp_group_name']);

    if(isset($_POST['wp_subscribe_mobile']))
        $mobile = trim($_POST['wp_subscribe_mobile']);

    if (isset($_POST['wp_add_subscribe'])) {
        if ($name && $mobile && $group) {
            if ((preg_match(WORDPRESS_SMSIR_MOBILE_REGEX, $mobile)) || (preg_match(WORDPRESS_SMSIR_MOBILE_REGEX_ZERO, $mobile))) {
                $check_mobile = $wpdb->query($wpdb->prepare("SELECT * FROM `{$table_prefix}smsir_subscribes` WHERE `mobile` = '%s'", $mobile));

                if (!$check_mobile) {
                    $check = $wpdb->insert(
                        "{$table_prefix}smsir_subscribes", 
                        array(
                            'date' => $date,
                            'name' => $name,
                            'mobile' => $mobile,
                            'status' => '1',
                            'group_ID' => $group,
                        )
                    );

                    if ($check) {
                        echo "<div class='updated'><p>" . sprintf(__('username <strong>%s</strong> was added successfully.', 'wordpress_smsir'), $name) . "</div></p>";
                    }
                } else {
                    echo "<div class='error'><p>" . __('Phone number is repeated', 'wordpress_smsir') . "</div></p>";
                }
            } else {
                echo "<div class='error'><p>" . __('Please enter a valid mobile number', 'wordpress_smsir') . "</div></p>";
            }
        } else {
            echo "<div class='error'><p>" . __('Please complete all fields', 'wordpress_smsir') . "</div></p>";
        }
    }

    if (isset($_POST['wpsms_delete_group'])) {
        if ($group) {
            $check_group = $wpdb->query($wpdb->prepare("SELECT * FROM `{$table_prefix}smsir_subscribes_group` WHERE `ID` = '%s'", $group));

            if ($check_group) {
                $group_name = $wpdb->get_row($wpdb->prepare("SELECT * FROM `{$table_prefix}smsir_subscribes_group` WHERE `ID` = '%s'", $group));
                $check = $wpdb->delete("{$table_prefix}smsir_subscribes_group", array('ID' => $group));

                if ($check) {
                    $get_result = $wpdb->get_results("SELECT * FROM `{$table_prefix}smsir_subscribes` WHERE `group_ID` REGEXP '{$group}'");
                    if ($get_result) {
                        foreach ($get_result as $gets) {
                            if ($gets->group_ID) {
                                if (strpos($gets->group_ID, ',') != false) {
                                    $groups_id = explode(",", $gets->group_ID);
                                    foreach ($groups_id as $g_key=>$g_val) {
                                        if ($g_val == $group) {
                                            $groups_var = "";
                                        } else {
                                            $groups_var = $g_val;
                                        }
                                        if ($groups_var != "") {
                                            $groups[] = $groups_var;
                                        }
                                    }
                                    $groupsid = implode(",", $groups);
                                    $update = $wpdb->update(
                                        "{$table_prefix}smsir_subscribes",
                                        array(
                                            'group_ID' => $groupsid
                                        ),
                                        array(
                                            'ID' => $gets->ID
                                        )
                                    );
                                    unset($groups);
                                } else {
                                    $update = $wpdb->update(
                                        "{$table_prefix}smsir_subscribes",
                                        array(
                                            'group_ID' => 0
                                        ),
                                        array(
                                            'ID' => $gets->ID
                                        )
                                    );
                                }
                            }
                        }
                    }
                    echo "<div class='updated'><p>" . sprintf(__('Group <strong>%s</strong> was successfully removed.', 'wordpress_smsir'), $group_name->name) . "</div></p>";
                }
            }
        } else {
            echo "<div class='error'><p>" . __('Nothing found!', 'wordpress_smsir') . "</div></p>";
        }
    }

    if (isset($_POST['wp_edit_subscribe'])) {

        if ($name && $mobile && $group) {
            if ((preg_match(WORDPRESS_SMSIR_MOBILE_REGEX, $mobile)) || (preg_match(WORDPRESS_SMSIR_MOBILE_REGEX_ZERO, $mobile))) {

                $check = $wpdb->update(
                    "{$table_prefix}smsir_subscribes",
                    array(
                        'name' => $name,
                        'mobile' => $mobile,
                        'status' => $_POST['wp_subscribe_status'],
                        'group_ID' => $group
                    ),
                    array(
                        'ID' => $_GET['ID']
                    )
                );

                if ($check) {
                    echo "<div class='updated'><p>" . sprintf(__('username <strong>%s</strong> was update successfully.', 'wordpress_smsir'), $name) . "</div></p>";
                }
            } else {
                echo "<div class='error'><p>" . __('Please enter a valid mobile number', 'wordpress_smsir') . "</div></p>";
            }
        } else {
            echo "<div class='error'><p>" . __('Please complete all fields', 'wordpress_smsir') . "</div></p>";
        }

    }

    if (isset($_POST['wp_edit_group'])) {
        if ($grp_name) {
            $check = $wpdb->update(
                "{$table_prefix}smsir_subscribes_group",
                array(
                    'name' => $grp_name
                ),
                array(
                    'ID' => $_POST['wp_group_id']
                )
            );
            if ($check) {
                echo "<div class='updated'><p>" . sprintf(__('Group name <strong>%s</strong> was update successfully.', 'wordpress_smsir'), $grp_name) . "</div></p>";
            }
        } else {
            echo "<div class='error'><p>" . __('Please complete all fields', 'wordpress_smsir') . "</div></p>";
        }
    }

    if (isset($_GET['group'])) {
        $total = $wpdb->query($wpdb->prepare("SELECT * FROM `{$table_prefix}smsir_subscribes` WHERE `group_ID` REGEXP '%s'", $_GET['group']));
    } else {
        $total = $wpdb->get_results("SELECT * FROM `{$table_prefix}smsir_subscribes`");
        $total = count($total);
    }

    if (isset($_POST['search'])) {
        $search_query = "%" . $_POST['s'] . "%";
        $total = $wpdb->query($wpdb->prepare("SELECT * FROM `{$table_prefix}smsir_subscribes` WHERE `name` LIKE '%s' OR `mobile` LIKE '%s'", $search_query, $search_query));
    }

    $get_group_result = $wpdb->get_results("SELECT * FROM `{$table_prefix}smsir_subscribes_group`");

    /* Pagination */
    wp_enqueue_style('pagination-css', plugin_dir_url(__FILE__) . 'assets/css/pagination.css', true, '1.0');
    include_once dirname(__FILE__) . '/includes/classes/pagination.class.php';

    // Instantiate pagination smsect with appropriate arguments
    $pagesPerSection = 10;
    $rowsperpage = 20;

    $options = array($rowsperpage, "All");
    $stylePageOff = "pageOff";
    $stylePageOn = "pageOn";
    $styleErrors = "paginationErrors";
    $styleSelect = "paginationSelect";

    $Pagination = new Pagination($total, $pagesPerSection, $options, false, $stylePageOff, $stylePageOn, $styleErrors, $styleSelect);

    $start = $Pagination->getEntryStart();
    $end = $Pagination->getEntryEnd();
    /* Pagination */

    if (isset($_POST['doaction'])) {
        $get_IDs = implode(",", $_POST['column_ID']);
        $check_ID = $wpdb->query($wpdb->prepare("SELECT * FROM {$table_prefix}smsir_subscribes WHERE ID IN (%s)", $get_IDs));

        switch($_POST['action']) {
        case 'trash':
            if ($check_ID) {
                foreach ($_POST['column_ID'] as $items) {
                    $wpdb->delete("{$table_prefix}smsir_subscribes", array('ID' => $items));
                }
                echo "<div class='updated'><p>" . __('With success was removed', 'wordpress_smsir') . "</div></p>";
            } else {
                    echo "<div class='error'><p>" . __('Not Found', 'wordpress_smsir') . "</div></p>";
            }
            break;
        case 'active':
            if ($check_ID) {
                foreach ($_POST['column_ID'] as $items) {
                    $wpdb->update("{$table_prefix}smsir_subscribes", array('status' => '1'), array('ID' => $items));
                }
                echo "<div class='updated'><p>" . __('User is active.', 'wordpress_smsir') . "</div></p>";
            } else {
                echo "<div class='error'><p>" . __('Not Found', 'wordpress_smsir') . "</div></p>";
            }
            break;
        case 'deactive':
            if ($check_ID) {
                foreach ($_POST['column_ID'] as $items) {
                    $wpdb->update("{$table_prefix}smsir_subscribes", array('status' => '0'), array('ID' => $items));
                }
                echo "<div class='updated'><p>" . __('User is Deactive..', 'wordpress_smsir') . "</div></p>";
            } else {
                echo "<div class='error'><p>" . __('Not Found', 'wordpress_smsir') . "</div></p>";
            }
            break;
        }
    }

    if (!$get_group_result) {
        add_action('admin_print_footer_scripts', 'Wordpress_Smsir_Group_pointer');
    }

    if (isset($_GET['action'])) {
        if ($_GET['action'] == 'import') {
            include_once dirname(__FILE__) . "/includes/classes/excel-reader.class.php";
            if (isset($_POST['wps_import'])) {
                if (!$_FILES['wps-import-file']['error']) {

                    $filename = substr($_FILES['wps-import-file']['name'], -4);
                    $filetype = $_FILES['wps-import-file']['type'];
                    
                    $duplicate = array();
                    
                    if ((strtolower($filename) == '.xls') && ($filetype == 'application/vnd.ms-excel')) {
                        $data = new Spreadsheet_Excel_Reader($_FILES["wps-import-file"]["tmp_name"]);
                        $get_mobile = $wpdb->get_col("SELECT `mobile` FROM {$table_prefix}smsir_subscribes");

                        foreach ($data->sheets[0]['cells'] as $items) {

                            // Check and count duplicate items
                            if (in_array($items[2], $get_mobile)) {
                                $duplicate = $items[2];
                                continue;
                            }

                            // Count submited items.
                            $total_submit[] = $data->sheets[0]['cells'];
                            date_default_timezone_set('Asia/Tehran');
                            $result = $wpdb->insert(
                                "{$table_prefix}smsir_subscribes",
                                array(
                                    'date' => date('Y-m-d H:i:s', current_time('timestamp', 0)),
                                    'name' => $items[1],
                                    'mobile' => $items[2],
                                    'status' => '1',
                                    'group_ID' => $group
                                )
                            );
                        }

                        if ($result)
                            echo "<div class='updated'><p>" . sprintf(__('<strong>%s</strong> items was successfully added.', 'wordpress_smsir'), count($total_submit)) . "</div></p>";

                        if ($duplicate)
                            echo "<div class='error'><p>" . sprintf(__('<strong>%s</strong> Mobile numbers Was repeated.', 'wordpress_smsir'), count($duplicate)) . "</div></p>";
                    } else {
                        echo "<div class='error'><p>" . __('Only xls Format Supported', 'wordpress_smsir') . "</div></p>";
                    }
                } else {
                    echo "<div class='error'><p>" . __('Please complete all fields', 'wordpress_smsir') . "</div></p>";
                }
            }

            include_once dirname(__FILE__) . "/includes/templates/settings/import.php";

        } else if ($_GET['action'] == 'export') {
            include_once dirname(__FILE__) . "/includes/templates/settings/export.php";
        } else {
            include_once dirname(__FILE__) . "/includes/templates/settings/subscribes.php";
        }
    } else {
        include_once dirname(__FILE__) . "/includes/templates/settings/subscribes.php";
    }
}

/**
 * Creating Setting Page.
 *
 * @return void
 */
function Wordpress_Smsir_Setting_page()
{
    global $sms;

    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));

        settings_fields('wordpress_smsir_options');
    }

    wp_enqueue_style('css', plugin_dir_url(__FILE__) . 'assets/css/style.css', true, '1.0');

    $sms_page['about'] = get_bloginfo('url') . "/wp-admin/admin.php?page=wordpress_smsir/about";

    if (isset($_GET['tab'])) {
        switch($_GET['tab']) {
        case 'web-service':
            wp_enqueue_style('chosen', plugin_dir_url(__FILE__) . 'assets/css/chosen.min.css', true, '1.2.0');
            wp_enqueue_script('chosen', plugin_dir_url(__FILE__) . 'assets/js/chosen.jquery.min.js', true, '1.2.0');

            if (isset($_GET['action']) == 'reset') {
                delete_option('wordpress_smsir_webservice');
                echo '<meta http-equiv="refresh" content="0; url=admin.php?page=wordpress_smsir/setting&tab=web-service" />';
            }
            include_once dirname(__FILE__) . "/includes/templates/settings/web-service.php";

            if(get_option('wordpress_smsir_webservice'))
                update_option('wordpress_smsir_last_credit', $sms->getCredit());

            break;
        case 'newsletter':
            include_once dirname(__FILE__) . "/includes/templates/settings/newsletter.php";
            break;
        case 'features':
            include_once dirname(__FILE__) . "/includes/templates/settings/features.php";
            break;
        case 'notification':
            include_once dirname(__FILE__) . "/includes/templates/settings/notification.php";
            break;
        }
    } else {
        include_once dirname(__FILE__) . "/includes/templates/settings/setting.php";
    }
}

/**
 * Creating About Page.
 *
 * @return void
 */
function Wordpress_Smsir_About_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    include_once dirname(__FILE__) . "/includes/templates/settings/about.php";
}

require_once dirname(__FILE__) . '/includes/admin/wordpress_smsir-newslleter.php';
require_once dirname(__FILE__) . '/includes/admin/wordpress_smsir-features.php';
require_once dirname(__FILE__) . '/includes/admin/wordpress_smsir-notifications.php';

