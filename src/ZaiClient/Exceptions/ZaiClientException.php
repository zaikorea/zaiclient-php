<?php
/**
 * ZaiClientException
 * @author Uiseop Eom <tech@zaikorea.org>
 * @modifiedBy <name>
 */

namespace ZaiKorea\ZaiClient\Exceptions;

use \GuzzleHttp\Exception\RequestException;

/**
 * Exception class thrown when the connection succeeded but 
 * request failed due to client or server error.
 */
class ZaiClientException extends \Exception {
    private $http_status_code;

    public function __construct($message, RequestException $previous) {
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
