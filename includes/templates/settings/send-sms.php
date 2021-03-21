<?php

/**
 * Send SMS Template Page
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
    var boxId2 = 'wp_get_message';
    var counter = 'wp_counter';
    var part = 'wp_part';
    var max = 'wp_max';
    function charLeft2() {
        checkSMSLength(boxId2, counter, part, max);
    }

    jQuery(document).ready(function(){
        jQuery("select#select_sender").change(function(){
            var get_method = "";
            jQuery("select#select_sender option:selected").each(
                function(){
                    get_method += jQuery(this).attr('id');
                }
            );
            if(get_method == 'wp_tellephone'){
                jQuery("#wpsms_group_name").hide();
                jQuery("#wp_customer_club_contacts_desc").hide();
                jQuery("#show_linenumber").fadeIn();
                jQuery("#wp_get_numbers").fadeIn();
                jQuery("#wp_get_number").focus();
            } else {
                jQuery("#wp_get_numbers").hide();
                jQuery("#wp_customer_club_contacts_desc").hide();
                jQuery("#show_linenumber").fadeIn();
                jQuery("#wpsms_group_name").fadeIn();
                jQuery("#wpsms_group_name").focus();
            }
            if(get_method == 'wp_customer_club_contacts'){
                jQuery("#wpsms_group_name").hide();
                jQuery("#wp_get_numbers").hide();
                jQuery("#show_linenumber").hide();
                jQuery("#wp_customer_club_contacts_desc").fadeIn();
                jQuery("#wp_get_message").focus();
            } 
        });
        charLeft2();
        jQuery("#" + boxId2).bind('keyup', function() {
            charLeft2();
        });
        jQuery("#" + boxId2).bind('keydown', function() {
            charLeft2();
        });
        jQuery("#" + boxId2).bind('paste', function(e) {
            charLeft2();
        });
    });
</script>

<div class="wrap">
    <h2><?php _e('Send SMS', 'wordpress_smsir'); ?></h2>
    <?php
    global $sms, $wpdb, $table_prefix, $date;
    if (get_option('wordpress_smsir_webservice')) {
        update_option('wordpress_smsir_last_credit', $sms->getCredit());
        if ($sms->getCredit()) {
            ?>
            <form method="post" action="">
            <table class="form-table">
                <tr>
                    <td colspan="2">
            <?php
            if (isset($_POST['SendSMS'])) {
                if ($_POST['wp_get_message']) {
                    if ($_POST['wp_send_to'] == "wp_subscribe_username") {
                        if (in_array('all', $_POST['wpsms_group_name'])) {
                            //$sms->to = $wpdb->get_col("SELECT mobile FROM {$table_prefix}smsir_subscribes WHERE `status` = '1'");
                            $numbers = $wpdb->get_col("SELECT mobile FROM {$table_prefix}smsir_subscribes WHERE `status` = '1'");
                        } else {
                            $wp_groups = $_POST['wpsms_group_name'];
                            foreach ($wp_groups as $key_wp_groups=>$val_wp_groups) {
                                $sm = $wpdb->get_col("SELECT mobile FROM {$table_prefix}smsir_subscribes WHERE `status` = '1' AND `group_ID` REGEXP '".$val_wp_groups."'");
                                foreach ($sm as $key_sm=>$val_sm) {
                                    $arrays[] = $val_sm;
                                }
                            }
                            $numbers = array_unique($arrays);
                        }
                        $sms->to = $numbers;
                    } else if ($_POST['wp_send_to'] == "wp_tellephone") {
                        $sms->to = explode(",", $_POST['wp_get_number']);
                    } else if ($_POST['wp_send_to'] == "wp_customer_club_contacts") {
                        $message = $_POST['wp_get_message'];
                        $sendtocustomerclub = $sms->sendSMStoCustomerclubContacts($message);
                        if ($sendtocustomerclub) {
                            echo "<div class='updated'><p>" . __('SMS was sent with success', 'wordpress_smsir') . "</p></div>";
                            update_option('wordpress_smsir_last_credit', $sms->getCredit());
                        }
                    }
                    $sms->msg = $_POST['wp_get_message'];
                    if (isset($_POST['wp_flash']) && ($_POST['wp_flash'] == "true")) {
                        $sms->isflash = true;
                    } elseif (isset($_POST['wp_flash']) && ($_POST['wp_flash'] == "false")) {
                        $sms->isflash = false;
                    }
                    if ($sms->sendSMS()) {
                        $subscribes = $wpdb->get_col("SELECT mobile FROM {$table_prefix}smsir_subscribes");

                        $to = implode(",", $subscribes);
                        echo "<div class='updated'><p>" . __('SMS was sent with success', 'wordpress_smsir') . "</p></div>";
                        update_option('wordpress_smsir_last_credit', $sms->getCredit());
                    }
                } else {
                    echo "<div class='error'><p>" . __('Please enter a message', 'wordpress_smsir') . "</p></div>";
                }
            }
            ?>
                    </td>
                </tr>
                <?php wp_nonce_field('update-options');?>
                <tr>
                    <th><h3><?php _e('Send SMS', 'wordpress_smsir'); ?></h4></th>
                </tr>
                <tr>
                    <td><?php _e('Send from', 'wordpress_smsir'); ?>:</td>
                    <td>
                    <span id="show_linenumber">
            <?php 
            echo $sms->from." "; 
            if (get_option('wordpress_smsir_stcc_number')) { 
                _e('(The number configured as a customer club number)', 'wordpress_smsir'); 
                echo "<br>";_e('Customer Club numbers sends to just one number', 'wordpress_smsir');
            } 
            ?>
                    </span>
                        <span id="wp_customer_club_contacts_desc" class="wpsms_display_none">
                            <?php _e('This option sends sms to your customer club contacts that saved in sms.ir panel.', 'wordpress_smsir'); ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><?php _e('Send to', 'wordpress_smsir'); ?>:</td>
                    <td>
                        <select name="wp_send_to" id="select_sender">
                            <?php global $wpdb, $table_prefix; ?>
                            <option value="wp_subscribe_username" id="wp_subscribe_username">
                                <?php _e('Subscribe users', 'wordpress_smsir'); ?>
                            </option>
                            <option value="wp_tellephone" id="wp_tellephone"><?php _e('Number(s)', 'wordpress_smsir'); ?></option>
                            <option value="wp_customer_club_contacts" id="wp_customer_club_contacts">
                                <?php _e('Customer club contacts', 'wordpress_smsir'); ?>
                            </option>
                        </select>
                        <div id="wpsms_group_name">
                            <?php
                                $username_active = $wpdb->query("SELECT * FROM {$table_prefix}smsir_subscribes WHERE status = '1'");
                            ?>
                            <input type="checkbox" value="all" name="wpsms_group_name[]" id="wpsms_group_name_all" />
                            <label for="wpsms_group_name_all" ><?php echo sprintf(__('All (%s subscribers active)', 'wordpress_smsir'), $username_active); ?></label><br>
                            <?php foreach($get_group_result as $items): ?>
                                <input type="checkbox" value="<?php echo $items->ID; ?>" name="wpsms_group_name[]" id="wpsms_group_name_<?php echo $items->ID; ?>" />
                                <label for="wpsms_group_name_<?php echo $items->ID; ?>" ><?php echo $items->name; ?></label><br>
                            <?php endforeach; ?>
                        </div>
                        <span id="wp_get_numbers" class="wpsms_display_none">
                            <input type="text" class="wpsms_get_number" id="wp_get_number" name="wp_get_number" value="09"/>
                            <span id="wpsms_get_number_example">
                            <?php 
                            if (!get_option('wordpress_smsir_stcc_number')) { 
                                _e('For example', 'wordpress_smsir');  
                                ?>: 09180000000,09180000001<?php 
                            } 
                            ?>
                            </span>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><?php _e('SMS', 'wordpress_smsir'); ?>:</td>
                    <td>
                        <textarea name="wp_get_message" id="wp_get_message" class="wpsms_get_message"></textarea><br />
                        <?php _e('The remaining words', 'wordpress_smsir'); ?>: <span id="wp_counter" class="number"></span>/<span id="wp_max" class="number"></span><br />
                        <span id="wp_part" class="number"></span> <?php _e('SMS', 'wordpress_smsir'); ?><br />
                        <p class="number">
                            <?php echo __('Your credit', 'wordpress_smsir') . ': ' . number_format($sms->getCredit()) . ' ' . $sms->unit; ?>
                        </p>
                    </td>
                </tr>
                <?php if ($sms->flash == "enable") { ?>
                <tr>
                    <td><?php _e('Send a Flash', 'wordpress_smsir'); ?>:</td>
                    <td>
                        <input type="radio" id="flash_yes" name="wp_flash" value="true"/>
                        <label for="flash_yes"><?php _e('Yes', 'wordpress_smsir'); ?></label>
                        <input type="radio" id="flash_no" name="wp_flash" value="false" checked="checked"/>
                        <label for="flash_no"><?php _e('No', 'wordpress_smsir'); ?></label>
                        <br />
                        <p class="description"><?php _e('Flash is possible to send messages without being asked, opens', 'wordpress_smsir'); ?></p>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <td>
                        <p class="submit">
                            <input type="submit" class="button-primary" name="SendSMS" value="<?php _e('Send SMS', 'wordpress_smsir'); ?>" />
                        </p>
                    </td>
                </tr>
            </form>
            </table>
            <?php
        } else {
            ?>
            <div class="error">
                <?php $get_bloginfo_url = get_admin_url() . "admin.php?page=wordpress_smsir/setting&tab=web-service"; ?>
                <p><?php echo sprintf(__('Please check the <a href="%s">SMS credit</a> the settings', 'wordpress_smsir'), $get_bloginfo_url); ?>.</p>
            </div>
            <?php
        }
    } else {
        ?>
        <div class="error">
            <?php $get_bloginfo_url = get_admin_url() . "admin.php?page=wordpress_smsir/setting&tab=web-service"; ?>
            <p><?php echo sprintf(__('Please check the <a href="%s">SMS credit</a> the settings', 'wordpress_smsir'), $get_bloginfo_url); ?>.</p>
        </div>
        <?php
    } ?>
</div>