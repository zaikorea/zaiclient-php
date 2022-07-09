<?php

/**
 *  Z.Ai API client
 *  @author Uiseop Eom <aesop@zaikorea.org>
 */


namespace ZaiKorea\ZaiClient;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
use ZaiKorea\ZaiClient\Requests\RecommendationRequest;
use ZaiKorea\ZaiClient\Responses\RecommendationResponse;
use ZaiKorea\ZaiClient\Responses\EventLoggerResponse;
use ZaiKorea\ZaiClient\Security\ZaiHeaders;
use ZaiKorea\ZaiClient\Exceptions\ZaiClientException;
use ZaiKorea\ZaiClient\Exceptions\ZaiNetworkIOException;

use JsonMapper;
use ZaiKorea\ZaiClient\Configs\Config;

/**
 * Client for easy usage of Z.Ai recommendation API
 */
class ZaiClient
{
    private $zai_client_id;
    private $zai_secret;
    private $guzzle_client;
    private $json_mapper;


    public function __construct($client_id, $secret)
    {
        $this->zai_client_id = $client_id;
        $this->zai_secret = $secret;

        $this->guzzle_client = new \GuzzleHttp\Client();
        $this->json_mapper = new JsonMapper();
        //$this->json_mapper->bEnforceMapType = false;
    }

    /**
     * @param BaseEvent $event
     * @return int StatusCode
     */
    public function addEventLog($event)
    {
        $headers = ZaiHeaders::generateZaiHeaders(
            $this->zai_client_id,
            $this->zai_secret,
            Config::EVENTS_API_PATH
        );
        $body = json_encode($event->getPayload());

        $guzzle_request = new Request(
            'POST',
            Config::EVENTS_API_ENDPOINT . Config::EVENTS_API_PATH,
            $headers,
            Utils::streamFor($body)
        );

        try {
            $response = $this->guzzle_client->send($guzzle_request);
        } catch (RequestException $e) {
            throw new ZaiClientException($e->getMessage(), $e);
        } catch (TransferException $e) {
            throw new ZaiNetworkIOException($e->getMessage(), $e);
        }

        $response_body = json_decode($response->getBody());
        $eventlogger_response = $this->json_mapper->map($response_body, new EventLoggerResponse());
        return $eventlogger_response;
    }

    /**
     * @param BaseEvent $event
     * @return int StatusCode
     */
    public function updateEventLog($event)
    {
        if (is_array($event->getPayload()))
            throw new \InvalidArgumentException('Events with multiple payloads does not support updateEventLog operation');

        $headers = ZaiHeaders::generateZaiHeaders(
            $this->zai_client_id,
            $this->zai_secret,
            Config::EVENTS_API_PATH
        );
        $body = json_encode($event->getPayload());

        $guzzle_request = new Request(
            'PUT',
            Config::EVENTS_API_ENDPOINT . Config::EVENTS_API_PATH,
            $headers,
            Utils::streamFor($body)
        );

        try {
            $response = $this->guzzle_client->send($guzzle_request);
        } catch (RequestException $e) {
            throw new ZaiClientException($e->getMessage(), $e);
        } catch (TransferException $e) {
            throw new ZaiNetworkIOException($e->getMessage(), $e);
        }

        $response_body = json_decode($response->getBody());
        $eventlogger_response = $this->json_mapper->map($response_body, new EventLoggerResponse());
        return $eventlogger_response;
    }

    /**
     * @param BaseEvent $event
     * @return int StatusCode
     */
    public function deleteEventLog($event)
    {
        $headers = ZaiHeaders::generateZaiHeaders(
            $this->zai_client_id,
            $this->zai_secret,
            Config::EVENTS_API_PATH
        );
        $body = json_encode($event->getPayload());

        $guzzle_request = new Request(
            'DELETE',
            Config::EVENTS_API_ENDPOINT . Config::EVENTS_API_PATH,
            $headers,
            Utils::streamFor($body)
        );

        try {
            $response = $this->guzzle_client->send($guzzle_request);
        } catch (RequestException $e) {
            throw new ZaiClientException($e->getMessage(), $e);
        } catch (TransferException $e) {
            throw new ZaiNetworkIOException($e->getMessage(), $e);
        }

        $response_body = json_decode($response->getBody());
        $eventlogger_response = $this->json_mapper->map($response_body, new EventLoggerResponse());
        return $eventlogger_response;
    }

    /**
     *  Get recommendation from Z.Ai ML API server
     * 
     * @param RecommendationRequest $request Request can be one of <UserRecomendation | RelatedItems | Reranking>Recomendations
     * @return RecommendationResponse Json serializable class
     */
    public function getRecommendations($request)
    {
        $headers = ZaiHeaders::generateZaiHeaders(
            $this->zai_client_id,
            $this->zai_secret,
            $request->getPath($this->zai_client_id)
        );
        $body = json_encode($request);

        $guzzle_request = new Request(
            'POST',
            $request->getURIPath($this->zai_client_id),
            $headers,
            Utils::streamFor($body)
        );

        try {
            $response = $this->guzzle_client->send($guzzle_request);
        } catch (RequestException $e) {
            throw new ZaiClientException($e->getMessage(), $e);
        } catch (TransferException $e) {
            throw new ZaiNetworkIOException($e->getMessage(), $e);
        }

        $response_body = json_decode($response->getBody());
        $recommendation_response = $this->json_mapper->map($response_body, new RecommendationResponse());
        return $recommendation_response;
    }
}
