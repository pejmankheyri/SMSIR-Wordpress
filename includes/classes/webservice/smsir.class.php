<?php

/**
 * SMSIR Class Page
 * 
 * PHP version 5.6.x | 7.x | 8.x
 * 
 * @category  PLugins
 * @package   Wordpress
 * @author    Pejman Kheyri <pejmankheyri@gmail.com>
 * @copyright 2021 All rights reserved.
 */

/**
 * SmsIr Bulk Gateway Class
 * 
 * @category  PLugins
 * @package   Wordpress
 * @author    Pejman Kheyri <pejmankheyri@gmail.com>
 * @copyright 2021 All rights reserved.
 */
class Smsir extends WORDPRESS_SMSIR
{
    public $tariff = "https://sms.ir/";
    public $panel = "sms.ir";
    public $unitrial = false;
    public $unit;
    public $flash = "disable";
    public $isflash = false;

    /**
     * Gets API Customer Club Send To Categories Url.
     *
     * @return string Indicates the Url
     */
    protected function getAPICustomerClubSendToCategoriesUrl()
    {
        return "api/CustomerClub/SendToCategories";
    }

    /**
     * Gets API Message Send Url.
     *
     * @return string Indicates the Url
     */
    protected function getAPIMessageSendUrl()
    {
        return "api/MessageSend";
    }

    /**
     * Gets API Customer Club Add Contact And Send Url.
     *
     * @return string Indicates the Url
     */
    protected function getAPICustomerClubAddAndSendUrl()
    {
        return "api/CustomerClub/AddContactAndSend";
    }

    /**
     * Gets API Customer Club Contact Url.
     *
     * @return string Indicates the Url
     */
    protected function getAPICustomerClubContactUrl()
    {
        return "api/CustomerClubContact";
    }

    /**
     * Gets API credit Url.
     *
     * @return string Indicates the Url
     */
    protected function getAPIcreditUrl()
    {
        return "api/credit";
    }

    /**
     * Gets API Verification Code Url.
     *
     * @return string Indicates the Url
     */
    protected function getAPIVerificationCodeUrl()
    {
        return "api/VerificationCode";
    }

    /**
     * Gets Api Token Url.
     *
     * @return string Indicates the Url
     */
    protected function getApiTokenUrl()
    {
        return "api/Token";
    }

    /**
     * Class Construction
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // ini_set("soap.wsdl_cache_enabled", "0");
    }

    /**
     * Send SMS.
     *
     * @return boolean
     */
    public function sendSMS()
    {
        if ($this->to) {
            foreach ($this->to as $key=>$value) {
                if (($this->ismobile($value)) || ($this->ismobilewithz($value))) {
                    $number[] = doubleval($value);
                }
            }
            @$numbers = array_unique($number);

            if (is_array($numbers) && $numbers) {
                foreach ($numbers as $key => $value) {
                    $Messages[] = $this->msg;
                }
            }

            $SendDateTime = date("Y-m-d")."T".date("H:i:s");
            date_default_timezone_set('Asia/Tehran');

            if (get_option('wordpress_smsir_stcc_number')) {

                foreach ($numbers as $num_keys => $num_vals) {
                    $contacts[] = array(
                        "Prefix" => "",
                        "FirstName" => "" ,
                        "LastName" => "",
                        "Mobile" => $num_vals,
                        "BirthDay" => "",
                        "CategoryId" => "",
                        "MessageText" => $this->msg
                    );
                }

                $CustomerClubInsertAndSendMessage = $this->customerClubInsertAndSendMessage($contacts);

                if ($CustomerClubInsertAndSendMessage == true) {
                    $this->insertToDBclubWithNumbers($this->msg, $this->to);
                    $this->hook('wordpress_smsir_send', $CustomerClubInsertAndSendMessage);
                    return true;
                } else {
                    return false;
                }
            } else {
                $SendMessage = $this->sendMessage($numbers, $Messages, $SendDateTime);
                if ($SendMessage == true) {
                    $this->insertToDB($this->from, $this->msg, $this->to);
                    $this->hook('wordpress_smsir_send', $SendMessage);
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * Customer Club Send To Categories.
     *
     * @param Messages[] $Messages array structure of messages
     * 
     * @return string Indicates the sent sms result
     */
    public function sendSMStoCustomerclubContacts($Messages)
    {
        $contactsCustomerClubCategoryIds = array();
        $token = $this->_getToken($this->username, $this->password);
        if ($token != false) {
            $postData = array(
                'Messages' => $Messages,
                'contactsCustomerClubCategoryIds' => $contactsCustomerClubCategoryIds,
                'SendDateTime' => '',
                'CanContinueInCaseOfError' => 'false'
            );

            $url = $this->apidomain.$this->getAPICustomerClubSendToCategoriesUrl();
            $CustomerClubSendToCategories = $this->_execute($postData, $url, $token);
            $object = json_decode($CustomerClubSendToCategories);

            if (is_object($object)) {
                if ($object->IsSuccessful == true) {
                    $this->insertToDBclub($Messages);
                    $this->hook('wordpress_smsir_send', $object);
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Verification Code.
     *
     * @param string $Code         Code
     * @param string $MobileNumber Mobile Number
     * 
     * @return string Indicates the sent sms result
     */
    public function sendSMSforVerification($Code, $MobileNumber)
    {
        $token = $this->_getToken($this->username, $this->password);
        if ($token != false) {
            $postData = array(
                'Code' => $Code,
                'MobileNumber' => $MobileNumber,
            );

            $url = $this->apidomain.$this->getAPIVerificationCodeUrl();
            $VerificationCode = $this->_execute($postData, $url, $token);
            $object = json_decode($VerificationCode);

            $result = false;
            if (is_object($object)) {
                if ($object->IsSuccessful == true) {
                    $result = true;
                } else {
                    $result = false;
                }
                $result = $object->IsSuccessful;
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * Get Credit.
     *
     * @return string Indicates the sent sms result
     */
    public function getCredit()
    {
        $token = $this->_getToken($this->username, $this->password);
        if ($token != false) {
            $url = $this->apidomain.$this->getAPIcreditUrl();
            $GetCredit = $this->_executeCredit($url, $token);

            $object = json_decode($GetCredit);

            $result = false;
            if (is_object($object)) {
                if ($object->IsSuccessful == true) {
                    $result = $object->Credit;
                } else {
                    $result = $object->Message;
                }
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * Send sms.
     *
     * @param MobileNumbers[] $MobileNumbers array structure of mobile numbers
     * @param Messages[]      $Messages      array structure of messages
     * @param string          $SendDateTime  Send Date Time
     * 
     * @return string Indicates the sent sms result
     */
    public function sendMessage($MobileNumbers, $Messages, $SendDateTime = '')
    {
        $token = $this->_getToken($this->username, $this->password);

        if ($token != false) {
            $postData = array(
                'Messages' => $Messages,
                'MobileNumbers' => $MobileNumbers,
                'LineNumber' => $this->from,
                'SendDateTime' => $SendDateTime,
                'CanContinueInCaseOfError' => 'false'
            );

            $url = $this->apidomain.$this->getAPIMessageSendUrl();
            $SendMessage = $this->_execute($postData, $url, $token);
            $object = json_decode($SendMessage);

            $result = false;
            if (is_object($object)) {
                if ($object->IsSuccessful == true) {
                    $result = true;
                } else {
                    $result = false;
                }
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * Customer Club Insert And Send Message.
     *
     * @param data[] $data array structure of contacts data
     * 
     * @return string Indicates the sent sms result
     */
    public function customerClubInsertAndSendMessage($data)
    {
        $token = $this->_getToken($this->username, $this->password);
        if ($token != false) {
            $postData = $data;

            $url = $this->apidomain.$this->getAPICustomerClubAddAndSendUrl();
            $CustomerClubInsertAndSendMessage = $this->_execute($postData, $url, $token);
            $object = json_decode($CustomerClubInsertAndSendMessage);

            $result = false;
            if (is_object($object)) {
                if ($object->IsSuccessful == true) {
                    $result = true;
                } else {
                    $result = false;
                }
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * Add Contact To Customer Club.
     *
     * @param string $user_name user name
     * @param string $mobile    mobile
     *
     * @return string Indicates the sent sms result
     */
    public function inserttosmscustomerclub($user_name,$mobile)
    {
        $token = $this->_getToken($this->username, $this->password);
        if ($token != false) {
            $postData = array(
                'Prefix' => '',
                'FirstName' => '',
                'LastName' => $user_name,
                'Mobile' => $mobile,
                'BirthDay' => '',
                'CategoryId' => ''
            );

            $url = $this->apidomain.$this->getAPICustomerClubContactUrl();
            $AddContactToCustomerClub = $this->_execute($postData, $url, $token);
            $object = json_decode($AddContactToCustomerClub);

            $result = false;
            if (is_object($object)) {
                $result = $object->Message;
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * Gets token key for all web service requests.
     *
     * @return string Indicates the token key
     */
    private function _getToken()
    {
        $postData = array(
            'UserApiKey' => $this->username,
            'SecretKey' => $this->password,
            'System' => 'wordpress_5_v_3_1'
        );
        $postString = json_encode($postData);

        $ch = curl_init($this->apidomain.$this->getApiTokenUrl());
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            )
        );
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result);

        $resp = false;
        $IsSuccessful = '';
        $TokenKey = '';
        if (is_object($response)) {
            $IsSuccessful = $response->IsSuccessful;
            if ($IsSuccessful == true) {
                $TokenKey = $response->TokenKey;
                $resp = $TokenKey;
            } else {
                $resp = false;
            }
        }
        return $resp;
    }

    /**
     * Executes the main method.
     *
     * @param postData[] $postData array of json data
     * @param string     $url      url
     * @param string     $token    token string
     *
     * @return string Indicates the curl execute result
     */
    private function _execute($postData, $url, $token)
    {
        $postString = json_encode($postData);

        $ch = curl_init($url);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'x-sms-ir-secure-token: '.$token
            )
        );
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
    /**
     * Executes the main method.
     *
     * @param string $url   url
     * @param string $token token string
     * 
     * @return string Indicates the curl execute result
     */
    private function _executeCredit($url, $token)
    {
        $ch = curl_init($url);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'x-sms-ir-secure-token: '.$token
            )
        );
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    /**
     * Check if mobile number is valid.
     *
     * @param string $mobile mobile number
     * 
     * @return boolean Indicates the mobile validation
     */
    public function ismobile($mobile)
    {
        if (preg_match('/^09(0[1-5]|1[0-9]|3[0-9]|2[0-2]|9[0-1])-?[0-9]{3}-?[0-9]{4}$/', $mobile)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if mobile with zero number is valid.
     *
     * @param string $mobile mobile with zero number
     * 
     * @return boolean Indicates the mobile with zero validation
     */
    public function ismobilewithz($mobile)
    {
        if (preg_match('/^9(0[1-5]|1[0-9]|3[0-9]|2[0-2]|9[0-1])-?[0-9]{3}-?[0-9]{4}$/', $mobile)) {
            return true;
        } else {
            return false;
        }
    }
}
?>