<?php
// Email Pas respondre
define('EMAIL_PAS_RESPONDRE', "ne-pas-repondre@your-business.fr");

// Email default test
define('EMAIL_TO_TEST', 'test.email@email-provider.com');

// Maximum number of ours before the token for a new password gets expired
define('HOURS_TO_EXPIRE_TOKEN_NEW_PASS', '48');

// App Name
define('APP_NAME', 'App Starter');

function uc_first($str) {
    return mb_convert_case(mb_strtolower($str), MB_CASE_TITLE, "UTF-8");
}

function uc_words($str) {
    return mb_convert_case(mb_strtolower($str), MB_CASE_TITLE, "UTF-8");
}

function strip_tags_sp($html) {
    return preg_replace('#<[^>]+>#', ' ', $html);
}
