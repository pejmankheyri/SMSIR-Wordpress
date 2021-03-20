<?php

/**
 * Export Template Page
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
    <h2><?php _e('Export', 'wordpress_smsir'); ?></h2>
    <form id="export-filters" method="post" action="<?php echo WORDPRESS_SMSIR_DIR_PLUGIN.'includes/admin/wordpress_smsir-export.php'; ?>">
        <table>
            <tr valign="top">
                <th scope="row">
                    <label for="export-file-type"><?php _e('Export To', 'wordpress_smsir'); ?>:</label>
                </th>
                <td>
                    <select id="export-file-type" name="export-file-type">
                        <option value="0"><?php _e('Please select.', 'wordpress_smsir'); ?></option>
                        <option value="excel">Excel</option>
                        <option value="xml">XML</option>
                    </select>
                    <p class="description"><?php _e('Select the output file type.', 'wordpress_smsir'); ?></p>
                </td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" class="button-primary" name="wps_export_subscribe" value="<?php _e('Export', 'wordpress_smsir'); ?>" /></td>
            </tr>
        </table>
        <h4><a href="<?php echo admin_url(); ?>admin.php?page=wordpress_smsir/subscribe"><?php _e('Back', 'wordpress_smsir'); ?></a></h4>
    </form>
</div>