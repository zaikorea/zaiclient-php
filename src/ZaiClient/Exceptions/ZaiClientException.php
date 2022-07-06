<?php
/**
 * ZaiClientException
 */

namespace ZaiKorea\ZaiClient\Exceptions;

use \GuzzleHttp\Exception\BadResponseException;

class ZaiClientException extends \Exception {
    private $http_status_code;

    public function __construct($message, BadResponseException $previous) {
        // $message = preg_replace('/^[^:]*/', '\ZaiKorea\ZaiClient\Exceptions\ZaiClientException', $message);
        $this->http_status_code = $previous->getResponse()->getStatusCode();
        parent::__construct($message, 0, $previous);
    }

    public function getHttpStatusCode() {
        return $this->http_status_code;
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
