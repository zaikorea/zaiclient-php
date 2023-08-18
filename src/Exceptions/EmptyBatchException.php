<?php

/**
 * BatchSizeLimitExceededException
 * @author Uiseop Eom <tech@zaikorea.org>
 * @modifiedBy <name>
 */

namespace ZaiClient\Exceptions;

/**
 *
 */
class EmptyBatchException extends \Exception
{
    public function __construct()
    {
        $message = sprintf("Number of total records cannot be 0");
        parent::__construct($message, 2);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
