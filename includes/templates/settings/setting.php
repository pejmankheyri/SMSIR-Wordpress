<?php

/**
 * Setting Template Page
 * 
 * PHP version 5.6.x | 7.x | 8.x
 * 
 * @category  PLugins
 * @package   Wordpress
 * @author    Pejman Kheyri <pejmankheyri@gmail.com>
 * @copyright 2021 All rights reserved.
 */

?>

<div class="wrap">
    <h2 class="nav-tab-wrapper">
        <a href="?page=wordpress_smsir/setting" class="nav-tab
        <?php 
        if (isset($_GET['tab']) == '') { 
            echo " nav-tab-active";
        } 
        ?>
        "><?php _e('General', 'wordpress_smsir'); ?></a>
        <a href="?page=wordpress_smsir/setting&tab=web-service" class="nav-tab
        <?php 
        if (isset($_GET['tab']) == 'web-service') { 
            echo " nav-tab-active"; 
        } 
        ?>
        "><?php _e('Web Service', 'wordpress_smsir'); ?></a>
        <a href="?page=wordpress_smsir/setting&tab=newsletter" class="nav-tab
        <?php 
        if (isset($_GET['tab']) == 'newsletter') { 
            echo " nav-tab-active"; 
        } 
        ?>
        "><?php _e('Newsletter', 'wordpress_smsir'); ?></a>
        <a href="?page=wordpress_smsir/setting&tab=features" class="nav-tab
        <?php 
        if (isset($_GET['tab']) == 'features') { 
            echo " nav-tab-active"; 
        } 
        ?>
        "><?php _e('Features', 'wordpress_smsir'); ?></a>
        <a href="?page=wordpress_smsir/setting&tab=notification" class="nav-tab
        <?php 
        if (isset($_GET['tab']) == 'notification') { 
            echo " nav-tab-active"; 
        } 
        ?>
        "><?php _e('Notification', 'wordpress_smsir'); ?></a>
    </h2>
    <table class="form-table">
        <form method="post" action="options.php" name="form">
            <?php wp_nonce_field('update-options');?>
            <tr>
                <td><?php _e('Your Mobile Number', 'wordpress_smsir'); ?>:</td>
                <td>
                    <input type="text" dir="ltr" id="wpsms_admin_mobile" name="wordpress_smsir_admin_mobile" value="<?php echo get_option('wordpress_smsir_admin_mobile'); ?>"/>
                </td>
            </tr>
            <tr>
                <td><?php _e('Your mobile country code', 'wordpress_smsir'); ?>:</td>
                <td>
                    <input type="text" dir="ltr" id="wpsms_admin_mobile" name="wordpress_smsir_mcc" value="<?php echo get_option('wordpress_smsir_mcc'); ?>"/>
                    <p class="description"><?php _e('Enter your mobile country code. (For example: Iran 09, Australia 61)', 'wordpress_smsir'); ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="submit">
                        <input type="hidden" name="action" value="update" />
                        <input type="hidden" name="page_options" value="wordpress_smsir_admin_mobile,wordpress_smsir_mcc" />
                        <input type="submit" class="button-primary" name="Submit" value="<?php _e('Update', 'wordpress_smsir'); ?>" />
                    </p>
                </td>
            </tr>
        </form>
    </table>
</div>