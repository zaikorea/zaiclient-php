<?php

/**
 * Config
 * @author Uiseop Eom <tech@zaikorea.org>
 * @modifiedBy <name>
 */

namespace ZaiKorea\ZaiClient\Configs;

class Config
{
    const EVENTS_API_ENDPOINT = 'https://collector-api.zaikorea.org';
    const EVENTS_API_PATH = '/events';

    const ML_API_ENDPOINT = 'https://ml-api.zaikorea.org';
    const ML_API_PATH_PREFIX = '/clients/%s/recommenders';

    const HMAC_ALGORITHM = 'sha256';
    const HMAC_SCHEME = 'ZAi';
    const ZAI_CLIENT_ID_HEADER = 'X-ZAI-CLIENT-ID';
    const ZAI_UNIX_TIMESTAMP_HEADER = 'X-ZAI-TIMESTAMP';
    const ZAI_AUTHORIZATION_HEADER = 'X-ZAI-AUTHORIZATION';
    const ZAI_CALL_TYPE_HEADER = 'X-ZAI-CALL-TYPE';
    const ZAI_CALL_TYPE = 'sdk_call';

    const BATCH_REQUEST_CAP = '50';
    const EPSILON = 1e-4;

    /* ----------------- Error Messages ----------------- */
    const NON_STR_ARG_ERRMSG = '%s:%s expects parameter %d to be a string.';
    const NULL_ARG_ERRMSG = '%s:%s expects parameter %d to be a string or non-empty array.';
    const EMPTY_ARR_ERRMSG = '%s:%s expects parameter %d to be a non-empty array.';
    const NON_SEQ_ARR_ERRMSG = '%s:%s expects parameter %d to be a sequential array.';
    const ARR_FORM_ERRMSG = '%s:%s expects parameter %d to be an array of form %s.';
}
