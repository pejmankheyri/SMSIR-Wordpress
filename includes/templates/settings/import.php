<?php

/**
 * Import Template Page
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
    <h2><?php _e('Import', 'wordpress_smsir'); ?></h2>
    <form method="post" action="" enctype="multipart/form-data">
        <div id="html-upload-ui">
            <p id="async-upload-wrap">
                <input id="async-upload" type="file" name="wps-import-file"/>
                <p class="upload-html-bypass"><?php echo sprintf(__('<code>.xls</code> is the only acceptable format. Please see <a href="%s">this image</a> to show a standard xls import file.', 'wordpress_smsir'), WORDPRESS_SMSIR_DIR_PLUGIN.'assets/images/standard-xml-file.png'); ?></p>
            </p>
            <p id="async-upload-wrap">
                <label for="wpsms_group_name"><?php _e('Group', 'wordpress_smsir'); ?>:</label>
                <select name="wpsms_group_name" id="wpsms_group_name">
                    <?php foreach($get_group_result as $items): ?>
                    <option value="<?php echo $items->ID; ?>"><?php echo $items->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <input type="submit" class="button" name="wps_import" value="<?php _e('Upload', 'wordpress_smsir'); ?>" /></td>
        </div>
        <h4><a href="<?php echo admin_url(); ?>admin.php?page=wordpress_smsir/subscribe"><?php _e('Back', 'wordpress_smsir'); ?></a></h4>
    </form>
</div>