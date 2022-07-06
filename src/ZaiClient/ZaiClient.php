<?php
/**
 *  Z.Ai API client
 *  @author Uiseop Eom <aesop@zaikorea.org>
 */


namespace ZaiKorea\ZaiClient;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Exception\RequestException;

use ZaiKorea\ZaiClient\Requests\RecommendationRequest;
use ZaiKorea\ZaiClient\Responses\RecommendationResponse;
use ZaiKorea\ZaiClient\Security\ZaiHeaders;

use JsonMapper;

/**
 * Client for easy usage of Z.Ai recommendation API
 */
class ZaiClient {
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
        $this->json_mapper->bEnforceMapType = false;
    }

    /**
     * @param RecommendationRequest $request
     * @return RecommendationResponse Used
     */
    public function getRecommendations($request) {
        $headers = ZaiHeaders::generateZaiHeaders($this->zai_client_id, $this->zai_secret, $request->getPath($this->zai_client_id));
        $body = json_encode($request);

        $guzzle_request = new Request('POST', $request->getURIPath($this->zai_client_id), $headers, Utils::streamFor($body));

        try {
            $response = $this->guzzle_client->send($guzzle_request);
        }
        catch(RequestException $e) {
            echo "\nMessage: " .$e->getMessage() . "\n";
            echo $e->getRequest()->getBody() . "\n";
        } 

        $response_body = json_decode($response->getBody());
        $recommendation_response = $this->json_mapper->map($response_body, new RecommendationResponse());
        return $recommendation_response;
    }
}
?>
