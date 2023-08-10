<?php

/**
 * Config
 * @author Uiseop Eom <tech@zaikorea.org>
 * @modifiedBy <name>
 */

namespace ZaiClient\Configs;

class Config
{
    const EVENTS_API_ENDPOINT = "https://collector-api%s.zaikorea.org";
    const COLLECTOR_API_ENDPOINT = "https://collector-api%s.zaikorea.org";

    const EVENTS_API_PATH = '/events';
    const ITEMS_API_PATH = '/items';

    const ML_API_ENDPOINT = "https://ml-api%s.zaikorea.org";
    const ML_API_PATH_PREFIX = '/clients/%s/recommenders';

    const USER_RECOMMENDATION_PATH = '/user-recommendations';
    const RELATED_ITEMS_PATH = "/related-items";

    const RERANKING_RECOMMENDATION_PATH = "/reranking";
    const RERANKING_RECOMMENDATION_PATH_PREFIX = "/reranking";

    const HMAC_ALGORITHM = 'sha256';
    const HMAC_SCHEME = 'ZAi';
    const ZAI_CLIENT_ID_HEADER = 'X-ZAI-CLIENT-ID';
    const ZAI_UNIX_TIMESTAMP_HEADER = 'X-ZAI-TIMESTAMP';
    const ZAI_AUTHORIZATION_HEADER = 'X-ZAI-AUTHORIZATION';
    const BATCH_REQUEST_CAP = '50';
    const EPSILON = 1e-4;

    /* ----------------- Error Messages ----------------- */
    const NON_STR_ARG_ERRMSG = '%s:%s expects parameter %d to be a non-empty string.';
    const EMPTY_ARR_ERRMSG = '%s:%s expects parameter %d to be a non-empty array.';
    const NON_SEQ_ARR_ERRMSG = '%s:%s expects parameter %d to be a sequential array.';
    const ARR_FORM_ERRMSG = '%s:%s expects parameter %d to be an array of form %s.';
    const BATCH_ERRMSG = '%s does not support batch processing.';

    const TEST_EVENT_TIME_TO_LIVE = 60 * 60 * 24; // 1 day
}
