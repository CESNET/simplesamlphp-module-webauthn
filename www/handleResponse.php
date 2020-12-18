<?php

use Jose\Component\Core\JWK;
use Jose\Component\Core\JWT;
use Jose\Easy\Load;
use Jose\Component\Core\JWKSet;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Easy\Build;

if (!array_key_exists('StateId', $_REQUEST)) {
    throw new \SimpleSAML\Error\BadRequest(
        'Missing required StateId query parameter.'
    );
}
$original_nonce = urldecode($_REQUEST['StateId']);
$nonce_array = explode(";", $original_nonce);
$id = $nonce_array[0];
$state = \SimpleSAML\Auth\State::loadState($id, 'webauthn:request');
if (is_null($state)) {
    throw new \SimpleSAML\Error\NoState;
} elseif (array_key_exists('core:SP', $state)) {
    $spentityid = $state['core:SP'];
} elseif (array_key_exists('saml:sp:State', $state)) {
    $spentityid = $state['saml:sp:State']['core:SP'];
} else {
    $spentityid = 'UNKNOWN';
}

$user_id = $state["api_user_id"];
$actual_time = strval(time());
$request = ["user_id" => $user_id, "nonce" => $original_nonce, "time" => $actual_time];

$jwk = JWKFactory::createFromKeyFile(
    $state["signing_key"], // The filename
    NULL,                   // Secret if the key is encrypted
    [
        'use' => 'sig',         // Additional parameters
    ]
);
$jws = Build::jws() // We build a JWS
    ->alg('RS256') // The signature algorithm. A string or an algorithm class.
    ->payload($request)
    ->sign($jwk); // Compute the token with the given JWK

$api_url = $state["api_url"] . "/" . $jws;
$response_json = file_get_contents($api_url);
$response = json_decode($response_json, true);

if ($response["result"] != "okay" or $response["nonce"] != $original_nonce) {
    throw new Exception("The authentication was unsuccessful");
}

\SimpleSAML\Auth\ProcessingChain::resumeProcessing($state);

