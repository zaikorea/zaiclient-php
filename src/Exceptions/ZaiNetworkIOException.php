<?php

/**
 * ZaiNetworkIOException
 * @author Uiseop Eom <tech@zaikorea.org>
 * @modifiedBy <name>
 */

namespace ZaiClient\Exceptions;

use \GuzzleHttp\Exception\TransferException;

/**
 * Exception class thrown when the Network connection failed.
 */
class ZaiNetworkIOException extends \Exception
{

    public function __construct($message, TransferException $previous)
    {
        parent::__construct($message, 1, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
