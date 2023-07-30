<?php

/**
 * EventLogger response
 */

namespace ZaiClient\Responses;

class EventLoggerResponse
{

    /**
     * @var string $message message returned from server
     */
    private $message;

    /**
     * @var int $failure_count number of failed records to DB
     */
    private $failure_count;

    /**
     * @var float $timestamp timestamp from server
     */
    private $timestamp;

    /**
     * Set message
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Set failure count due to throttling in server
     * @param int $failure_count
     */
    public function setFailureCount($failure_count)
    {
        $this->failure_count = $failure_count;
    }

    /**
     * Set timestamp
     * @param float $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Get Message
     * @return string Message from server
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get Failure count
     * @return float Number of record failures from server to DB
     */
    public function getFailureCount()
    {
        return $this->failure_count;
    }

    /**
     * Get Timestamp
     * @return float Timestamp from the server
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function __toString()
    {
        return "EventLoggerResonse{\n" .
            "\tmessage=\"{$this->message}\"\n" .
            "\tfailure count={$this->failure_count}\n" .
            "\ttimestamp={$this->timestamp}\n" .
            "}\n";
    }
}
