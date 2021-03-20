<?php

/**
 * Features Template Page
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
        if ($_GET['tab'] == '') { 
            echo " nav-tab-active"; 
        } 
        ?>
        "><?php _e('General', 'wordpress_smsir'); ?></a>
        <a href="?page=wordpress_smsir/setting&tab=web-service" class="nav-tab
        <?php 
        if ($_GET['tab'] == 'web-service') { 
            echo " nav-tab-active"; 
        } 
        ?>
        "><?php _e('Web Service', 'wordpress_smsir'); ?></a>
        <a href="?page=wordpress_smsir/setting&tab=newsletter" class="nav-tab
        <?php 
        if ($_GET['tab'] == 'newsletter') { 
            echo " nav-tab-active"; 
        } 
        ?>
        "><?php _e('Newsletter', 'wordpress_smsir'); ?></a>
        <a href="?page=wordpress_smsir/setting&tab=features" class="nav-tab
        <?php 
        if ($_GET['tab'] == 'features') { 
            echo " nav-tab-active"; 
        } 
        ?>
        "><?php _e('Features', 'wordpress_smsir'); ?></a>
        <a href="?page=wordpress_smsir/setting&tab=notification" class="nav-tab
        <?php 
        if ($_GET['tab'] == 'notification') { 
            echo " nav-tab-active"; 
        } 
        ?>
        "><?php _e('Notification', 'wordpress_smsir'); ?></a>
    </h2>
    <table class="form-table">
        <form method="post" action="options.php" name="form">
            <?php wp_nonce_field('update-options');?>
            <tr>
                <th><?php _e('Suggested post by SMS?', 'wordpress_smsir'); ?></th>
                <td>
                    <input type="checkbox" name="wordpress_smsir_suggestion_status" id="wordpress_smsir_suggestion_status" <?php echo get_option('wordpress_smsir_suggestion_status') ==true? 'checked="checked"':'';?>/>
                    <label for="wordpress_smsir_suggestion_status"><?php _e('Active', 'wordpress_smsir'); ?></label>
                </td>
            </tr>
            <tr>
                <th><?php _e('Add Mobile number field?', 'wordpress_smsir'); ?></th>
                <td>
                    <input type="checkbox" name="wordpress_smsir_add_mobile_field" id="wordpress_smsir_add_mobile_field" <?php echo get_option('wordpress_smsir_add_mobile_field') ==true? 'checked="checked"':'';?>/>
                    <label for="wordpress_smsir_add_mobile_field"><?php _e('Active', 'wordpress_smsir'); ?></label>
                    <p class="description"><?php _e('Add Mobile number to user profile and register form.', 'wordpress_smsir'); ?></p>
                    <p class="description"><?php _e('If this option activated users mobile numbers must be unique', 'wordpress_smsir'); ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="submit">
                        <input type="hidden" name="action" value="update" />
                        <input type="hidden" name="page_options" value="wordpress_smsir_suggestion_status,wordpress_smsir_add_mobile_field" />
                        <input type="submit" class="button-primary" name="Submit" value="<?php _e('Update', 'wordpress_smsir'); ?>" />
                    </p>
                </td>
            </tr>
        </form>
    </table>
</div>