<?php

/**
 * Tell Friend Page
 * 
 * PHP version 5.6.x | 7.x | 8.x
 * 
 * @category  PLugins
 * @package   Wordpress
 * @author    Pejman Kheyri <pejmankheyri@gmail.com>
 * @copyright 2021 All rights reserved.
 */

?>

<span id="send_friend"><?php _e('Suggested by SMS', 'wordpress_smsir'); ?></span>
<form action="" method="post" id="tell_friend_form">
    <table width="100%">
        <tr>
            <td><label for="get_name"><?php _e('Your name', 'wordpress_smsir'); ?>:</label></td>
            <td><label for="get_fname"><?php _e('Your friend name', 'wordpress_smsir'); ?>:</label></td>
            <td><label for="get_fmobile"><?php _e('Your friend mobile', 'wordpress_smsir'); ?>:</label></td>
            <td></td>
        </tr>
        <tr>
            <td><input type="text" name="get_name" id="get_name"/></td>
            <td><input type="text" name="get_fname" id="get_fname"/></td>
            <td><input type="text" name="get_fmobile" id="get_fmobile" value="<?php echo get_option('wordpress_smsir_mcc'); ?>"/></td>
            <td><input type="submit" name="send_post" value="<?php _e('Send', 'wordpress_smsir'); ?>"/></td>
        </tr>
    </table>
</form>