<?php

/**
 * Notification Template Page
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
        jQuery('#wordpress_smsir_subscribes_send').click(function() {
            jQuery('#wp_subscribes_stats').fadeToggle();
        });
        jQuery('#wpsms-nrnu-stats').click(function() {
            jQuery('#wpsms-nrnu').fadeToggle();
            jQuery('#wpsms-verify-config-div').fadeToggle();
        });
        jQuery('#wpsms-gnc-stats').click(function() {
            jQuery('#wpsms-gnc').fadeToggle();
        });
        jQuery('#wpsms-ul-stats').click(function() {
            jQuery('#wpsms-ul').fadeToggle();
        });
        jQuery('#wpsms-wc-no-stats').click(function() {
            jQuery('#wpsms-wc-no').fadeToggle();
        });
        jQuery('#wordpress_smsir_add_wpcf7').click(function() {
            jQuery('#wpsms-cf7-no').fadeToggle();
        });
        jQuery('#wpsms-edd-no-stats').click(function() {
            jQuery('#wpsms-edd-no').fadeToggle();
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
    <table class="form-table">
        <form method="post" action="options.php" name="form">
            <?php wp_nonce_field('update-options');?>
            <tr valign="top">
                <th scope="row" colspan="2"><h3><?php _e('Wordpress Notifications', 'wordpress_smsir'); ?></h3></th>
            </tr>
            <tr>
                <th><?php _e('Published new posts', 'wordpress_smsir'); ?></th>
                <td>
                    <input type="checkbox" name="wordpress_smsir_subscribes_send" id="wordpress_smsir_subscribes_send" <?php echo get_option('wordpress_smsir_subscribes_send') ==true? 'checked="checked"':'';?>/>
                    <label for="wordpress_smsir_subscribes_send"><?php _e('Active', 'wordpress_smsir'); ?></label>
                    <p class="description"><?php _e('Send a sms to subscribers When published new posts.', 'wordpress_smsir'); ?></p>
                </td>
            </tr>
            <?php 
            if (get_option('wordpress_smsir_subscribes_send')) { 
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
                    <textarea id="wpsms-text-template" cols="50" rows="7" name="wordpress_smsir_text_template"><?php echo get_option('wordpress_smsir_text_template'); ?></textarea>
                    <p class="description"><?php _e('Enter the contents of the sms message.', 'wordpress_smsir'); ?></p>
                    <p class="description data">
                        <?php _e('Input data:', 'wordpress_smsir'); ?>
                        <?php _e('Title post', 'wordpress_smsir'); ?>: <code>%title_post%</code>
                        <?php _e('URL post', 'wordpress_smsir'); ?>: <code>%url_post%</code>
                        <?php _e('Date post', 'wordpress_smsir'); ?>: <code>%date_post%</code>
                    </p>
                </td>
            </tr>
            <tr>
                <th><?php _e('The new release of WordPress', 'wordpress_smsir'); ?></th>
                <td>
                    <input type="checkbox" name="wordpress_smsir_notification_new_wp_version" id="wordpress_smsir_notification_new_wp_version" <?php echo get_option('wordpress_smsir_notification_new_wp_version') ==true? 'checked="checked"':'';?>/>
                    <label for="wordpress_smsir_notification_new_wp_version"><?php _e('Active', 'wordpress_smsir'); ?></label>
                    <p class="description"><?php _e('Send a sms to you When the new release of WordPress.', 'wordpress_smsir'); ?></p>
                </td>
            </tr>
            <tr>
                <th><?php _e('Register a new user', 'wordpress_smsir'); ?></th>
                <td>
                    <input type="checkbox" name="wordpress_smsir_nrnu_stats" id="wpsms-nrnu-stats" <?php echo get_option('wordpress_smsir_nrnu_stats') ==true? 'checked="checked"':'';?>/>
                    <label for="wpsms-nrnu-stats"><?php _e('Active', 'wordpress_smsir'); ?></label>
                    <p class="description"><?php _e('Send a sms to you and user when register on wordpress.', 'wordpress_smsir'); ?></p>
                    <?php 
                    if (get_option('wordpress_smsir_nrnu_stats')) { 
                        $hidden=""; 
                    } else { 
                        $hidden=" style='display: none;'"; 
                    }
                    ?>
                    <div id="wpsms-verify-config-div" <?php echo $hidden;?>>
                        <input type="checkbox" name="wordpress_smsir_verify_config" id="wpsms-verify-config" <?php echo get_option('wordpress_smsir_verify_config') ==true? 'checked="checked"':'';?>/>
                        <label for="wpsms-verify-config"><?php _e('Send Verification Code', 'wordpress_smsir'); ?></label>
                        <p class="description"><?php _e('Sends Verification Code for users validation.', 'wordpress_smsir'); ?></p>
                    </div>
                </td>
            </tr>
            <?php 
            if (get_option('wordpress_smsir_nrnu_stats')) { 
                $hidden=""; 
            } else { 
                $hidden=" style='display: none;'"; 
            }
            ?>
            <tr valign="top"<?php echo $hidden;?> id="wpsms-nrnu">
                <td scope="row">
                    <label for="wpsms-nrnu-tt"><?php _e('Text template', 'wordpress_smsir'); ?>:</label>
                </th>
                <td>
                    <p><?php _e('For users:', 'wordpress_smsir'); ?></p>
                    <textarea id="wpsms-nrnu-tt" cols="50" rows="7" name="wordpress_smsir_narnu_tt"><?php echo get_option('wordpress_smsir_narnu_tt'); ?></textarea>
                    <p class="description"><?php _e('Enter the contents of the sms message.', 'wordpress_smsir'); ?></p>
                    <p class="description data">
                        <?php _e('Input data:', 'wordpress_smsir'); ?>
                        <?php _e('User name', 'wordpress_smsir'); ?>: <code>%user_login%</code>
                        <?php _e('User email', 'wordpress_smsir'); ?>: <code>%user_email%</code>
                        <?php _e('Date register', 'wordpress_smsir'); ?>: <code>%date_register%</code>
                    </p>
                    <p><?php _e('For admin:', 'wordpress_smsir'); ?></p>
                    <textarea id="wpsms-nrnu-tt" cols="50" rows="7" name="wordpress_smsir_nrnu_tt"><?php echo get_option('wordpress_smsir_nrnu_tt'); ?></textarea>
                    <p class="description"><?php _e('Enter the contents of the sms message.', 'wordpress_smsir'); ?></p>
                    <p class="description data">
                        <?php _e('Input data:', 'wordpress_smsir'); ?>
                        <?php _e('User name', 'wordpress_smsir'); ?>: <code>%user_login%</code>
                        <?php _e('User email', 'wordpress_smsir'); ?>: <code>%user_email%</code>
                        <?php _e('Date register', 'wordpress_smsir'); ?>: <code>%date_register%</code>
                    </p>
                </td>
            </tr>
            <tr>
                <th><?php _e('New comment', 'wordpress_smsir'); ?></th>
                <td>
                    <input type="checkbox" name="wordpress_smsir_gnc_stats" id="wpsms-gnc-stats" <?php echo get_option('wordpress_smsir_gnc_stats') ==true? 'checked="checked"':'';?>/>
                    <label for="wpsms-gnc-stats"><?php _e('Active', 'wordpress_smsir'); ?></label>
                    <p class="description"><?php _e('Send a sms to you When get a new comment.', 'wordpress_smsir'); ?></p>
                </td>
            </tr>
            <?php 
            if (get_option('wordpress_smsir_gnc_stats')) { 
                $hidden=""; 
            } else { 
                $hidden=" style='display: none;'"; 
            }
            ?>
            <tr valign="top"<?php echo $hidden;?> id="wpsms-gnc">
                <td scope="row">
                    <label for="wpsms-gnc-tt"><?php _e('Text template', 'wordpress_smsir'); ?>:</label>
                </th>
                <td>
                    <textarea id="wpsms-gnc-tt" cols="50" rows="7" name="wordpress_smsir_gnc_tt"><?php echo get_option('wordpress_smsir_gnc_tt'); ?></textarea>
                    <p class="description"><?php _e('Enter the contents of the sms message.', 'wordpress_smsir'); ?></p>
                    <p class="description data">
                        <?php _e('Input data:', 'wordpress_smsir'); ?>
                        <?php _e('Comment author', 'wordpress_smsir'); ?>: <code>%comment_author%</code>
                        <?php _e('Comment author email', 'wordpress_smsir'); ?>: <code>%comment_author_email%</code>
                        <?php _e('Comment author url', 'wordpress_smsir'); ?>: <code>%comment_author_url%</code>
                        <?php _e('Comment author IP', 'wordpress_smsir'); ?>: <code>%comment_author_IP%</code>
                        <?php _e('Comment date', 'wordpress_smsir'); ?>: <code>%comment_date%</code>
                        <?php _e('Comment content', 'wordpress_smsir'); ?>: <code>%comment_content%</code>
                    </p>
                </td>
            </tr>
            <tr>
                <th><?php _e('User login', 'wordpress_smsir'); ?></th>
                <td>
                    <input type="checkbox" name="wordpress_smsir_ul_stats" id="wpsms-ul-stats" <?php echo get_option('wordpress_smsir_ul_stats') ==true? 'checked="checked"':'';?>/>
                    <label for="wpsms-ul-stats"><?php _e('Active', 'wordpress_smsir'); ?></label>
                    <p class="description"><?php _e('Send a sms to you When user is login.', 'wordpress_smsir'); ?></p>
                </td>
            </tr>
            <?php 
            if (get_option('wordpress_smsir_ul_stats')) { 
                $hidden=""; 
            } else { 
                $hidden=" style='display: none;'"; 
            }
            ?>
            <tr valign="top"<?php echo $hidden;?> id="wpsms-ul">
                <td scope="row">
                    <label for="wpsms-ul-tt"><?php _e('Text template', 'wordpress_smsir'); ?>:</label>
                </th>
                <td>
                    <textarea id="wpsms-ul-tt" cols="50" rows="7" name="wordpress_smsir_ul_tt"><?php echo get_option('wordpress_smsir_ul_tt'); ?></textarea>
                    <p class="description"><?php _e('Enter the contents of the sms message.', 'wordpress_smsir'); ?></p>
                    <p class="description data">
                        <?php _e('Input data:', 'wordpress_smsir'); ?>
                        <?php _e('User name', 'wordpress_smsir'); ?>: <code>%user_login%</code>
                        <?php _e('Display name', 'wordpress_smsir'); ?>: <code>%display_name%</code>
                        <?php _e('User email', 'wordpress_smsir'); ?>: <code>%user_email%</code>
                        <?php _e('Date register', 'wordpress_smsir'); ?>: <code>%user_registered%</code>
                    </p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" colspan="2"><h3><?php _e('Contact form 7 plugin', 'wordpress_smsir'); ?></h3></th>
            </tr>
            <tr>
                <th><?php _e('SMS meta box', 'wordpress_smsir'); ?></th>
                <td>
                    <input type="checkbox" name="wordpress_smsir_add_wpcf7" id="wordpress_smsir_add_wpcf7" <?php echo get_option('wordpress_smsir_add_wpcf7') ==true? 'checked="checked"':'';?>/>
                    <label for="wordpress_smsir_add_wpcf7"><?php _e('Active', 'wordpress_smsir'); ?></label>
                    <p class="description"><?php _e('Added Wordpress SMS meta box to Contact form 7 plugin when enable this option.', 'wordpress_smsir'); ?></p>
                </td>
            </tr>
            <?php 
            if (get_option('wordpress_smsir_add_wpcf7')) { 
                $hidden=""; 
            } else { 
                $hidden=" style='display: none;'"; 
            }
            ?>
            <tr valign="top"<?php echo $hidden;?> id="wpsms-cf7-no">
                <td scope="row">
                    <label for="wpsms-cf7-no-tt"><?php _e('Text template', 'wordpress_smsir'); ?>:</label>
                </td>
                <td>
                    <textarea id="wpsms-cf7-no-tt" cols="50" rows="7" name="wordpress_smsir_cf7_no_tt"><?php echo get_option('wordpress_smsir_cf7_no_tt'); ?></textarea>
                    <p class="description"><?php _e('Enter the contents of the sms message.', 'wordpress_smsir'); ?></p>
                    <p class="description data">
                        <?php _e('Input data:', 'wordpress_smsir'); ?>
                        <?php _e('Form ID', 'wordpress_smsir'); ?>: <code>%form_id%</code>
                        <?php _e('Form Title', 'wordpress_smsir'); ?>: <code>%form_title%</code>
                    </p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" colspan="2"><h3><?php _e('WooCommerce plugin', 'wordpress_smsir'); ?></h3></th>
            </tr>
            <tr>
                <th><?php _e('New order', 'wordpress_smsir'); ?></th>
                <td>
                    <input type="checkbox" name="wordpress_smsir_wc_no_stats" id="wpsms-wc-no-stats" <?php echo get_option('wordpress_smsir_wc_no_stats') ==true? 'checked="checked"':'';?>/>
                    <label for="wpsms-wc-no-stats"><?php _e('Active', 'wordpress_smsir'); ?></label>
                    <p class="description"><?php _e('Send a sms to you When get new order.', 'wordpress_smsir'); ?></p>
                </td>
            </tr>
            <?php 
            if (get_option('wordpress_smsir_wc_no_stats')) { 
                $hidden=""; 
            } else { 
                $hidden=" style='display: none;'"; 
            }
            ?>
            <tr valign="top"<?php echo $hidden;?> id="wpsms-wc-no">
                <td scope="row">
                    <label for="wpsms-wc-no-tt"><?php _e('Text template', 'wordpress_smsir'); ?>:</label>
                </th>
                <td>
                    <textarea id="wpsms-wc-no-tt" cols="50" rows="7" name="wordpress_smsir_wc_no_tt"><?php echo get_option('wordpress_smsir_wc_no_tt'); ?></textarea>
                    <p class="description"><?php _e('Enter the contents of the sms message.', 'wordpress_smsir'); ?></p>
                    <p class="description data">
                        <?php _e('Input data:', 'wordpress_smsir'); ?>
                        <?php _e('Order ID', 'wordpress_smsir'); ?>: <code>%order_id%</code>
                    </p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" colspan="2"><h3><?php _e('Easy Digital Downloads plugin', 'wordpress_smsir'); ?></h3></th>
            </tr>
            <tr>
                <th><?php _e('New order', 'wordpress_smsir'); ?></th>
                <td>
                    <input type="checkbox" name="wordpress_smsir_edd_no_stats" id="wpsms-edd-no-stats" <?php echo get_option('wordpress_smsir_edd_no_stats') ==true? 'checked="checked"':'';?>/>
                    <label for="wpsms-edd-no-stats"><?php _e('Active', 'wordpress_smsir'); ?></label>
                    <p class="description"><?php _e('Send a sms to you When get new order.', 'wordpress_smsir'); ?></p>
                </td>
            </tr>
            <?php 
            if (get_option('wordpress_smsir_edd_no_stats')) { 
                $hidden=""; 
            } else { 
                $hidden=" style='display: none;'"; 
            }
            ?>
            <tr valign="top"<?php echo $hidden;?> id="wpsms-edd-no">
                <td scope="row">
                    <label for="wpsms-edd-no-tt"><?php _e('Text template', 'wordpress_smsir'); ?>:</label>
                </th>
                <td>
                    <textarea id="wpsms-edd-no-tt" cols="50" rows="7" name="wordpress_smsir_edd_no_tt"><?php echo get_option('wordpress_smsir_edd_no_tt'); ?></textarea>
                    <p class="description"><?php _e('Enter the contents of the sms message.', 'wordpress_smsir'); ?></p>
                </td>
            </tr>
            <tr>
                <th><?php _e('Input Datas Guidance', 'wordpress_smsir'); ?></th>
                <td>
                    <p class="description" style="color: red;"><?php _e('Input Datas Guidance Description', 'wordpress_smsir'); ?>.</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="submit">
                        <input type="hidden" name="action" value="update" />
                        <input type="hidden" name="page_options" value="wordpress_smsir_subscribes_send,wordpress_smsir_text_template,wordpress_smsir_notification_new_wp_version,wordpress_smsir_nrnu_stats,wordpress_smsir_verify_config,wordpress_smsir_nrnu_tt,wordpress_smsir_narnu_tt,wordpress_smsir_gnc_stats,wordpress_smsir_gnc_tt,wordpress_smsir_ul_stats,wordpress_smsir_ul_tt,wordpress_smsir_add_wpcf7,wordpress_smsir_cf7_no_tt,wordpress_smsir_wc_no_stats,wordpress_smsir_wc_no_tt,wordpress_smsir_edd_no_stats,wordpress_smsir_edd_no_tt" />
                        <input type="submit" class="button-primary" name="Submit" value="<?php _e('Update', 'wordpress_smsir'); ?>" />
                    </p>
                </td>
            </tr>
        </form>
    </table>
</div>