<?php

/**
 * Different Versions Meta Links
 * 
 * PHP version 5.6.x | 7.x | 8.x
 * 
 * @category  PLugins
 * @package   Wordpress
 * @author    Pejman Kheyri <pejmankheyri@gmail.com>
 * @copyright 2021 All rights reserved.
 */

/**
 * Add Meta links.
 *
 * @param string $links links
 * @param string $file  file
 * 
 * @return array
 */
function Wordpress_Smsir_Add_Meta_links($links, $file) 
{
    if ($file == 'wordpress_smsir/wordpress_smsir.php') {
        $links[] =  __('Upgrade to pro version', 'wordpress_smsir');
    }

    return $links;
}
add_filter('plugin_row_meta', 'Wordpress_Smsir_Add_Meta_links', 10, 2);