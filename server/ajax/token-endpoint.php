<?php

// SET ALL AGAINST DOC ROOT
require_once '../../config/config.php';
require_once '../lib/gigya/GSSDK.php';
require_once '../lib/livefyre-php-utils/src/Livefyre.php';

use Livefyre\Core\Network as Network;
use Livefyre\Utils\LivefyreUtils as LFU;


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


  $uid = $_POST['UID'];

  // Fetch user account info directly from Gigya
  // If accounts, it is preferable to use accounts.getAccountInfo generally but getUserInfo works for both implementations for basic info
  $request = new GSRequest(GIGYA_API_KEY , GIGYA_SECRET, "socialize.getUserInfo");
  $request->setParam("UID", $uid);

  $userInfo = $request->send();
    if($userInfo->getErrorCode() != 0) {
      throw new ErrorException($userInfo->getErrorMessage());
    }

    // Generate Livefyre authentication token
    $net = Network::init(LIVEFYRE_NETWORK, LIVEFYRE_NETWORK_KEY);

    $gigyaUID = LFU::base64_to_urlsafe_base64($userInfo->getString('UID'));

    $token = $net->buildUserAuthToken($gigyaUID,
        $userInfo->getString('nickname'), SESSION_EXPIRATION);

    // sending UID and Gigya UID
    $response = array(
      'success'   => true,
      'token'     => $token,
      'UID'       => $uid,
      'gigyaUID'    => $gigyaUID,
    );

  } catch(Exception $e) {
    $response = array(
      'success'       => false,
      'errorMessage'  => $e->getMessage(),
      'UID'       => $uid,
      'gigyaUID'    => $gigyaUID,
    );
  }

// // Return as JSON
header('Content-Type: text/javascript; charset=utf8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1');
header('Access-Control-Allow-Origin: http://' . SITE_DOMAIN . '/');
echo json_encode($response);
