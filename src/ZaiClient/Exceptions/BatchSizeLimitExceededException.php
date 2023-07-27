<?php

/**
 * BatchSizeLimitExceededException
 * @author Uiseop Eom <tech@zaikorea.org>
 * @modifiedBy <name>
 */

namespace ZaiKorea\ZaiClient\Exceptions;

use \GuzzleHttp\Exception\BadResponseException;

/**
 *
 */
class BatchSizeLimitExceededException extends \Exception
{
    public function __construct($records_num)
    {
        $message = sprintf("Number of total records cannot exceed 50, but your Event holds %d.", $records_num);
        parent::__construct($message, 2);
    }

    public function getHttpStatusCode()
    {
        return $this->http_status_code;
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
