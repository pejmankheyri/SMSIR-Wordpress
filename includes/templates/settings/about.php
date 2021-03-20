<?php

/**
 * About Template Page
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
    <h2><?php _e('About Plugin', 'wordpress_smsir'); ?></h2>
    <p><?php echo sprintf(__('Version plugin: %s', 'wordpress_smsir'), WORDPRESS_SMSIR_VERSION); ?></p>
    <p><?php echo sprintf(__('The first free WordPress Iranian plugin that works on Web service messages.', 'wordpress_smsir'), ''); ?></p>
    <p><?php echo sprintf(__('This plugin created by %s from %s gorup', 'wordpress_smsir'), '<a href="https://ipe.ir">Ipe Developers</a>', '<a href="https://ipe.ir">IPE</a>'); ?>
</div>