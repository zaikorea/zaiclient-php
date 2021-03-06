<?php

/**
 * Util
 * @author Uiseop Eom <tech@zaikorea.org>
 */

namespace ZaiKorea\ZaiClient\Security;

use ZaiKorea\ZaiClient\Configs\Config;

/**
 * Utils
 */
class ZaiHeaders
{

    public static function generateZaiHeaders($zai_client_id, $zai_secret, $path)
    {
        $unix_timestamp = strval(time());
        $zai_token = hash_hmac(Config::HMAC_ALGORITHM, $path . ":" . $unix_timestamp, $zai_secret);
        $zai_headers = array();

        $zai_headers[Config::ZAI_CLIENT_ID_HEADER] = $zai_client_id;
        $zai_headers[Config::ZAI_UNIX_TIMESTAMP_HEADER] = $unix_timestamp;
        $zai_headers[Config::ZAI_AUTHORIZATION_HEADER] = Config::HMAC_SCHEME . " " . $zai_token;
        $zai_headers[Config::ZAI_CALL_TYPE_HEADER] = Config::ZAI_CALL_TYPE;

        return $zai_headers;
    }
}
