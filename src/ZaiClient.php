<?php

/**
 *  Z.Ai API client
 *  @author Uiseop Eom <aesop@zaikorea.org>
 */


namespace ZaiClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Utils;
use JsonMapper;
use ZaiClient\Configs\Config;
use ZaiClient\Exceptions\ZaiClientException;
use ZaiClient\Exceptions\ZaiNetworkIOException;
use ZaiClient\Requests\BaseEvent;
use ZaiClient\Requests\Request;
use ZaiClient\Requests\RecommendationRequest;
use ZaiClient\Responses\EventLoggerResponse;
use ZaiClient\Responses\RecommendationResponse;
use ZaiClient\Security\ZaiHeaders;

/**
 * Client for easy usage of Z.Ai recommendation API
 */
class ZaiClient
{
    private $zai_client_id;
    private $zai_secret;
    private $guzzle_client;
    private $json_mapper;
    private $options;
    private $ml_api_endpoint;
    private $collector_api_endpoint;

    public function __construct($client_id, $secret, $options = array(), $client = null)
    {
        $this->zai_client_id = $client_id;
        $this->zai_secret = $secret;

        $this->json_mapper = new JsonMapper();
        $this->options = [
            'connect_timeout' => $this->resolveTimeoutOptions('connect_timeout', $options),
            'read_timeout' => $this->resolveTimeoutOptions('read_timeout', $options),
            'custom_endpoint' => $this->resolveEndpointOptions('custom_endpoint', $options)
        ];

        $this->ml_api_endpoint = sprintf(Config::ML_API_ENDPOINT, $this->options['custom_endpoint']);
        $this->collector_api_endpoint = sprintf(Config::EVENTS_API_ENDPOINT, $this->options['custom_endpoint']);

        if ($client && getenv('ZAI_CLIENT_TEST') == 'true')
            $this->guzzle_client = $client;
        else
            $this->guzzle_client = new \GuzzleHttp\Client();
    }

    /**
     * @param Request
     */

    /**
     * @param BaseEvent $event
     * @return mixed StatusCode
     */
    public function addEventLog($event)
    {
        $headers = ZaiHeaders::generateZaiHeaders(
            $this->zai_client_id,
            $this->zai_secret,
            Config::EVENTS_API_PATH
        );
        $body = json_encode($event->getPayload());

        try {
            $response = $this->guzzle_client->request(
                'POST',
                $this->collector_api_endpoint . Config::EVENTS_API_PATH,
                [
                    'headers' => $headers,
                    'body' => Utils::streamFor($body),
                    'connect_timeout' => $this->options['connect_timeout'],
                    'read_timeout' => $this->options['read_timeout']
                ]
            );
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

        try {
            $response = $this->guzzle_client->request(
                'PUT',
                $this->collector_api_endpoint . Config::EVENTS_API_PATH,
                [
                    'headers' => $headers,
                    'body' => Utils::streamFor($body),
                    'connect_timeout' => $this->options['connect_timeout'],
                    'read_timeout' => $this->options['read_timeout']
                ]
            );
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

        try {
            $response = $this->guzzle_client->request(
                'DELETE',
                $this->collector_api_endpoint . Config::EVENTS_API_PATH,
                [
                    'headers' => $headers,
                    'body' => Utils::streamFor($body),
                    'connect_timeout' => $this->options['connect_timeout'],
                    'read_timeout' => $this->options['read_timeout']
                ]
            );
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
        $path = $request->getPath($this->zai_client_id);
        $headers = ZaiHeaders::generateZaiHeaders(
            $this->zai_client_id,
            $this->zai_secret,
            $path
        );
        $body = json_encode($request);

        try {
            $response = $this->guzzle_client->request(
                'POST',
                $this->ml_api_endpoint . $path,
                [
                    'headers' => $headers,
                    'body' => Utils::streamFor($body),
                    'connect_timeout' => $this->options['connect_timeout'],
                    'read_timeout' => $this->options['read_timeout']
                ]
            );
        } catch (RequestException $e) {
            throw new ZaiClientException($e->getMessage(), $e);
        } catch (TransferException $e) {
            throw new ZaiNetworkIOException($e->getMessage(), $e);
        }

        $response_body = json_decode($response->getBody());
        $recommendation_response = $this->json_mapper->map($response_body, new RecommendationResponse());
        return $recommendation_response;
    }

    /**
     * Send Request to Zai API server
     *
     * @param Request $request
     */
    public function sendRequest($request, $options = ['is_test' => false])
    {
        $method = $request->getMethod();
        $path = $request->getPath($this->zai_client_id);
        $headers = ZaiHeaders::generateZaiHeaders(
            $this->zai_client_id,
            $this->zai_secret,
            $path
        );

        $body = json_encode(
            $request->getPayload(
                key_exists('is_test', $options) ? $options['is_test'] : false
            )
        );

        $url = sprintf(
            $request->getBaseUrl(), $this->options['custom_endpoint']
        ) . $path;

        try {
            $response = $this->guzzle_client->request(
                $method,
                $url,
                [
                    'headers' => $headers,
                    'body' => Utils::streamFor($body),
                    'connect_timeout' => $this->options['connect_timeout'],
                    'read_timeout' => $this->options['read_timeout']
                ]
            );
        } catch (RequestException $e) {
            throw new ZaiClientException($e->getMessage(), $e);
        } catch (TransferException $e) {
            throw new ZaiNetworkIOException($e->getMessage(), $e);
        }

        return $response;
    }

    public function resolveTimeoutOptions($key, $options)
    {
        if (isset($options[$key])) {
            if (!is_int($options[$key]))
                throw new \InvalidArgumentException('Timeout options should be an integer');

            if ($options[$key] > 0)
                return $options[$key];
        }

        if ($key == 'connect_timeout')
            return 10;

        if ($key == 'read_timeout')
            return 30;
    }

    public function resolveEndpointOptions($key, $options)
    {
        if (isset($options[$key])) {
            if (!is_string($options[$key]))
                throw new \InvalidArgumentException('Custom endpoint option must be a string');

            if (strlen($options[$key]) > 10)
                throw new \InvalidArgumentException('Custom endpoint should be less than or equal to 10.');

            $pattern = "/^[a-zA-Z0-9-]*$/";

            $is_match = preg_match($pattern, $options[$key]);

            if ($is_match)
                return "-" . $options[$key];
            else
                throw new \InvalidArgumentException('Only alphanumeric characters are allowed for custom endpoint.');
        }

        return "";
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getCollectorApiEndpoint()
    {
        return $this->collector_api_endpoint;
    }

    public function getMlApiEndpoint()
    {
        return $this->ml_api_endpoint;
    }
}
