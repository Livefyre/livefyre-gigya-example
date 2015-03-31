<?php

// SET ALL AGAINST DOC ROOT
require_once '../../config/config.php';
require_once '../lib/gigya/GSSDK.php';
require_once '../lib/livefyre-php-utils/src/Livefyre.php';

use Livefyre\Core\Network as Network;

// WE CALL THIS ENDPOINT TO GENERATE THE LIVEFYRE TOKEN
// NOTE:
// LF requires URL-SAFE IDs; some chars in the GIGYA ID may not pass:
// specifically: '=', '/', '+'

// These functions might make that subsitution easier:
function base64_to_urlsafe_base64($uid) {
	return rtrim ( strtr ( base64_encode ( $uid ), '+/', '-_' ), '=' );
}
function urlsafe_base64_to_base64($uid) {
	$translations = array (
			'_gid_' => '_gid_', // case for gigya guids
			'-' => '+',
			'_' => '/' 
	);
	$uid = strtr ( base64_decode ( $uid ), $translations );
	
	// repad
	switch ($uid % 3) {
		case 1 :
			$uid .= "==";
			break;
		case 2 :
			$uid .= "=";
			break;
		default :
			break;
	}
	
	return $uid;
}

$response = array ();
try {
	// Confirm POST data is present
	$requiredKeys = array (
			'UID',
			'UIDSignature',
			'signatureTimestamp' 
	);
	foreach ( $requiredKeys as $key ) {
		if (empty ( $_POST [$key] )) {
			throw new InvalidArgumentException ( 'Missing parameter: ' . $key );
		}
	}
	
	// Validate user signature
	$validSignature = SigUtils::validateUserSignature ( $_POST ['UID'], $_POST ['signatureTimestamp'], GIGYA_SECRET, $_POST ['UIDSignature'] );
	if (! $validSignature) {
		throw new ErrorException ( 'Invalid user signature.' );
	}
	
	// Fetch user account info directly from Gigya
	// If accounts, it is preferable to use accounts.getAccountInfo generally but getUserInfo works for both implementations for basic info
	$request = new GSRequest ( GIGYA_API_KEY, GIGYA_SECRET, "socialize.getUserInfo" );
	$request->setParam ( "UID", $_POST ['UID'] );
	
	$userInfo = $request->send ();
	
	if ($userInfo->getErrorCode () != 0) {
		throw new ErrorException ( $userInfo->getErrorMessage () );
	}
	
	// Generate Livefyre authentication token
	$net = Network::init ( LIVEFYRE_NETWORK, LIVEFYRE_NETWORK_KEY );
	
	$GIGYA_UID = $userInfo->getString ( 'UID' );
	
	// THE STRING REPLACEMENT OPTION
	// which requires no extra storage on Gigya to have access
	$LF_SAFE_GIGYA_UID = base64_to_urlsafe_base64 ( $GIGYA_UID );
	
	// BUILD LIVEFYRE TOKEN USING LF-SAFE UID
	$token = $net->buildUserAuthToken ( $LF_SAFE_GIGYA_UID, $userInfo->getString ( 'nickname' ), SESSION_EXPIRATION );
	
	// for the sake of example:
	// UID, Gigya UID, and LF-URL-safe translated Gigya UID
	$response = array (
			'success' => true,
			'token' => $token,
			'GIGYA_UID,' => $GIGYA_UID,
			'LF_SAFE_UID' => $LF_SAFE_GIGYA_UID,
			'GIGYA_ACCT_DATA' => $LF_UPDATED_USER 
	);
} catch ( Exception $e ) {
	$response = array (
			'success' => false,
			'errorMessage' => $e->getMessage (),
			'UID' => $uid,
			'GIGYA_UID,' => $GIGYA_UID,
			'LF_SAFE_UID' => $LF_SAFE_GIGYA_UID,
			'GIGYA_ACCT_DATA' => $LF_UPDATED_USER 
	);
}

// // Return as JSON
header ( 'Content-Type: text/javascript; charset=utf8' );
header ( 'Access-Control-Allow-Methods: POST' );
header ( 'Access-Control-Max-Age: 1' );
header ( 'Access-Control-Allow-Origin: http://' . SITE_DOMAIN . '/' );
echo json_encode ( $response );
