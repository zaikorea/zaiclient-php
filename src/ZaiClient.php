<?php

/**
 *  Z.Ai API client
 *  @author Uiseop Eom <aesop@zaikorea.org>
 */


namespace ZaiClient;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Query;
use GuzzleHttp\Psr7\Utils;
use JsonMapper;
use BadMethodCallException;
use ZaiClient\Configs\Config;
use ZaiClient\Exceptions\ZaiClientException;
use ZaiClient\Exceptions\ZaiNetworkIOException;
use ZaiClient\Requests\BaseEvent;
use ZaiClient\Requests\Events\EventRequest;
use ZaiClient\Requests\Items\ItemRequest;
use ZaiClient\Requests\Recommendations\RecommendationRequest;
use ZaiClient\Requests\Request;
use ZaiClient\Responses\EventResponse;
use ZaiClient\Responses\ItemResponse;
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

        if ($client && getenv('ZAI_CLIENT_TEST') == 'true') {
            $this->guzzle_client = $client;
        } else {
            $this->guzzle_client = new \GuzzleHttp\Client();
        }
    }

    /**
     * Send Request to Zai API server
     *
     * @param Request|EventRequest|ItemRequest|RecommendationRequest $request
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

        try{
            $query = Query::build(
                $request->getQueryParams()
            );
        } catch (BadMethodCallException $e) {
            $query = null;
        }

        $url = sprintf(
            $request->getBaseUrl(), $this->options['custom_endpoint']
        ) . $path;

        try {
            $response = $this->guzzle_client->request(
                $method,
                $url,
                [
                    'headers' => $headers,
                    'query' => $query,
                    'body' => Utils::streamFor($body),
                    'connect_timeout' => $this->options['connect_timeout'],
                    'read_timeout' => $this->options['read_timeout'],
                ]
            );
        } catch (RequestException $e) {
            throw new ZaiClientException($e->getMessage(), $e);
        } catch (TransferException $e) {
            throw new ZaiNetworkIOException($e->getMessage(), $e);
        }

        if (is_a($request, EventRequest::class)) {
            $response_body = json_decode($response->getBody());
            $event_response = $this->json_mapper->map($response_body, new EventResponse());
            return $event_response;
        }

        if (is_a($request, ItemRequest::class)) {
            $response_body = json_decode($response->getBody());
            $item_response = $this->json_mapper->map($response_body, new ItemResponse());
            return $item_response;
        }

        if (is_a($request, RecommendationRequest::class)) {
            $response_body = json_decode($response->getBody());
            $recommendation_response = $this->json_mapper->map($response_body, new RecommendationResponse());
            return $recommendation_response;
        }
    }

    public function resolveTimeoutOptions($key, $options)
    {
        if (isset($options[$key])) {
            if (!is_int($options[$key])) {
                throw new \InvalidArgumentException('Timeout options should be an integer');
            }

            if ($options[$key] > 0) {
                return $options[$key];
            }
        }

        if ($key == 'connect_timeout') {
            return 10;
        }

        if ($key == 'read_timeout') {
            return 30;
        }
    }

    public function resolveEndpointOptions($key, $options)
    {
        if (isset($options[$key])) {
            if (!is_string($options[$key])) {
                throw new \InvalidArgumentException('Custom endpoint option must be a string');
            }

            if (strlen($options[$key]) > 10) {
                throw new \InvalidArgumentException('Custom endpoint should be less than or equal to 10.');
            }

            $pattern = "/^[a-zA-Z0-9-]*$/";

            $is_match = preg_match($pattern, $options[$key]);

            if ($is_match) {
                return "-" . $options[$key];
            } else {
                throw new \InvalidArgumentException('Only alphanumeric characters are allowed for custom endpoint.');
            }
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
