<?php

// define('DOC_ROOT', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'));
// set_include_path(implode(PATH_SEPARATOR, array(
//     DOC_ROOT . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'livefyre', // Livefyre uses very generic file names and we want it high in our include structure.
//     DOC_ROOT . DIRECTORY_SEPARATOR . 'lib',
//     DOC_ROOT . DIRECTORY_SEPARATOR . 'inc',
//     get_include_path(),
// )));


// PUSH THESE VARS TO ENV
define('SITE_DOMAIN', 'localhost');
define('SESSION_EXPIRATION', 2592000); // 30 days

define('GIGYA_API_KEY',
	'');
define('GIGYA_SECRET', '');

define('LIVEFYRE_NETWORK', '');
define('LIVEFYRE_NETWORK_KEY', '');
define('LIVEFYRE_SITE_ID', '');
define('LIVEFYRE_SITE_KEY', '');

define('LIVEFYRE_COOKIE_NAME', 'livefyre_token');

// THIS VALUE IS STORED ON GIGYA: ACCOUNTINFO.DATA.LF_UID
define('LIVEFYRE_ID_FIELD', 'LF_UID');

