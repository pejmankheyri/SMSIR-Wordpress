<?php

/**
 * Web Service Template Page
 * 
 * PHP version 5.6.x | 7.x | 8.x
 * 
 * @category  PLugins
 * @package   Wordpress
 * @author    Pejman Kheyri <pejmankheyri@gmail.com>
 * @copyright 2021 All rights reserved.
 */

?>

<script type="text/javascript">
    function openwin() {
        var url=document.form.wordpress_smsir_webservice.value;
        if(url==1) {
            document.location.href="<?php echo $sms_page['about']; ?>";
        }
    }

    jQuery(document).ready(function(){
        jQuery(".chosen-select").chosen();

        jQuery("#wps_reset").click(function(){
            if(confirm('<?php _e('Your Web service data will be deleted. Are you sure?', 'wordpress_smsir'); ?>')) {
                return true;
            } else {
                return false;
            }
        });
    });
</script>

<style>
    p.register{
        float: <?php echo is_rtl() == true? "right":"left"; ?>
    }
</style>

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

    <form method="post" action="options.php" name="form">
        <table class="form-table">
            <?php wp_nonce_field('update-options');?>
            <tr class="wpsms_display_none">
                <td>
                    <select name="wordpress_smsir_webservice" id="wp-webservice">
                        <option value="Smsir" selected="selected">sms.ir</option>
                    </select>
                </td>
            </tr>

            <?php //if(get_option('wordpress_smsir_webservice')) { ?>

            <tr>
                <th><?php _e('ApiDomain', 'wordpress_smsir'); ?>:</th>
                <td>
                    <input type="text" dir="ltr" style="width: 200px;" name="wordpress_smsir_apidomain" value="<?php echo get_option('wordpress_smsir_apidomain'); ?>"/>
                    <p class="description"><?php _e('Api Domain Desc', 'wordpress_smsir'); ?></p>
                </td>
            </tr>

            <tr>
                <th><?php _e('Username', 'wordpress_smsir'); ?>:</th>
                <td>
                    <input type="text" dir="ltr" id="wpsms_admin_mobile" name="wordpress_smsir_username" value="<?php echo get_option('wordpress_smsir_username'); ?>"/>
                    <p class="description"><?php _e('Your username in', 'wordpress_smsir'); ?>: <?php echo $sms->panel; ?></p>
                    <?php if (!get_option('wordpress_smsir_username')) { ?>
                        <p class="register"><?php echo sprintf(__('If you do not have a username for this service <a href="%s">click here..</a>', 'wordpress_smsir'), $sms->tariff) ?>.</p>
                    <?php } ;?>
                </td>
            </tr>
            <tr>
                <th><?php _e('Password', 'wordpress_smsir'); ?>:</th>
                <td>
                    <input type="password" dir="ltr" id="wpsms_admin_mobile" name="wordpress_smsir_password" value="<?php echo get_option('wordpress_smsir_password'); ?>"/>
                    <p class="description"><?php _e('Your password in', 'wordpress_smsir'); ?>: <?php echo $sms->panel; ?></p>
                    <?php 
                    if (!get_option('wordpress_smsir_password')) { 
                        ?>
                        <p class="register"><?php echo sprintf(__('If you do not have a password for this service <a href="%s">click here..</a>', 'wordpress_smsir'), $sms->tariff) ?>.</p>
                    <?php } ?>
                </td>
            </tr>
            <?php if ($sms->has_key) { ?>
            <tr>
                <th><?php _e('API/Key', 'wordpress_smsir'); ?>:</th>
                <td>
                    <input type="text" dir="ltr" id="wpsms_admin_mobile" name="wordpress_smsir_key" value="<?php echo get_option('wordpress_smsir_key'); ?>"/>
                    <p class="description"><?php _e('Your API Key in', 'wordpress_smsir'); ?>: <?php echo get_option('wordpress_smsir_webservice'); ?></p>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <th><?php _e('Number', 'wordpress_smsir'); ?>:</th>
                <td>
                    <input type="text" dir="ltr" id="wpsms_admin_mobile" name="wordpress_smsir_number" value="<?php echo get_option('wordpress_smsir_number'); ?>"/>
                    <p class="description"><?php _e('Your SMS sender number in', 'wordpress_smsir'); ?>: <?php echo $sms->panel; ?></p>
                    <input type="checkbox" name="wordpress_smsir_stcc_number" id="wpsms-stcc-number" <?php echo get_option('wordpress_smsir_stcc_number') ==true? 'checked="checked"':'';?>/>
                    <label for="wpsms-stcc-number"><?php _e('Activate if number is customer clubs number', 'wordpress_smsir'); ?>.</label>
                </td>
            </tr>
            <?php if ($sms->getCredit() > 0) { ?>
            <tr>
                <th><?php _e('Status', 'wordpress_smsir'); ?>:</th>
                <td>
                    <img src="<?php echo WORDPRESS_SMSIR_DIR_PLUGIN; ?>assets/images/1.png" alt="Active" align="absmiddle"/><span id="wpsms_bold_style"><?php _e('Active', 'wordpress_smsir'); ?></span>
                </td>
            </tr>
            <tr>
                <th><?php _e('Credit', 'wordpress_smsir'); ?>:</th>
                <td>
                    <?php global $sms; echo $sms->getCredit() . " " . $sms->unit; ?>
                </td>
            </tr>
            <?php } else { ?>
            <tr>
                <th><?php _e('Status', 'wordpress_smsir'); ?>:</th>
                <td>
                    <img src="<?php echo WORDPRESS_SMSIR_DIR_PLUGIN; ?>assets/images/0.png" alt="Deactive" align="absmiddle"/><span id="wpsms_bold_style"><?php _e('Deactive', 'wordpress_smsir'); ?></span>
                </td>
            </tr>
            <?php } ?>
            <?php //} ?>
            <tr>
                <td>
                    <p class="submit">
                        <input type="hidden" name="action" value="update" />
                        <input type="hidden" name="page_options" value="wordpress_smsir_webservice,wordpress_smsir_apidomain,wordpress_smsir_username,wordpress_smsir_password,wordpress_smsir_key,wordpress_smsir_number,wordpress_smsir_stcc_number" />
                        <input type="submit" class="button-primary" name="Submit" value="<?php _e('Update', 'wordpress_smsir'); ?>" />
                    </p>
                </td>
            </tr>
        </table>
    </form>
</div>