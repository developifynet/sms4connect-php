<?php

namespace Developifynet\Sms4Connect;

use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Traits\Macroable;

class Sms4ConnectSMS
{
    use Macroable;

    /**
     * Account ID for SMS API
     *
     * @var null
     */
    private $_id = null;

    /**
     * Password for SMS APIs
     *
     * @var null
     */
    private $_password = null;

    /**
     * SMS Mask
     *
     * @var string
     */
    private $_mask = 'SMS4CONNECT';

    /**
     * Receiver List which will get SMS
     *
     * @var null
     */
    private $_tos = null;

    /**
     * Text for Receiver List which will get SMS
     *
     * @var null
     */
    private $_msg = null;

    /**
     * Language is English and Urdu. Not Case sensitive.
     *
     * @var string
     */
    private $_lang = 'English';


    /**
     * Type XML or JSON
     *
     * @var string
     */
    private $_type = 'xml';

    /**
     * Test Mode enable/disable
     *
     * @var int
     */
    private $_test_mode = 0;

    /**
     * SMS transaction number
     *
     * @var int
     */
    private $_transaction = 0;

    /**
     * URL where SMS will be sent.
     *
     * @var string
     */
    private $_sendsms_url = 'http://sms4connect.com/api/sendsms.php/sendsms/url';

    /**
     * URL where delivery status of SMS will be checked.
     *
     * @var string
     */
    private $_deliverystatus_url = 'http://sms4connect.com/api/sendsms.php/delivery/status';

    /**
     * URL where delivery status of SMS will be checked.
     *
     * @var string
     */
    private $_checkbalance_url = 'http://sms4connect.com/api/sendsms.php/balance/status';

    /*
     * Send Quick SMS based on provided Data
     * @param: void
     * @return: array|mixed
     */
    private function sendQuickSMS() {

        $client = new \GuzzleHttp\Client();

        $query = array(
            'id' => $this->_id,
            'pass' => $this->_password,
            'mask' => $this->_mask,
            'to' => $this->_tos,
            'msg' => $this->_msg,
            'lang' => $this->_lang,
            'type' => $this->_type,
        );

        // SMS Response Data
        $sms_data = '';

        try {
            $response = $client->request('GET', $this->_sendsms_url, ['query' => $query]);

            $status = true;
            $error_msg = '';

            if($response->getStatusCode() == 200) {
                $responseBody = $response->getBody();
                if($responseBody) {
                    try {
                        $responseJSON = \GuzzleHttp\json_decode(\GuzzleHttp\json_encode(simplexml_load_string($responseBody, "SimpleXMLElement", LIBXML_NOCDATA)), true);
                        if(
                            (isset($responseJSON['code']) && $responseJSON['code'] == '300') &&
                            (isset($responseJSON['type']) && $responseJSON['type'] == 'Success')
                        ) {
                            $sms_data = $responseJSON['transactionID'];
                        } else {
                            $status = false;
                            $error_msg = $this->tranlateError($responseJSON['code']);
                        }
                    } catch (\Exception $e) {
                        $status = false;
                        $error_msg = 'Service is temporarily unavailable. Your patience is requested.';
                    }
                } else {
                    $status = false;
                    $error_msg = 'Unable to connect with server.';
                }
            } else {
                $status = false;
                $error_msg = $response->getReasonPhrase();
            }
        } catch (ConnectException $e) {
            $status = false;
            $error_msg = $e->getMessage();
        }

        return array(
            'status' => $status,
            'sms_data' => $sms_data,
            'error_msg' => $error_msg
        );
    }

    /*
     * Send SMS based on provided Data
     * @param: array|mixed
     * @return: array|mixed
     */
    public function SendSMS($SMSData) {
        // Error handling variables
        $status = 1;
        $error_msg = array();

        if(!isset($SMSData['id']) || !$SMSData['id']) {
            $status = false;
            $error_msg[] = 'Account ID is required';
        } else {
            $this->_id = $SMSData['id'];
        }

        if(!isset($SMSData['password']) || !$SMSData['password']) {
            $status = false;
            $error_msg[] = 'Account Password is required';
        } else {
            $this->_password = $SMSData['password'];
        }

        if(!isset($SMSData['to']) || !$SMSData['to']) {
            $status = false;
            $error_msg[] = 'To is required';
        } else {
            $this->_tos = is_array($SMSData['to']) ? implode(',', $SMSData['to']) : $SMSData['to'];
        }

        if(!isset($SMSData['msg']) || !$SMSData['msg']) {
            $status = false;
            $error_msg[] = 'Message is required';
        } else {
            $this->_msg = htmlentities($SMSData['msg']);
        }

        if(!isset($SMSData['test_mode'])) {
            $status = false;
            $error_msg[] = 'Test Mode value is required';
        } else {
            $this->_test_mode = $SMSData['test_mode'];
        }

        if(!isset($SMSData['mask']) || !$SMSData['mask']) {
            $status = false;
            $error_msg[] = 'SMS Mask is required';
        } else {
            $this->_mask = $SMSData['mask'];
        }

        if(isset($SMSData['lang']) && $SMSData['lang']) {
            $this->_lang = $SMSData['lang'];
        }

        // Verify Test Mode, If enable then send response immedately
        if($this->_test_mode) {
            return array(
                'status' => true,
                'sms_data' => 'Test Mode is enabled',
                'error_msg' => '',
            );
        }

        if(!$status) {
            // One or more information is needed
            return array(
                'status' => $status,
                'sms_data' => '',
                'error_msg' => implode(', ', $error_msg),
            );
        } else {
            // Send Quick SMS based on retrieved Session ID
            return $this->sendQuickSMS();
        }
    }

    /*
     * Check delivery status of SMS
     * @param: array|mixed
     * @return: array|mixed
     */
    public function checkDeliveryStatus($SMSData) {
        // Error handling variables
        $status = 1;
        $error_msg = array();

        if(!isset($SMSData['id']) || !$SMSData['id']) {
            $status = false;
            $error_msg[] = 'Account ID is required';
        } else {
            $this->_id = $SMSData['id'];
        }

        if(!isset($SMSData['password']) || !$SMSData['password']) {
            $status = false;
            $error_msg[] = 'Account Password is required';
        } else {
            $this->_password = $SMSData['password'];
        }

        if(!isset($SMSData['transaction']) || !$SMSData['transaction']) {
            $status = false;
            $error_msg[] = 'Transaction is required';
        } else {
            $this->_transaction = is_array($SMSData['transaction']) ? implode(',', $SMSData['transaction']) : $SMSData['transaction'];
        }

        if(!isset($SMSData['test_mode'])) {
            $status = false;
            $error_msg[] = 'Test Mode value is required';
        } else {
            $this->_test_mode = $SMSData['test_mode'];
        }

        // Verify Test Mode, If enable then send response immedately
        if($this->_test_mode) {
            return array(
                'status' => true,
                'sms_data' => [],
                'error_msg' => '',
            );
        }

        if(!$status) {
            // One or more information is needed
            return array(
                'status' => $status,
                'sms_data' => [],
                'error_msg' => implode(', ', $error_msg),
            );
        } else {

            $client = new \GuzzleHttp\Client();

            // SMS Response Data
            $sms_data = [];

            try {
                $response = $client->request('GET', $this->_deliverystatus_url, ['query' => [
                    'id' => $this->_id,
                    'pass' => $this->_password,
                    'transaction' => $this->_transaction,
                ]]);

                $status = true;
                $error_msg = '';

                if($response->getStatusCode() == 200) {
                    $responseBody = $response->getBody();
                    if($responseBody) {

                        try {
                            $responseJSON = \GuzzleHttp\json_decode(\GuzzleHttp\json_encode(simplexml_load_string($responseBody, "SimpleXMLElement", LIBXML_NOCDATA)), true);
                            if(
                                (isset($responseJSON['transaction']) && count($responseJSON['transaction']))
                            ) {
                                $sms_data = $responseJSON['transaction'];
                            } else {
                                $sms_data = [];
                            }
                        } catch (\Exception $e) {
                            $status = false;
                            $error_msg = 'Service is temporarily unavailable. Your patience is requested.';
                        }
                    } else {
                        $status = false;
                        $error_msg = 'Unable to connect with server.';
                    }
                } else {
                    $status = false;
                    $error_msg = $response->getReasonPhrase();
                }
            } catch (ConnectException $e) {
                $status = false;
                $error_msg = $e->getMessage();
            }

            return array(
                'status' => $status,
                'sms_data' => $sms_data,
                'error_msg' => $error_msg
            );
        }
    }

    /*
     * Check account balance
     * @param: array|mixed
     * @return: array|mixed
     */
    public function checkBalance($SMSData) {
        // Error handling variables
        $status = 1;
        $error_msg = array();

        if(!isset($SMSData['id']) || !$SMSData['id']) {
            $status = false;
            $error_msg[] = 'Account ID is required';
        } else {
            $this->_id = $SMSData['id'];
        }

        if(!isset($SMSData['password']) || !$SMSData['password']) {
            $status = false;
            $error_msg[] = 'Account Password is required';
        } else {
            $this->_password = $SMSData['password'];
        }

        if(!isset($SMSData['test_mode'])) {
            $status = false;
            $error_msg[] = 'Test Mode value is required';
        } else {
            $this->_test_mode = $SMSData['test_mode'];
        }

        // Verify Test Mode, If enable then send response immedately
        if($this->_test_mode) {
            return array(
                'status' => true,
                'sms_data' => [
                    'balance' => 0.00,
                    'expiry' => date('Y-m-d')
                ],
                'error_msg' => 'Test Mode is enabled',
            );
        }

        if(!$status) {
            // One or more information is needed
            return array(
                'status' => $status,
                'sms_data' => [
                    'balance' => null,
                    'expiry' => null
                ],
                'error_msg' => implode(', ', $error_msg),
            );
        } else {

            $client = new \GuzzleHttp\Client();

            // SMS Response Data
            $sms_data = [
                'balance' => null,
                'expiry' => null
            ];

            try {
                $response = $client->request('GET', $this->_checkbalance_url, ['query' => [
                    'id' => $this->_id,
                    'pass' => $this->_password,
                ]]);

                $status = true;
                $error_msg = '';

                if($response->getStatusCode() == 200) {
                    $responseBody = $response->getBody();
                    if($responseBody) {
                        try {
                            $responseJSON = \GuzzleHttp\json_decode(\GuzzleHttp\json_encode(simplexml_load_string($responseBody, "SimpleXMLElement", LIBXML_NOCDATA)), true);
                            if(
                                (isset($responseJSON['code']) && $responseJSON['code'] == '100') &&
                                (isset($responseJSON['type']) && $responseJSON['type'] == 'Success')
                            ) {
                                $sms_data = [
                                    'balance' => number_format($responseJSON['response'], 2),
                                    'expiry' => $responseJSON['expiry']
                                ];
                            } else {
                                $status = false;
                                $error_msg = $this->tranlateError($responseJSON['code']);
                            }
                        } catch (\Exception $e) {
                            $status = false;
                            $error_msg = 'Service is temporarily unavailable. Your patience is requested.';
                        }
                    } else {
                        $status = false;
                        $error_msg = 'Unable to connect with server.';
                    }
                } else {
                    $status = false;
                    $error_msg = $response->getReasonPhrase();
                }
            } catch (ConnectException $e) {
                $status = false;
                $error_msg = $e->getMessage();
            }

            return array(
                'status' => $status,
                'sms_data' => $sms_data,
                'error_msg' => $error_msg
            );
        }
    }

    /*
     * Error Translation for SMS Gateway
     *
     * @param: string
     * @return: string
     */
    private function tranlateError($ErrorCode) {
        $errorCodes = array(
            '200' => 'Invalid API id/password for the customer.',
            '203' => 'Customer account has expired.',
            '204' => 'Invalid SMS masking for the customer.',
            '206' => 'Invalid Destination Number, Please Check Format i-e 92345xxxxxxx',
            '208' => 'One of the required parameters is missing.',
            '209' => 'Account has been blocked/suspended.',
            '210' => 'Invalid language specified for the message',
            '211' => 'Insufficient Credit in account for this SMS.',
        );

        if($ErrorCode && array_key_exists($ErrorCode, $errorCodes)) {
            return $errorCodes[$ErrorCode];
        } else {
            return 'No error code match.';
        }
    }
}

