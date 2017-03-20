<?php
date_default_timezone_set('Asia/Chongqing');
error_reporting(0);
ini_set('display_errors','off');

include './vendor/autoload.php';

use \Firebase\JWT\JWT;
// use \Zend\Config\Config;
// use \Zend\Config\Factory;
use \Zend\Http\PhpEnvironment\Request;

$secretKey = md5('zengyi');

if (false) {
	$tokenId	= base64_encode('asdfas');
	$issuedAt   = time();
	$notBefore  = $issuedAt ;			 //Adding 10 seconds
	$expire	 = $notBefore + 3600;			// Adding 60 seconds
	// $serverName = $config->get('serverName'); // Retrieve the server name from config file
	/*
	 * Create the token as an array
	 */
	$data = [
		'iat'  => $issuedAt,		 // Issued at: time when the token was generated
		'jti'  => $tokenId,		  // Json Token Id: an unique identifier for the token
		'iss'  => $serverName,	   // Issuer
		'nbf'  => $notBefore,		// Not before
		'exp'  => $expire,		   // Expire
		'data' => [				  // Data related to the signer user
			'userId'   => 124124, // userid from the users table
			'userName' => 'juyuan', // User name
		]
	];
	    
    /*
     * Encode the array to a JWT string.
     * Second parameter is the key to encode the token.
     * 
     * The output string can be validated at http://jwt.io/
     */
    $jwt = JWT::encode(
        $data,      //Data to be encoded in the JWT
        $secretKey, // The signing key
        'HS256'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
        );
        
    $unencodedArray = ['jwt' => $jwt];
    echo json_encode($unencodedArray);exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){

	$request = new Request();


	if ($request->isPost()) {
		$authHeader = $request->getHeader('authorization');
		if($authHeader){
			list($jwt) = sscanf($authHeader->toString(), 'Authorization: Bearer %s');
			if($jwt){
				try {

					$decoded = JWT::decode($jwt,$secretKey,array('HS256'));
					var_dump($decoded);
				} catch (Exception $e) {
					var_dump($e->getMessage());
				}
			}
		}
	}
	

}

 

