<?php

/**
 * WORDPRESS SMSIR Class Page
 * 
 * PHP version 5.6.x | 7.x | 8.x
 * 
 * @category  PLugins
 * @package   Wordpress
 * @author    Pejman Kheyri <pejmankheyri@gmail.com>
 * @copyright 2021 All rights reserved.
 */

/**
 * WORDPRESS SMSIR Class
 * 
 * @category  PLugins
 * @package   Wordpress
 * @author    Pejman Kheyri <pejmankheyri@gmail.com>
 * @copyright 2021 All rights reserved.
 */
abstract class WORDPRESS_SMSIR
{
    /**
     * Webservice apidomain
     *
     * @var string
     */
    public $apidomain;

    /**
     * Webservice username
     *
     * @var string
     */
    public $username;

    /**
     * Webservice password
     *
     * @var string
     */
    public $password;

    /**
     * Webservice API/Key
     *
     * @var string
     */
    public $has_key = false;

    /**
     * SMsS send from number
     *
     * @var string
     */
    public $from;

    /**
     * Send SMS to number
     *
     * @var string
     */
    public $to;

    /**
     * SMS text
     *
     * @var string
     */
    public $msg;

    /**
     * Wordpress Database
     *
     * @var string
     */
    protected $db;

    /**
     * Wordpress Table prefix
     *
     * @var string
     */
    protected $tb_prefix;

    /**
     * Constructors
     */
    public function __construct()
    {
        global $wpdb, $table_prefix;
        $this->db = $wpdb;
        $this->tb_prefix = $table_prefix;
    }

    /**
     * Hook function.
     *
     * @param string $tag tag
     * @param string $arg arg
     * 
     * @return void
     */
    public function hook($tag, $arg)
    {
        do_action($tag, $arg);
    }

    /**
     * Insert To DataBase.
     *
     * @param string $sender    sender
     * @param string $message   message
     * @param string $recipient recipient
     * 
     * @return array
     */
    public function insertToDB($sender, $message, $recipient)
    {
        date_default_timezone_set('Asia/Tehran');

        return $this->db->insert(
            $this->tb_prefix . "smsir_send",
            array(
                'date' => date('Y-m-d H:i:s', current_time('timestamp', 0)),
                'sender' => $sender,
                'message' => $message,
                'recipient' => implode(',', $recipient)
            )
        );
    }

    /**
     * Insert To DataBase.
     *
     * @param string $message message
     * 
     * @return array
     */
    public function insertToDBclub($message)
    {
        date_default_timezone_set('Asia/Tehran');
        $recipient = __('Customer club contacts', 'wordpress_smsir');
        $sender = __('Customer club number', 'wordpress_smsir');

        return $this->db->insert(
            $this->tb_prefix . "smsir_send",
            array(
                'date' => date('Y-m-d H:i:s', current_time('timestamp', 0)),
                'sender' => $sender,
                'message' => $message,
                'recipient' => $recipient
            )
        );
    }

    /**
     * Insert To DataBase.
     *
     * @param string $message message
     * @param string $to      to
     * 
     * @return array
     */
    public function insertToDBclubWithNumbers($message,$to)
    {
        date_default_timezone_set('Asia/Tehran');
        $sender = __('Customer club number', 'wordpress_smsir');

        return $this->db->insert(
            $this->tb_prefix . "smsir_send",
            array(
                'date' => date('Y-m-d H:i:s', current_time('timestamp', 0)),
                'sender' => $sender,
                'message' => $message,
                'recipient' => implode(',', $to)
            )
        );
    }
}