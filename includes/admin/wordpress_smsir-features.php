<?php

/**
 * Features Page
 * 
 * PHP version 5.6.x | 7.x | 8.x
 * 
 * @category  PLugins
 * @package   Wordpress
 * @author    Pejman Kheyri <pejmankheyri@gmail.com>
 * @copyright 2021 All rights reserved.
 */

if (!defined('ABSPATH')) exit;

/**
 * Tell A Freind head.
 *
 * @return void
 */
function Wordpress_Smsir_Tell_A_Freind_head()
{
    include_once dirname(__FILE__) . "/../templates/wordpress_smsir-tell-friend-head.php";
}

/**
 * Tell A Freind Content.
 *
 * @param string $content content
 * 
 * @return array
 */
function Wordpress_Smsir_Tell_A_freind($content)
{
    if (is_single()) {
        global $sms;
        include_once dirname(__FILE__) . "/../templates/wordpress_smsir-tell-friend.php";
        if ($_POST['send_post']) {

            $mobile = $_POST['get_fmobile'];
            if ($_POST['get_name'] && $_POST['get_fname'] && $_POST['get_fmobile']) {
                if ((strlen($mobile) >= 11) && (substr($mobile, 0, 2) == get_option('wordpress_smsir_mcc')) && (preg_match("([a-zA-Z])", $mobile) == 0) ) {
                    $sms->to = array($_POST['get_fmobile']);
                    $sms->msg = sprintf(__('Hi %s, the %s post suggested to you by %s. url: %s', 'wordpress_smsir'), $_POST['get_fname'], get_the_title(), $_POST['get_name'], wp_get_shortlink());

                    if ($sms->sendSMS())
                        _e('SMS was sent with success', 'wordpress_smsir');

                } else {
                    _e('Please enter a valid mobile number', 'wordpress_smsir');
                }
            } else {
                _e('Please complete all fields', 'wordpress_smsir');
            }
        }
    }
    return $content;
}

if (get_option('wordpress_smsir_suggestion_status')) {
    add_action('wp_head', 'Wordpress_Smsir_Tell_A_Freind_head');
    add_action('the_content', 'Wordpress_Smsir_Tell_A_freind');
}

/**
 * Modify Contact Methods.
 *
 * @param string $fields fields
 * 
 * @return array
 */
function Wordpress_Smsir_Modify_Contact_methods($fields)
{
    $fields['mobile'] = __('Mobile', 'wordpress_smsir');
    return $fields;
}

/**
 * Register Form Methods.
 *
 * @return void
 */
function Wordpress_Smsir_Register_form()
{
    $mobile = (isset($_POST['mobile'])) ? $_POST['mobile']: '';
    ?>
    <p>
        <label for="mobile"><?php _e('Your Mobile Number', 'wordpress_smsir') ?><br />
        <input type="text" name="mobile" id="mobile" class="input" value="<?php echo esc_attr(stripslashes($mobile)); ?>" size="25" /></label>
    </p>
    <?php
}

/**
 * Registration Errors Methods.
 *
 * @param string $errors               errors
 * @param string $sanitized_user_login sanitized user login
 * @param string $user_email           user email
 * 
 * @return array
 */
function Wordpress_Smsir_Registration_errors($errors, $sanitized_user_login, $user_email)
{
    global $sms, $wpdb, $table_prefix;
    $verify_config = get_option('wordpress_smsir_verify_config');

    if (empty($_POST['mobile'])) {
        $errors->add('first_name_error', __('<strong>ERROR</strong>: You must include a mobile number.', 'wordpress_smsir'));
    } else {
        $mobile_no = $_POST['mobile'];
        $get_mobile = $wpdb->get_results("SELECT * FROM `{$table_prefix}usermeta` WHERE `meta_key` = 'mobile' AND `meta_value` = '$mobile_no'");
        $mobile = $get_mobile[0]->meta_value;
        if ($mobile) {
            $errors->add('mobile_error', __('<strong>ERROR</strong>: This mobile number is already exist.', 'wordpress_smsir'));
        } else {
            if ($verify_config == "on") {

                $user_login = $_POST["user_login"];
                $user_email = $_POST["user_email"];
                $mobile = doubleval($_POST["mobile"]);
                $verification = $_POST["verification"];
                $wp_submit = $_POST["wp_submit"];
                $wp_resend = $_POST["wp_resend"];

                $random_number = mt_rand(1000, 9999);
                $get_current_date = time();

                $verify_form = '
				<p>
					<form method="post" action="'.$_SERVER["REQUEST_URI"].'">
						<label for="verification">'.__('Verification', 'wordpress_smsir').'<br />
						<input required placeholder="'.__('1234', 'wordpress_smsir').'" type="text" name="verification" id="verification" class="input" value="" size="25" /></label>
				</p>
				<p class="submit">
						<input type="hidden" name="user_login" value="'.$user_login.'" />
						<input type="hidden" name="user_email" value="'.$user_email.'" />
						<input type="hidden" name="mobile" value="'.$mobile.'" />
						<input type="hidden" name="redirect_to" value="" />
						<input type="submit" name="wp_submit" id="wp_submit" class="button button-primary button-large" value="'.__('verify', 'wordpress_smsir').'">
					</form>
				
				<form method="post" action="'.$_SERVER["REQUEST_URI"].'" id="wpsms_feature_resend">
					<input type="hidden" name="user_login" value="'.$user_login.'" />
					<input type="hidden" name="user_email" value="'.$user_email.'" />
					<input type="hidden" name="mobile" value="'.$mobile.'" />
					<input type="hidden" name="redirect_to" value="" />
					<input type="submit" name="wp_resend" id="wp_resend" class="button button-default button-large" value="'.__('resend', 'wordpress_smsir').'">
				</form>
				</p>
				';

                if ($wp_resend) {
                    $ReSendSMSforVerification = $sms->sendSMSforVerification($random_number, $mobile);
                    if ($ReSendSMSforVerification) {

                        $Reselect = $wpdb->query("SELECT * FROM `{$table_prefix}smsir_verification` WHERE `user_login` = '$user_login' AND `user_email` = '$user_email'");
                        if ($Reselect > 0) {
                            $Reupdate = $wpdb->update(
                                "{$table_prefix}smsir_verification",
                                array(
                                    'code' => $random_number,
                                    'status' => 'pending',
                                    'add_time' => $get_current_date,
                                    'mobile' => $mobile
                                ),
                                array(
                                    'user_login' => $user_login,
                                    'user_email' => $user_email
                                )
                            );
                        } else {
                            $Reinsert = $wpdb->insert(
                                "{$table_prefix}smsir_verification", array(
                                    'user_login' => $user_login,
                                    'user_email' => $user_email,
                                    'mobile' => $mobile,
                                    'code' => $random_number,
                                    'status' => 'pending',
                                    'add_time' => $get_current_date
                                )
                            );
                        }
                    } else {
                        $errors->add('verify_send_error', __('<strong>ERROR</strong>: verification sending unsuccessful.', 'wordpress_smsir'));
                        $errors->add('verification_form', $verify_form);
                    }
                } 

                if ($verification == "") {
                    if ($wp_submit != "verify") {
                        $SendSMSforVerification = $sms->sendSMSforVerification($random_number, $mobile);
                        if ($SendSMSforVerification) {
                            $select = $wpdb->query("SELECT * FROM `{$table_prefix}smsir_verification` WHERE `user_login` = '$user_login' AND `user_email` = '$user_email'");

                            if ($select > 0) {
                                $update = $wpdb->update(
                                    "{$table_prefix}smsir_verification",
                                    array(
                                        'code' => $random_number,
                                        'status' => 'pending',
                                        'add_time' => $get_current_date,
                                        'mobile' => $mobile
                                    ),
                                    array(
                                        'user_login' => $user_login,
                                        'user_email' => $user_email
                                    )
                                );
                            } else {
                                $insert = $wpdb->insert(
                                    "{$table_prefix}smsir_verification", array(
                                        'user_login' => $user_login,
                                        'user_email' => $user_email,
                                        'mobile' => $mobile,
                                        'code' => $random_number,
                                        'status' => 'pending',
                                        'add_time' => $get_current_date
                                    )
                                );
                            }
                        } else {
                            $errors->add('verify_send_error', __('<strong>ERROR</strong>: verification sending unsuccessful.', 'wordpress_smsir'));
                            $errors->add('verification_form', $verify_form);
                        }
                        $errors->add('verification_form', $verify_form);
                    } else {
                        $errors->add('verify_require_error', __('<strong>ERROR</strong>: verification code required.', 'wordpress_smsir'));
                        $errors->add('verification_form', $verify_form);
                    }
                } else {
                    $results = $wpdb->get_results("SELECT * FROM `{$table_prefix}smsir_verification` WHERE `user_login` = '$user_login' AND `user_email` = '$user_email' AND `mobile` = '$mobile' ORDER BY id DESC LIMIT 1");
                    if (empty($results)) {
                        $errors->add('no_user_error', __('<strong>ERROR</strong>: No user for check verification.', 'wordpress_smsir'));
                        $errors->add('verification_form', $verify_form);
                    } else {
                        if ($results[0]->code == $verification) {
                            $update = $wpdb->update(
                                "{$table_prefix}smsir_verification",
                                array(
                                    'status' => 'active',
                                    'add_time' => $get_current_date,
                                    'mobile' => $mobile
                                ),
                                array(
                                    'user_login' => $user_login,
                                    'user_email' => $user_email
                                )
                            );
                        } else {
                            $errors->add('no_valid_varify_error', __('<strong>ERROR</strong>: Verification code is not valid.', 'wordpress_smsir'));
                            $errors->add('verification_form', $verify_form);
                        }
                    }
                }
            }
        }
    }
    return $errors;
}

/**
 * Modify Contact Methods.
 *
 * @param string $user_id user_id
 * 
 * @return void
 */
function Wordpress_Smsir_Save_register($user_id)
{
    if (isset($_POST['mobile'])) {
        global $sms, $date;

        // Update user meta
        update_user_meta($user_id, 'mobile', $_POST['mobile']);

        // Send sms to user.
        $string = get_option('wordpress_smsir_narnu_tt');

        $username_info = get_userdata($user_id);
		
		$final_message = str_replace('%user_login%', $username_info->user_login, $string);
		$final_message = str_replace('%user_email%', $username_info->user_email, $final_message);
		$final_message = str_replace('%date_register%', $date, $final_message);

        $sms->to = array($_POST['mobile']);
        $sms->msg = $final_message;

        $sms->sendSMS();
    }
}

if (get_option('wordpress_smsir_add_mobile_field')) {
    add_filter('user_contactmethods', 'Wordpress_Smsir_Modify_Contact_methods');
    add_action('register_form', 'Wordpress_Smsir_Register_form');
    add_filter('registration_errors', 'Wordpress_Smsir_Registration_errors', 10, 3);

    if(get_option('wordpress_smsir_nrnu_stats'))
        add_action('user_register', 'Wordpress_Smsir_Save_register');
}