<?php

/**
 * PHP Wrapper to Interact with Thinq API
 *
 * @package Thinq
 * @version 2.0
 * @author  https://github.com/dutchie027
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @see     https://github.com/dutche027/thinq-php
 * @see     https://packagist.org/packages/dutchie027/thinq
 * @see     https://apidocs.thinq.com/
 *
 */

namespace dutchie027\Thinq;

class Text
{
    /**
     * Reference to \API object
     *
     * @var object
     */
    protected $api;

    public function __construct(API $api)
    {
        $this->api = $api;
    }

    public function sendText($to, $from, $message)
    {
        $array['from_did'] = $from;
        $array['to_did'] = $to;
        $array['message'] = $message;
        $body = json_encode($array);
        return $this->api->makeAPICall('POST', $this->api::SEND_SMS_URL, $body);
    }
}
