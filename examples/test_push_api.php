<?php
require_once('runthered/push_api.php');
//If using Composer then you don't need the above, and can instead just use autoload.php:
//require __DIR__ . '/vendor/autoload.php';
use RtrPushApi\PushApi;
use RtrPushApi\PushApiException;

try{
	$username = 'testuser';
	$password = 'testuser';
	$service_key = '82221';
	$pushApi = new PushApi($username,$password,$service_key);

	$to = '6421859582';
	$from = '8222';
	$body = 'Hello World!';
	$id = 12345;
	$response = $pushApi->pushMessage($body, $to, $from, $id);
	echo "The msg_id is $response->msg_id\n";
	echo "The status is $response->status\n";
	echo "The id is $response->id\n";

	$msg_id = '55f8d226e13823069edbdfe2';
	$dlr_id = 12346;
	$dlr_response = $pushApi->queryDlr($msg_id,$dlr_id);
	echo "The status is $dlr_response->status\n";
	echo "The reason is $dlr_response->reason_code\n";
	echo "The dlr id is $dlr_response->id\n";
} catch (PushApiException $e){
	echo 'Caught exception: ',  $e->getMessage(), " and code ", $e->getCode(), "\n";
}

?>
