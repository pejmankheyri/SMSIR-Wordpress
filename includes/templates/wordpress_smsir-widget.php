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

<p>
    <?php _e('Name', 'wordpress_smsir'); ?>:<br />
    <input id="wordpress_smsir_widget_name" name="wordpress_smsir_widget_name" type="text" value="<?php echo get_option('wordpress_smsir_widget_name'); ?>" />
</p>

<input type="hidden" id="wordpress_smsir_submit_widget" name="wordpress_smsir_submit_widget" value="1" />