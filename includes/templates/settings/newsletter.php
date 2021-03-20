<?php

/**
 * Newsletter Template Page
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
    jQuery(document).ready(function() {
        jQuery('#wordpress_smsir_subscribes_send_sms').click(function() {
            jQuery('#wp_subscribes_stats').fadeToggle();
        });
    });
</script>

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
    <p><?php _e('You Can Use Newsletter Feature From Appearance -> Widgets By Adding The Widget To Your Theme', 'wordpress_smsir'); ?></p>
    <table class="form-table">
        <form method="post" action="options.php" name="form">
            <?php wp_nonce_field('update-options');?>
            <tr>
                <th><?php _e('Register?', 'wordpress_smsir'); ?></th>
                <td>
                    <input type="checkbox" name="wordpress_smsir_subscribes_status" id="wordpress_smsir_subscribes_status" <?php echo get_option('wordpress_smsir_subscribes_status') ==true? 'checked="checked"':'';?>/>
                    <label for="wordpress_smsir_subscribes_status"><?php _e('Active', 'wordpress_smsir'); ?></label>
                </td>
            </tr>
            <tr>
                <th><?php _e('Verified with the activation code?', 'wordpress_smsir'); ?></th>
                <td>
                    <input type="checkbox" name="wordpress_smsir_subscribes_activation" id="wordpress_smsir_subscribes_activation" <?php echo get_option('wordpress_smsir_subscribes_activation') ==true? 'checked="checked"':'';?>/>
                    <label for="wordpress_smsir_subscribes_activation"><?php _e('Active', 'wordpress_smsir'); ?></label>
                </td>
            </tr>
            <tr>
                <th><?php _e('Send SMS?', 'wordpress_smsir'); ?></th>
                <td>
                    <input type="checkbox" name="wordpress_smsir_subscribes_send_sms" id="wordpress_smsir_subscribes_send_sms" <?php echo get_option('wordpress_smsir_subscribes_send_sms') ==true? 'checked="checked"':'';?>/>
                    <label for="wordpress_smsir_subscribes_send_sms"><?php _e('Active', 'wordpress_smsir'); ?></label>
                    <p class="description"><?php _e('Send a sms to subscriber when register.', 'wordpress_smsir'); ?></p>
                </td>
            </tr>
            <?php 
            if (get_option('wordpress_smsir_subscribes_send_sms')) { 
                $hidden=""; 
            } else { 
                $hidden=" style='display: none;'"; 
            }
            ?>
            <tr valign="top"<?php echo $hidden;?> id='wp_subscribes_stats'>
                <td scope="row">
                    <label for="wpsms-text-template"><?php _e('Text template', 'wordpress_smsir'); ?>:</label>
                </th>
                <td>
                    <textarea id="wpsms-text-template" cols="50" rows="7" name="wordpress_smsir_subscribes_text_send"><?php echo get_option('wordpress_smsir_subscribes_text_send'); ?></textarea>
                    <p class="description"><?php _e('Enter the contents of the sms message.', 'wordpress_smsir'); ?></p>
                    <p class="description data">
                        <?php _e('Input data:', 'wordpress_smsir'); ?>
                        <?php _e('Subscribe name', 'wordpress_smsir'); ?>: <code>%subscribe_name%</code>
                        <?php _e('Subscribe mobile', 'wordpress_smsir'); ?>: <code>%subscribe_mobile%</code>
                    </p>
                </td>
            </tr>
            <tr>
                <th><?php _e('Calling jQuery in Wordpress?', 'wordpress_smsir'); ?></th>
                <td>
                    <input type="checkbox" name="wordpress_smsir_call_jquery" id="wordpress_smsir_call_jquery" <?php echo get_option('wordpress_smsir_call_jquery') ==true? 'checked="checked"':'';?>/>
                    <label for="wordpress_smsir_call_jquery"><?php _e('Active', 'wordpress_smsir'); ?></label>
                    <p class="description">(<?php _e('Enable this option with JQuery is called in the theme', 'wordpress_smsir'); ?>)</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="submit">
                        <input type="hidden" name="action" value="update" />
                        <input type="hidden" name="page_options" value="wordpress_smsir_subscribes_status,wordpress_smsir_subscribes_activation,wordpress_smsir_subscribes_send_sms,wordpress_smsir_subscribes_text_send,wordpress_smsir_subscribes_send,wordpress_smsir_call_jquery" />
                        <input type="submit" class="button-primary" name="Submit" value="<?php _e('Update', 'wordpress_smsir'); ?>" />
                    </p>
                </td>
            </tr>
        </form>
    </table>
</div>