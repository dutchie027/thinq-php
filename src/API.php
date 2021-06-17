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

use dutchie027\Thinq\Exceptions\ThinqAPIRequestException;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class API
{
    /**
     * Version of the Library
     *
     * @const string
     */
    protected const LIBRARY_VERSION = '0.5.0';

    /**
     * Root of the API
     *
     * @const string
     */
    protected const API_URL = 'https://api.thinq.com';


    /**
     * API Token
     *
     * @var string
     */
    protected $p_token;

    /**
     * Log Directory
     *
     * @var string
     */
    protected $p_log_location;

    /**
     * Log Reference
     *
     * @var string
     */
    protected $p_log;

    /**
     * Log Name
     *
     * @var string
     */
    protected $p_log_name;

    /**
     * Log File Tag
     *
     * @var string
     */
    protected $p_log_tag = "thinq";

    /**
     * Log Types
     *
     * @var array
     */
    protected $log_literals = [
        "debug",
        "info",
        "notice",
        "warning",
        "critical",
        "error"
    ];

    /**
     * The Guzzle HTTP client instance.
     *
     * @var \GuzzleHttp\Client
     */
    public $guzzle;

    /**
     * Default constructor
     */
    public function __construct($user, $token, array $attributes = [], Guzzle $guzzle = null)
    {
        $tokenString = $user . ":" . $token;
        $base64 = base64_encode($tokenString);
        $this->p_token = $base64;
        if (isset($attributes['log_dir']) && is_dir($attributes['log_dir'])) {
            $this->p_log_location = $attributes['log_dir'];
        } else {
            $this->p_log_location = sys_get_temp_dir();
        }

        if (isset($attributes['log_name'])) {
            $this->p_log_name = $attributes['log_name'];
            if (!preg_match("/\.log$/", $this->p_log_name)) {
                $this->p_log_name .= ".log";
            }
        } else {
            $this->p_log_name = $this->pGenRandomString() . "." . time() . ".log";
        }
        if (isset($attributes['log_tag'])) {
            $this->p_log = new Logger($attributes['log_tag']);
        } else {
            $this->p_log = new Logger($this->p_log_tag);
        }

        if (isset($attributes['log_level']) && in_array($attributes['log_level'], $this->log_literals)) {
            if ($attributes['log_level'] == "debug") {
                $this->p_log->pushHandler(new StreamHandler($this->pGetLogPath(), Logger::DEBUG));
            } elseif ($attributes['log_level'] == "info") {
                $this->p_log->pushHandler(new StreamHandler($this->pGetLogPath(), Logger::INFO));
            } elseif ($attributes['log_level'] == "notice") {
                $this->p_log->pushHandler(new StreamHandler($this->pGetLogPath(), Logger::NOTICE));
            } elseif ($attributes['log_level'] == "warning") {
                $this->p_log->pushHandler(new StreamHandler($this->pGetLogPath(), Logger::WARNING));
            } elseif ($attributes['log_level'] == "error") {
                $this->p_log->pushHandler(new StreamHandler($this->pGetLogPath(), Logger::ERROR));
            } elseif ($attributes['log_level'] == "critical") {
                $this->p_log->pushHandler(new StreamHandler($this->pGetLogPath(), Logger::CRITICAL));
            } else {
                $this->p_log->pushHandler(new StreamHandler($this->pGetLogPath(), Logger::WARNING));
            }
        } else {
            $this->p_log->pushHandler(new StreamHandler($this->pGetLogPath(), Logger::INFO));
        }
        $this->guzzle = $guzzle ?: new Guzzle();
    }

    /**
     * getLogLocation
     * Alias to Get Log Path
     *
     *
     * @return string
     *
     */
    public function getLogLocation()
    {
        return $this->pGetLogPath();
    }

    /**
     * getAPIToken
     * Returns the stored API Token
     *
     *
     * @return string
     *
     */
    private function getAPIToken()
    {
        return $this->p_token;
    }

    /**
     * account
     * Pointer to the \Account class
     *
     *
     * @return object
     *
     */
    public function cnam(): CNAM
    {
        $cnam = new CNAM($this);
        return $cnam;
    }

    /**
     * lrn
     * Pointer to the \LRN class
     *
     *
     * @return object
     *
     */
    public function lrn(): LRN
    {
        $lrn = new LRN($this);
        return $lrn;
    }

    /**
     * outbound
     * Pointer to the \Outbound class
     *
     *
     * @return object
     *
     */
    public function outbound(): Outbound
    {
        $outbound = new Outbound($this);
        return $outbound;
    }

    /**
     * inbound
     * Pointer to the \Inbound class
     *
     *
     * @return object
     *
     */
    public function inbound(): Inbound
    {
        $inbound = new Inbound($this);
        return $inbound;
    }

    /**
     * text
     * Pointer to the \Text class
     *
     *
     * @return object
     *
     */
    public function text(): Text
    {
        $text = new Text($this);
        return $text;
    }


    /**
     * getLogPointer
     * Returns a referencd to the logger
     *
     *
     * @return object
     *
     */
    public function getLogPointer()
    {
        return $this->p_log;
    }

    /**
     * pGetLogPath
     * Returns full path and name of the log file
     *
     *
     * @return string
     *
     */
    protected function pGetLogPath()
    {
        return $this->p_log_location . '/' . $this->p_log_name;
    }

    /**
     * setHeaders
     * Sets the headers using the API Token
     *
     *
     * @return array
     *
     */
    public function setHeaders()
    {
        $array = [
            'User-Agent' => 'php-api-dutchie027/' . self::LIBRARY_VERSION,
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $this->getAPIToken()
        ];
        return $array;
    }

    /**
     * pGenRandomString
     * Generates a random string of $length
     *
     * @param int $length
     *
     * @return string
     *
     */
    public function pGenRandomString($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    /**
     * makeAPICall
     * Makes the API Call
     *
     * @param $type string GET|POST|DELETE|PATCH
     * @param $url string endpoint
     * @param $body string - usually passed as JSON
     *
     * @return Psr7\Stream Object
     * @throws ThinqAPIRequestException Exception with details regarding the failed request
     *
     */
    public function makeAPICall($type, $url, $body = null)
    {
        $data['headers'] = $this->setHeaders();
        $data['body'] = $body;
        try {
            $request = $this->guzzle->request($type, $url, $data);
            return $request->getBody();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $ja = json_decode($response->getBody()->getContents(), true);
                throw new ThinqAPIRequestException('An error occurred while performing the request to ' . $url . ' -> ' . (isset($ja['error']) ? $ja['error'] : json_encode($ja)));
            }
            throw new ThinqAPIRequestException(('An unknown error ocurred while performing the request to ' . $url));
        }
    }
}
