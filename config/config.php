<?php

// define('DOC_ROOT', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'));
// set_include_path(implode(PATH_SEPARATOR, array(
//     DOC_ROOT . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'livefyre', // Livefyre uses very generic file names and we want it high in our include structure.
//     DOC_ROOT . DIRECTORY_SEPARATOR . 'lib',
//     DOC_ROOT . DIRECTORY_SEPARATOR . 'inc',
//     get_include_path(),
// )));


// PUSH THESE VARS TO ENV
define('SITE_DOMAIN', '<INSERT-YOUR-WEBSITE-DOMAIN-HERE>');
define('SESSION_EXPIRATION', 2592000); // 30 days

define('GIGYA_API_KEY',
	'2_yM8ACPAsEmp9GNWZaf9pms70VCJ317NzLHdkDRNHsAwIgLFrt1xKmOjmqt1lnTS1');
define('GIGYA_SECRET', 'Nl41R4RNYQLfRHXBa0qmX6Jlnn8/LDmLj7NPMEB+bwo=');

define('LIVEFYRE_NETWORK', 'gigya-0.fyre.co');
define('LIVEFYRE_NETWORK_KEY', 'tZ+tcybHzrMNnfJPnL8TB/3ZHok=');
define('LIVEFYRE_SITE_ID', '303862');
define('LIVEFYRE_SITE_KEY', 'SD58lOlEAMpB93j1NzCdqYX1xdw=');

define('LIVEFYRE_COOKIE_NAME', 'livefyre_token');
