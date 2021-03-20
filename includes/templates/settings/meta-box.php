<?php

/**
 * MetaBox Template Page
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
    <label for="subscribe_post"><?php _e('Send this post to subscribers?', 'wordpress_smsir'); ?></label>
    <select name="subscribe_post" id="subscribe_post">
        <option value="yes" <?php selected($selected, 'yes'); ?>><?php _e('Yes'); ?></option>
        <option value="no" <?php selected($selected, 'no'); ?>><?php _e('No'); ?></option>
    </select>
</p>