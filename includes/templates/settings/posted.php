<?php

/**
 * Posted Template Page
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
        jQuery('#doaction').click(function() {
            var action = jQuery('#action').val();
            if(action == 'trash') {
                var agree = confirm('<?php _e('Are you sure?', 'wordpress_smsir'); ?>');
                if(agree)
                    return true;
                else
                    return false;
            }
        })
    });
</script>

<div class="wrap">
    <h2><?php _e('Posted SMS', 'wordpress_smsir'); ?> (<?php echo count($total) . ' ' . __('SMS', 'wordpress_smsir'); ?>)</h2>
    <form action="" method="post">
        <table class="widefat fixed" cellspacing="0">
            <thead>
                <tr>
                    <th id="cb" scope="col" class="manage-column column-cb check-column"><input type="checkbox" name="checkAll" value=""/></th>
                    <th scope="col" class="manage-column column-name" width="5%"><?php //_e('Row', 'wordpress_smsir'); ?></th>
                    <th scope="col" class="manage-column column-name" width="10%"><?php _e('Post Date', 'wordpress_smsir'); ?></th>
                    <th scope="col" class="manage-column column-name" width="10%"><?php _e('Sender', 'wordpress_smsir'); ?></th>
                    <th scope="col" class="manage-column column-name" width="50%"><?php _e('Message', 'wordpress_smsir'); ?></th>
                    <th scope="col" class="manage-column column-name" width="25%"><?php _e('Recipient', 'wordpress_smsir'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Instantiate pagination smsect with appropriate arguments
                $pagesPerSection = 10;
                $rowsperpage = 20;

                $options = array($rowsperpage, "All");
                $stylePageOff = "pageOff";
                $stylePageOn = "pageOn";
                $styleErrors = "paginationErrors";
                $styleSelect = "paginationSelect";

                $Pagination = new Pagination(count($total), $pagesPerSection, $options, false, $stylePageOff, $stylePageOn, $styleErrors, $styleSelect);

                $start = $Pagination->getEntryStart();
                $end = $Pagination->getEntryEnd();

                // Retrieve MySQL data
                $get_result = $wpdb->get_results("SELECT * FROM `{$table_prefix}smsir_send` ORDER BY `{$table_prefix}smsir_send`.`ID` DESC  LIMIT {$start}, {$end}");

                $i = null;
                if (count($get_result) > 0) {
                    $j = 1;
                    foreach ($get_result as $gets) {
                        $i++;
                        ?>
                <tr class="<?php echo $i % 2 == 0 ? 'alternate':'author-self'; ?>" valign="middle" id="link-2">
                    <th class="check-column" scope="row"><input type="checkbox" name="column_ID[]" value="<?php echo $gets->ID ; ?>" /></th>
                    <td class="column-name"><?php echo $j; ?></td>
                    <td class="column-name"><?php echo $gets->date; ?></td>
                    <td class="column-name"><?php echo $gets->sender; ?></td>
                    <td class="column-name"><?php echo $gets->message; ?></td>
                    <td class="column-name"><?php echo $gets->recipient; ?></td>
                </tr>
                        <?php
                        $j = $j+1;
                    }
                } else { ?>
                <tr>
                    <td colspan="5"><?php _e('Not Found!', 'wordpress_smsir'); ?></td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th id="cb" scope="col" class="manage-column column-cb check-column"><input type="checkbox" name="checkAll" value=""/></th>
                    <th scope="col" class="manage-column column-name" width="5%"><?php //_e('Row', 'wordpress_smsir'); ?></th>
                    <th scope="col" class="manage-column column-name" width="10%"><?php _e('Post Date', 'wordpress_smsir'); ?></th>
                    <th scope="col" class="manage-column column-name" width="10%"><?php _e('Sender', 'wordpress_smsir'); ?></th>
                    <th scope="col" class="manage-column column-name" width="50%"><?php _e('Message', 'wordpress_smsir'); ?></th>
                    <th scope="col" class="manage-column column-name" width="25%"><?php _e('Recipient', 'wordpress_smsir'); ?></th>
                </tr>
            </tfoot>
        </table>
        <div class="tablenav">
            <div class="alignleft actions">
                <select name="action" id="action">
                    <option selected="selected"><?php _e('Bulk Actions', 'wordpress_smsir'); ?></option>
                    <option value="trash"><?php _e('Remove', 'wordpress_smsir'); ?></option>
                </select>
                <input value="<?php _e('Apply', 'wordpress_smsir'); ?>" name="doaction" id="doaction" class="button-secondary action" type="submit"/>
            </div>
            <br class="clear">
        </div>
    </form>
<?php 
if ($get_result) { 
    ?>
    <div class="pagination-log">
        <?php echo $Pagination->display(); ?>
        <p id="result-log">
            <?php echo ' ' . __('Page', 'wordpress_smsir') . ' ' . $Pagination->getCurrentPage() . ' ' . __('From', 'wordpress_smsir') . ' ' . $Pagination->getTotalPages(); ?>
        </p>
        <select><option><?php echo $rowsperpage; ?></option></select>
    </div>
    <?php 
} 
?>
</div>