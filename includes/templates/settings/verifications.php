<?php

/**
 * Verifications Template Page
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
    <h2><?php _e('Posted verification SMS', 'wordpress_smsir'); ?> (<?php echo count($total) . ' ' . __('SMS', 'wordpress_smsir'); ?>)</h2>
    <form action="" method="post">
        <table class="widefat fixed" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col" class="manage-column column-name" width="20%"><?php _e('User name', 'wordpress_smsir'); ?></th>
                    <th scope="col" class="manage-column column-name" width="30%"><?php _e('User email', 'wordpress_smsir'); ?></th>
                    <th scope="col" class="manage-column column-name" width="20%"><?php _e('Mobile', 'wordpress_smsir'); ?></th>
                    <th scope="col" class="manage-column column-name" width="10%"><?php _e('Verification code', 'wordpress_smsir'); ?></th>
                    <th scope="col" class="manage-column column-name" width="10%"><?php _e('Status', 'wordpress_smsir'); ?></th>
                    <th scope="col" class="manage-column column-name" width="10%"><?php _e('Post Date', 'wordpress_smsir'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                date_default_timezone_set('Asia/Tehran');
                // Instantiate pagination smsect with appropriate arguments
                $pagesPerSection = 10;
                $options = array(25, "All");
                $stylePageOff = "pageOff";
                $stylePageOn = "pageOn";
                $styleErrors = "paginationErrors";
                $styleSelect = "paginationSelect";

                $Pagination = new Pagination(count($total), $pagesPerSection, $options, false, $stylePageOff, $stylePageOn, $styleErrors, $styleSelect);

                $start = $Pagination->getEntryStart();
                $end = $Pagination->getEntryEnd();

                // Retrieve MySQL data
                $get_result = $wpdb->get_results("SELECT * FROM `{$table_prefix}smsir_verification` ORDER BY `{$table_prefix}smsir_verification`.`id` DESC LIMIT {$start}, {$end}");

                $i = null;
                if (count($get_result) > 0) {
                    foreach ($get_result as $gets) {
                        $i++;

                        ?>
                <tr class="<?php echo $i % 2 == 0 ? 'alternate':'author-self'; ?>" valign="middle" id="link-2">
                    <td class="column-name"><?php echo $gets->user_login; ?></td>
                    <td class="column-name"><?php echo $gets->user_email; ?></td>
                    <td class="column-name"><?php echo $gets->mobile; ?></td>
                    <td class="column-name"><?php echo $gets->code; ?></td>
                    <td class="column-name"><?php echo _e($gets->status, 'wordpress_smsir'); ?></td>
                    <td class="column-name"><?php echo date("Y/m/d-H:i:s", $gets->add_time); ?></td>
                </tr>
                        <?php
                    }
                } else { ?>
                <tr>
                    <td colspan="5"><?php _e('Not Found!', 'wordpress_smsir'); ?></td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th scope="col" class="manage-column column-name" width="20%"><?php _e('User name', 'wordpress_smsir'); ?></th>
                    <th scope="col" class="manage-column column-name" width="30%"><?php _e('User email', 'wordpress_smsir'); ?></th>
                    <th scope="col" class="manage-column column-name" width="20%"><?php _e('Mobile', 'wordpress_smsir'); ?></th>
                    <th scope="col" class="manage-column column-name" width="10%"><?php _e('Verification code', 'wordpress_smsir'); ?></th>
                    <th scope="col" class="manage-column column-name" width="10%"><?php _e('Status', 'wordpress_smsir'); ?></th>
                    <th scope="col" class="manage-column column-name" width="10%"><?php _e('Post Date', 'wordpress_smsir'); ?></th>
                </tr>
            </tfoot>
        </table>
    </form>

    <?php if ($get_result) { ?>
    <div class="pagination-log">
        <?php echo $Pagination->display(); ?>
        <p id="result-log">
            <?php echo ' ' . __('Page', 'wordpress_smsir') . ' ' . $Pagination->getCurrentPage() . ' ' . __('From', 'wordpress_smsir') . ' ' . $Pagination->getTotalPages(); ?>
        </p>
    </div>
    <?php } ?>
</div>