<?php

// define('DOC_ROOT', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'));
// set_include_path(implode(PATH_SEPARATOR, array(
//     DOC_ROOT . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'livefyre', // Livefyre uses very generic file names and we want it high in our include structure.
//     DOC_ROOT . DIRECTORY_SEPARATOR . 'lib',
//     DOC_ROOT . DIRECTORY_SEPARATOR . 'inc',
//     get_include_path(),
// )));

define('SITE_DOMAIN', '<INSERT-YOUR-WEBSITE-DOMAIN-HERE>');
define('SESSION_EXPIRATION', 2592000); // 30 days

define('GIGYA_API_KEY', '<INSERT-YOUR-GIGYA-API-KEY-HERE>');
define('GIGYA_SECRET', '<INSERT-YOUR-GIGYA-SECRET-KEY-HERE>');

define('LIVEFYRE_NETWORK', '<INSERT-YOUR-LIVEFYRE-NETWORK-HERE>');
define('LIVEFYRE_NETWORK_KEY', '<INSERT-YOUR-LIVEFYRE-NETWORK-KEY-HERE>');
define('LIVEFYRE_SITE_ID', '<INSERT-YOUR-LIVEFYRE-SITE-ID-HERE>');
define('LIVEFYRE_SITE_KEY', '<INSERT-YOUR-LIVEFYRE-SITE-KEY-HERE>');

define('LIVEFYRE_COOKIE_NAME', 'livefyre_token');
