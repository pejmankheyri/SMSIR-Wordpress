<?php

/**
 * Subscribes Template Page
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

<?php 
/**
 * Hook function.
 * 
 * @return void
 */
function Wordpress_Smsir_Group_pointer() 
{
    ?>
<script type="text/javascript">
jQuery(document).ready( function($) {
    $('#wpsms_groups').pointer({
        content: '<h3><?php _e('Group', 'wordpress_smsir'); ?></h3><p><?php _e('Outset Create group to better manage the subscribers.', 'wordpress_smsir'); ?></p>',
        position: {
            my: '<?php echo is_rtl() ? 'right':'left'; ?> top',
            at: 'center bottom',
            offset: '-25 0'
        },
        /*close: function() {
            setusernameSetting('wpsms_p1', '1');
        }*/
    }).pointer('open');
});
</script>
<?php } ?>

<div class="wrap">
    <?php if (!isset($_GET['action']) == 'edit') { ?>
        <?php if ((isset($_POST['action'])) && ($_POST['action'] == 'group_edit')) { ?>
            <?php 
                $wpsms_group_name = $_POST['wpsms_group_name'];
                $get_result = $wpdb->get_results("SELECT * FROM {$table_prefix}smsir_subscribes_group WHERE ID = '".$wpsms_group_name."'"); 
            ?>
            <div class="clear"></div>
            <form action="" method="post">
                <table>
                    <tr><td colspan="2"><h3><?php _e('Edit', 'wordpress_smsir'); ?> <?php _e('Group', 'wordpress_smsir'); ?></h4></td></tr>
                    <tr>
                        <td><span class="label_td" for="wp_group_name"><?php _e('Group name', 'wordpress_smsir'); ?>:</span></td>
                        <td><input type="text" id="wp_group_name" name="wp_group_name" value="<?php echo $get_result[0]->name; ?>"/></td>
                    </tr>
                    <input type="hidden" name="wp_group_id" value="<?php echo $get_result[0]->ID; ?>" />
                    <tr>
                        <td colspan="2"><input type="submit" class="button-primary" name="wp_edit_group" value="<?php _e('Edit', 'wordpress_smsir'); ?>" /></td>
                    </tr>
                </table>
            </form>
            <h4><a href="<?php echo admin_url(); ?>admin.php?page=wordpress_smsir/subscribe"><?php _e('Back', 'wordpress_smsir'); ?></a></h4>
        <?php } else {?>
            <h2>
                <?php _e('Members Newsletter', 'wordpress_smsir'); ?>
                <a class="add-new-h2" href="?page=wordpress_smsir/subscribe&action=import"><?php _e('Import', 'wordpress_smsir'); ?></a>
                <a class="add-new-h2" href="?page=wordpress_smsir/subscribe&action=export"><?php _e('Export', 'wordpress_smsir'); ?></a>
                <?php 
                if (isset($_POST['s'])) { 
                    ?>
                <span class="subtitle"><?php echo sprintf(__('Search result for %s', 'wordpress_smsir'), $_POST['s']); ?></span>
                <?php } ?>
            </h2>
            <div id="wpsms-subscribe-topBox">
                <?php if($get_group_result) : ?>
                <form action="" method="post" id="wpsms_subscribe_form_box">
                    <table>
                        <tr><td colspan="2"><h3><?php _e('Add new subscribe:', 'wordpress_smsir'); ?></h4></td></tr>
                        <tr>
                            <td><span class="label_td" for="wp_subscribe_name"><?php _e('Name', 'wordpress_smsir'); ?>:</span></td>
                            <td><input type="text" id="wp_subscribe_name" name="wp_subscribe_name"/></td>
                        </tr>
                        <tr>
                            <td><span class="label_td" for="wp_subscribe_mobile"><?php _e('Mobile', 'wordpress_smsir'); ?>:</span></td>
                            <td><input type="text" name="wp_subscribe_mobile" id="wp_subscribe_mobile" class="code"/></td>
                        </tr>
                        <tr>
                            <td><span class="label_td" for="wpsms_group_name"><?php _e('Group', 'wordpress_smsir'); ?>:</span></td>
                            <td>
                                <?php foreach($get_group_result as $items): ?>
                                <input type="checkbox" value="<?php echo $items->ID; ?>" name="wpsms_group_name[]" id="wpsms_group_name_<?php echo $items->ID; ?>" />
                                <label for="wpsms_group_name_<?php echo $items->ID; ?>" ><?php echo $items->name; ?></label><br>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><input type="submit" class="button-primary" name="wp_add_subscribe" value="<?php _e('Add', 'wordpress_smsir'); ?>" /></td>
                        </tr>
                    </table>
                </form>
                <?php endif; ?>
                <form action="" method="post" id="wpsms_subscribe_form_box">
                    <table>
                        <tr><td colspan="2"><h3 id="wpsms_groups"><?php _e('Add new Group:', 'wordpress_smsir'); ?></h4></td></tr>
                        <tr>
                            <td><span class="label_td" for="wpsms_group_name"><?php _e('Group name', 'wordpress_smsir'); ?>:</span></td>
                            <td><input type="text" id="wpsms_group_name" name="wpsms_group_name"/></td>
                        </tr>
                        <tr>
                            <td colspan="2"><input type="submit" class="button-primary" name="wpsms_add_group" value="<?php _e('Add', 'wordpress_smsir'); ?>" /></td>
                        </tr>
                    </table>
                </form>
                <script type="text/javascript">
                    function confirmation() {
                        if (!confirm("آیا از اعمال تغییر اطمینان دارید؟")) {
                            return false;
                        }
                    }
                </script>
                <?php if($get_group_result) : ?>
                <form action="" method="post" id="wpsms_subscribe_form_box">
                    <table>
                        <tr><td colspan="2"><h3><?php _e('Delete Group:', 'wordpress_smsir'); ?></h4></td></tr>
                        <tr>
                            <td><span class="label_td" for="wpsms_group_name"><?php _e('Group name', 'wordpress_smsir'); ?>:</span></td>
                            <td>
                                <select name="wpsms_group_name" id="wpsms_group_name">
                                    <?php foreach($get_group_result as $items): ?>
                                    <option value="<?php echo $items->ID; ?>"><?php echo $items->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><input type="submit" onclick="return confirmation();" class="button-primary" name="wpsms_delete_group" value="<?php _e('Remove', 'wordpress_smsir'); ?>" /></td>
                        </tr>
                    </table>
                </form>
                <?php endif; ?>
                <?php if($get_group_result) : ?>
                <form action="" method="post" id="wpsms_subscribe_form_box">
                    <table>
                        <tr><td colspan="2"><h3><?php _e('Edit', 'wordpress_smsir'); ?> <?php _e('Group', 'wordpress_smsir'); ?>:</h4></td></tr>
                        <tr>
                            <td><span class="label_td" for="wpsms_group_name"><?php _e('Group name', 'wordpress_smsir'); ?>:</span></td>
                            <td>
                                <select name="wpsms_group_name" id="wpsms_group_name">
                                    <?php foreach($get_group_result as $items): ?>
                                    <option value="<?php echo $items->ID; ?>"><?php echo $items->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <input type="hidden" name="action" value="group_edit" />
                        <tr>
                            <td colspan="2"><input type="submit" class="button-primary" name="wpsms_edit_group" value="<?php _e('Edit', 'wordpress_smsir'); ?>" /></td>
                        </tr>
                    </table>
                </form>
                <?php endif; ?>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <ul class="subsubsub">
                <li class="all">
                <a 
                <?php 
                if (isset($_GET['group']) == false) { 
                    echo 'class="current" '; 
                } ?>
                href="admin.php?page=wordpress_smsir/subscribe">
                    <?php _e('All', 'wordpress_smsir'); ?> 
                    <?php
                    if (isset($_GET['group']) == false) {
                        ?>
                    <span class="count">(<?php echo $total; ?>)</span>
                    <?php } ?>
                </a> |</li>
                <?php
                $i = null;
                foreach ($get_group_result as $groups) {
                    $current = null;
                    if (isset($_GET['group'])) {
                        if ($_GET['group'] == $groups->ID) {
                            $current = "class='current' ";
                        }
                    }
                    $line = ' |';
                    $i++;
                    if ($i == count($get_group_result)) {
                        $line = null;
                    }
                    $result = $wpdb->get_col("SELECT * FROM {$table_prefix}smsir_subscribes WHERE `group_ID` REGEXP '{$groups->ID}'");
                    $count = count($result);
                    echo "<li><a {$current} href='admin.php?page=wordpress_smsir/subscribe&group={$groups->ID}'>{$groups->name} <span class='count'>({$count})</span></a>{$line}</li>";
                }
                ?>
            </ul>
            <form method="post" action="" id="posts-filter">
                <p class="search-box">
                    <label for="post-search-input" class="screen-reader-text"><?php _e('Search subscribers', 'wordpress_smsir'); ?></label>
                    <input type="search" value="" name="s" id="post-search-input">
                    <input type="submit" value="<?php _e('Search subscribers', 'wordpress_smsir'); ?>" class="button" id="search-submit" name="search">
                </p>
            </form>
            <form action="" method="post">
                <table class="widefat fixed" cellspacing="0">
                    <thead>
                        <tr>
                            <th id="cb" scope="col" class="manage-column column-cb check-column"><input type="checkbox" name="checkAll" value=""/></th>
                            <th scope="col" class="manage-column column-name" width="5%"><?php //_e('Row', 'wordpress_smsir'); ?></th>
                            <th scope="col" class="manage-column column-name" width="30%"><?php _e('Name', 'wordpress_smsir'); ?></th>
                            <th scope="col" class="manage-column column-name" width="20%"><?php _e('Mobile', 'wordpress_smsir'); ?></th>
                            <th scope="col" class="manage-column column-name" width="20%"><?php _e('Group', 'wordpress_smsir'); ?></th>
                            <th scope="col" class="manage-column column-name" width="30%"><?php _e('Register date', 'wordpress_smsir'); ?></th>
                            <th scope="col" class="manage-column column-name" width="10%"><?php _e('Status', 'wordpress_smsir'); ?></th>
                            <th scope="col" class="manage-column column-name" width="10%"><?php _e('Edit', 'wordpress_smsir'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    // Retrieve MySQL data
                    if (isset($_GET['group'])) {
                        $get_result = $wpdb->get_results("SELECT * FROM `{$table_prefix}smsir_subscribes` WHERE `group_ID` REGEXP '{$_GET['group']}' ORDER BY `{$table_prefix}smsir_subscribes`.`ID` DESC  LIMIT {$start}, {$end}");
                    } else {
                        $get_result = $wpdb->get_results("SELECT * FROM `{$table_prefix}smsir_subscribes` ORDER BY `{$table_prefix}smsir_subscribes`.`ID` DESC  LIMIT {$start}, {$end}");
                    }
                    if (isset($_POST['search'])) {
                        $get_result = $wpdb->get_results("SELECT * FROM `{$table_prefix}smsir_subscribes` WHERE `name` LIKE '%{$_POST['s']}%' OR `mobile` LIKE '%{$_POST['s']}%' ORDER BY `{$table_prefix}smsir_subscribes`.`ID` DESC  LIMIT {$start}, {$end}");
                    }
                    if (count($get_result) > 0) {
                        $j = 1;
                        foreach ($get_result as $gets) {
                            $i++;
                            ?>
                        <tr class="<?php echo $i % 2 == 0 ? 'alternate':'author-self'; ?>" valign="middle" id="link-2">
                            <th class="check-column" scope="row"><input type="checkbox" name="column_ID[]" value="<?php echo $gets->ID ; ?>" /></th>
                            <td class="column-name"><?php echo $j; ?></td>
                            <td class="column-name"><?php echo $gets->name; ?></td>
                            <td class="column-name"><?php echo $gets->mobile; ?></td>
                            <td class="column-name">
                            <?php
                            if ($gets->group_ID) {
                                if (strpos($gets->group_ID, ',') != false) {
                                    $groups_id = explode(",", $gets->group_ID);
                                    $result = $wpdb->get_results("SELECT * FROM {$table_prefix}smsir_subscribes_group WHERE `ID` IN({$gets->group_ID})");
                                    $group_array = array();
                                    foreach ($result as $g_key=>$g_val) {
                                        $group_array[] = "<a href='admin.php?page=wordpress_smsir/subscribe&group={$g_val->ID}'>".$g_val->name."</a>";
                                    }
                                    echo implode(',', $group_array);
                                    unset($group_array);
                                } else {
                                    $result = $wpdb->get_row("SELECT * FROM {$table_prefix}smsir_subscribes_group WHERE `ID` = '{$gets->group_ID}'");
                                    echo "<a href='admin.php?page=wordpress_smsir/subscribe&group={$result->ID}'>{$result->name}</a>";
                                }
                            }
                            ?>
                            </td>
                            <td class="column-name"><?php echo $gets->date; ?></td>
                            <td class="column-name"><img src="<?php echo WORDPRESS_SMSIR_DIR_PLUGIN . '/assets/images/' . $gets->status; ?>.png" align="middle"/></td>
                            <td class="column-name"><a href="?page=wordpress_smsir/subscribe&action=edit&ID=<?php echo $gets->ID; ?>"><?php _e('Edit', 'wordpress_smsir'); ?></a></td>
                        </tr>
                            <?php
                            $j = $j+1;
                        }
                    } else { ?>
                        <tr>
                            <td colspan="7"><?php _e('Not Found!', 'wordpress_smsir'); ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th id="cb" scope="col" class="manage-column column-cb check-column"><input type="checkbox" name="checkAll" value=""/></th>
                            <th scope="col" class="manage-column column-name" width="5%"><?php //_e('Row', 'wordpress_smsir'); ?></th>
                            <th scope="col" class="manage-column column-name" width="30%"><?php _e('Name', 'wordpress_smsir'); ?></th>
                            <th scope="col" class="manage-column column-name" width="20%"><?php _e('Mobile', 'wordpress_smsir'); ?></th>
                            <th scope="col" class="manage-column column-name" width="20%"><?php _e('Group', 'wordpress_smsir'); ?></th>
                            <th scope="col" class="manage-column column-name" width="30%"><?php _e('Register date', 'wordpress_smsir'); ?></th>
                            <th scope="col" class="manage-column column-name" width="10%"><?php _e('Status', 'wordpress_smsir'); ?></th>
                            <th scope="col" class="manage-column column-name" width="10%"><?php _e('Edit', 'wordpress_smsir'); ?></th>
                        </tr>
                    </tfoot>
                </table>
                <div class="tablenav">
                    <div class="alignleft actions">
                        <select name="action" id="action">
                            <option selected="selected"><?php _e('Bulk Actions', 'wordpress_smsir'); ?></option>
                            <option value="trash"><?php _e('Remove', 'wordpress_smsir'); ?></option>
                            <option value="active"><?php _e('Active', 'wordpress_smsir'); ?></option>
                            <option value="deactive"><?php _e('Deactive', 'wordpress_smsir'); ?></option>
                        </select>
                        <input value="<?php _e('Apply', 'wordpress_smsir'); ?>" name="doaction" id="doaction" class="button-secondary action" type="submit"/>
                    </div>
                    <br class="clear">
                </div>
            </form>
            <?php if ($get_result) { ?>
            <div class="pagination-log">
                <?php echo $Pagination->display(); ?>
                <p id="result-log">
                    <?php echo ' ' . __('Page', 'wordpress_smsir') . ' ' . $Pagination->getCurrentPage() . ' ' . __('From', 'wordpress_smsir') . ' ' . $Pagination->getTotalPages(); ?>
                </p>
                <select><option><?php echo $rowsperpage; ?></option></select>
            </div>
            <?php } ?>
        <?php } ?>
    <?php } else { ?>
    <?php $get_result = $wpdb->get_results("SELECT * FROM {$table_prefix}smsir_subscribes WHERE ID = '".$_GET['ID']."'"); ?>
        <div class="clear"></div>
        <form action="" method="post">
            <table>
                <tr><td colspan="2"><h3><?php _e('Edit subscribe:', 'wordpress_smsir'); ?></h4></td></tr>
                <tr>
                    <td><span class="label_td" for="wp_subscribe_name"><?php _e('Name', 'wordpress_smsir'); ?>:</span></td>
                    <td><input type="text" id="wp_subscribe_name" name="wp_subscribe_name" value="<?php echo $get_result[0]->name; ?>"/></td>
                </tr>
                <tr>
                    <td><span class="label_td" for="wp_subscribe_mobile"><?php _e('Mobile', 'wordpress_smsir'); ?>:</span></td>
                    <td><input type="text" name="wp_subscribe_mobile" id="wp_subscribe_mobile" class="code" value="<?php echo $get_result[0]->mobile; ?>"/></td>
                </tr>
                <tr>
                    <td><span class="label_td" for="wpsms_group_name"><?php _e('Group name', 'wordpress_smsir'); ?>:</span></td>
                    <td>
                        <?php foreach($get_group_result as $items): ?>
                            <?php
                            if (strpos($get_result[0]->group_ID, ',') !== false) {
                                $g_ids = explode(",", $get_result[0]->group_ID);
                            } else {
                                $g_ids = $get_result[0]->group_ID;
                            }
                            if (($get_result[0]->group_ID) == ($items->ID)) {
                                $in_checked = "checked";
                            } else {
                                @$g_in_array = in_array($items->ID, $g_ids);
                                if ($g_in_array) {
                                    $in_checked = "checked";
                                } else {
                                    $in_checked = "";
                                }
                            }
                            ?>
                        <input type="checkbox" value="<?php echo $items->ID; ?>" <?php echo $in_checked; ?> name="wpsms_group_name[]" id="wpsms_group_name_<?php echo $items->ID; ?>" />
                        <label for="wpsms_group_name_<?php echo $items->ID; ?>" ><?php echo $items->name; ?></label><br>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <tr>
                    <td><span class="label_td" for="wp_subscribe_mobile"><?php _e('Status', 'wordpress_smsir'); ?>:</span></td>
                    <td>
                        <select name="wp_subscribe_status">
                            <option value="1" <?php selected($get_result[0]->status, '1'); ?>><?php _e('Active', 'wordpress_smsir'); ?></option>
                            <option value="0" <?php selected($get_result[0]->status, '0'); ?>><?php _e('Deactive', 'wordpress_smsir'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" class="button-primary" name="wp_edit_subscribe" value="<?php _e('Edit', 'wordpress_smsir'); ?>" /></td>
                </tr>
            </table>
        </form>
        <h4><a href="<?php echo admin_url(); ?>admin.php?page=wordpress_smsir/subscribe"><?php _e('Back', 'wordpress_smsir'); ?></a></h4>
    <?php } ?>
</div>