<?php

// SET ALL AGAINST DOC ROOT
require_once '../../config/config.php';
require_once '../lib/gigya/GSSDK.php';
require_once '../livefyre-php-utils/src/Livefyre.php';

$response = array();
try {
  // Confirm POST data is present
  $requiredKeys = array('UID', 'UIDSignature', 'signatureTimestamp');
  foreach($requiredKeys as $key) {
    if(empty($_POST[$key])) {
      throw new InvalidArgumentException('Missing parameter: ' . $key);
    }
  }

  // Validate user signature
  $validSignature = SigUtils::validateUserSignature($_POST['UID'], $_POST['signatureTimestamp'], GIGYA_SECRET, $_POST['UIDSignature']);
  if(!$validSignature) {
    throw new ErrorException('Invalid user signature.');
  }

  // Fetch user account info directly from Gigya
  // If accounts, it is preferable to use accounts.getAccountInfo generally but getUserInfo works for both implementations for basic info
$request = new GSRequest(GIGYA_API_KEY , GIGYA_SECRET, "socialize.getUserInfo");
$request->setParam("UID",$_POST['UID']);
$userInfo = $request->send();
  if($userInfo->getErrorCode() != 0) {
    throw new ErrorException($userInfo->getErrorMessage());
  }



  // TODO:
  // replace with new token from LF php utils Token.php
  // Generate Livefyre authentication
  $livefyre = new Livefyre_Domain(LIVEFYRE_NETWORK, LIVEFYRE_NETWORK_KEY);
  $token = $livefyre->user($userInfo->getString('UID'), $userInfo->getString('nickname'))->token(SESSION_EXPIRATION);
  $response = array(
    'success'   => true,
    'token'     => $token,
  );
} catch(Exception $e) {
  $response = array(
    'success'       => false,
    'errorMessage'  => $e->getMessage(),
  );
}

// Return as JSON
header('Content-Type: text/javascript; charset=utf8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1');
header('Access-Control-Allow-Origin: http://' . SITE_DOMAIN . '/');
echo json_encode($response);
