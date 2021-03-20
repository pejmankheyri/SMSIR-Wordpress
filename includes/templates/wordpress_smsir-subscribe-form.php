<?php

/**
 * Subscribe Form Page
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
    jQuery(document).ready(function($) {
        $("#wpsms-submit").click(function() {
            $("#wpsms-result").html('');
            var get_subscribe_name = $("#wpsms-name").val();
            var get_subscribe_mobile = $("#wpsms-mobile").val();
            var get_subscribe_type = $('input[name=subscribe_type]:checked').val();
            var $boxes = $('input[name=wpsms_grop_name]:checked');
            var get_subscribe_group = [];
            $boxes.each(function(){
                get_subscribe_group.push($(this).val().split(',')[0]);
            });
            $("#wpsms-subscribe").ajaxStart(function(){
                $("#wpsms-subscribe").css('opacity', '0.4');
                $("#wpsms-subscribe-loading").show();
            });
            $("#wpsms-subscribe").ajaxComplete(function(){
                $("#wpsms-subscribe").css('opacity', '1');
                $("#wpsms-subscribe-loading").hide();
            });
            $.get("<?php echo WORDPRESS_SMSIR_DIR_PLUGIN; ?>includes/admin/wordpress_smsir-subscribe.php", {name:get_subscribe_name, mobile:get_subscribe_mobile, group:get_subscribe_group, type:get_subscribe_type}, function(data, status){
                switch(data) {
                case 'success-1':
                    $("#wpsms-subscribe table").hide();
                    $("#wpsms-result").html('<p class="wps-success-message"><?php _e('You will join the newsletter!', 'wordpress_smsir'); ?></p>');
                    break;
                case 'success-2':
                    $("#wpsms-subscribe table").hide();
                    $("#wpsms-result").html('<p class="wps-error-message"><?php _e('Your subscription was canceled.', 'wordpress_smsir'); ?></p>');
                    break;
                case 'success-3':
                    $("#wpsms-subscribe table").hide();
                    $("#wpsms-activation").fadeIn();
                    $("#wpsms-result").html('<?php _e('You will join the newsletter, Activation code sent to your number.', 'wordpress_smsir'); ?>');
                    break;
                default:
                $("#wpsms-result").html(data);
                }
            });
        });

        <?php if (get_option('wordpress_smsir_subscribes_activation')) { ?>
        $("#activation").live('click', function() {
            $("#wpsms-activation-result").html('');
            var get_subscribe_mobile = $("#wpsms-mobile").val();
            var get_activation = $("#wpsms-ativation-code").val();
            $.get("<?php echo WORDPRESS_SMSIR_DIR_PLUGIN; ?>includes/admin/wordpress_smsir-subscribe-activation.php", {mobile:get_subscribe_mobile, activation:get_activation}, function(data, status){
                switch(data) {
                case 'success-1':
                    $("#wpsms-result").hide();
                    $("#wpsms-activation").hide();
                    $("#wpsms-activation-result").html('<p class="wps-success-message"><?php _e('Your membership in the complete newsletter!', 'wordpress_smsir'); ?></p>');
                    break;
                default:
                $("#wpsms-activation-result").html(data);
                }
            });
        });
        <?php } ?>
    });
</script>
<div id="wpsms-subscribe">
    <?php if (get_option('wordpress_smsir_subscribes_status')) { ?>
    <div id="wpsms-subscribe-loading"></div>
    <table>
        <tr>
            <td colspan="2"><?php _e('Enter your information for SMS Subscribe', 'wordpress_smsir'); ?></td>
        </tr>
        <tr>
            <td><?php _e('Name', 'wordpress_smsir'); ?>:</td>
            <td><input class="wpsms-input" type="text" id="wpsms-name"/></td>
        </tr>
        <tr>
            <td><?php _e('Mobile', 'wordpress_smsir'); ?>:</td>
            <td><input class="wpsms-input" type="text" id="wpsms-mobile"/></td>
        </tr>
        <tr>
            <td><?php _e('Group', 'wordpress_smsir'); ?>:</td>
            <td>
                <?php foreach($get_group_result as $items): ?>
                    <input style="cursor: pointer;" type="checkbox" value="<?php echo $items->ID; ?>" name="wpsms_grop_name" id="wpsms-groups-<?php echo $items->ID; ?>" />
                    <label style="cursor: pointer;" for="wpsms-groups-<?php echo $items->ID; ?>" ><?php echo $items->name; ?></label><br>
                <?php endforeach; ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input style="cursor: pointer;" type="radio" name="subscribe_type" id="wpsms-type-subscribe" value="subscribe" checked="checked"/>
                <label style="cursor: pointer;" for="wpsms-type-subscribe"><?php _e('Subscribe', 'wordpress_smsir'); ?></label>

                <input style="cursor: pointer;" type="radio" name="subscribe_type" id="wpsms-type-unsubscribe" value="unsubscribe"/>
                <label style="cursor: pointer;" for="wpsms-type-unsubscribe"><?php _e('Unsubscribe', 'wordpress_smsir'); ?></label>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <button class="wpsms-submit" id="wpsms-submit"><?php _e('Subscribe', 'wordpress_smsir'); ?></button>
            </td>
        </tr>
    </table>

    <div id="wpsms-result"></div>
    <div id="wpsms-activation">
        <?php _e('Please enter the activation code:', 'wordpress_smsir'); ?>
        <input type="text" id="wpsms-ativation-code" name="get_activation"/>
        <button class="wpsms-submit" id="activation"><?php _e('Activation', 'wordpress_smsir'); ?></button>
    </div>
    <div id="wpsms-activation-result"></div>

    <?php } else { ?>
    <div class="wpsms-deactive">
        <?php _e('Subscribe is Deactive!', 'wordpress_smsir'); ?>
    </div>
    <?php } ?>
</div>