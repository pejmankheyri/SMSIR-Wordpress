<?php

/**
 * Contact Form 7 Page
 * 
 * PHP version 5.6.x | 7.x | 8.x
 * 
 * @category  PLugins
 * @package   Wordpress
 * @author    Pejman Kheyri <pejmankheyri@gmail.com>
 * @copyright 2021 All rights reserved.
 */

?>

<div id="sms-sortables" class="meta-box-sortables ui-sortable">
    <div id="maildiv" class="postbox">
        <div class="handlediv" title="Click to toggle"><br/></div>
        <h3 class="hndle"><span><?php _e('SMS', 'wordpress_smsir'); ?></span></h3>
        <div class="inside">
            <div class="mail-fields">
                <div class="half-left">
                    <div class="mail-field">
                        <label for="wpcf7-sms-recipient"><?php _e('Send to', 'wordpress_smsir'); ?>:</label><br/>
                        <input type="text" id="wpcf7-sms-recipient" name="wpcf7-sms[phone]" class="wide" size="70" value="<?php echo $options['phone']; ?>"/>
                        <p class="description"><?php _e('Enter enter mobile number to receive message.', 'wordpress_smsir'); ?></p>
                    </div>
                </div>
                <div class="half-right">
                    <div class="mail-field">
                        <label for="wpcf7-mail-body"><?php _e('Message body', 'wordpress_smsir'); ?>:</label><br/>
                        <textarea id="wpcf7-mail-body" name="wpcf7-sms[message]" cols="100" rows="2"><?php echo $options['message']; ?></textarea>
                    </div>
                </div>
                <br class="clear"/>
            </div>
        </div>
    </div>
</div>