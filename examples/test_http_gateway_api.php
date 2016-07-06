<?php
require_once('runthered/http_gateway.php');
//If using Composer then you don't need the above, and can instead just use autoload.php:
//require __DIR__ . '/vendor/autoload.php';
use RtrHttpGateway\HttpGatewayApi;
use RtrHttpGateway\HttpGatewayException;

try{
	$username = 'snoop7';
	$password = 'snoop7';
	$service_key = 'snop7';
	$httpGatewayApi = new HttpGatewayApi($username,$password,$service_key);

	$to = '6421859582';
	$from = '2059';
	$message = 'Hello World!';

	$response = $httpGatewayApi->pushMessage($message, $to, $from);
	echo "The msg_id is $response\n";

	$msg_id = '55f8d193e13823069edbdfdd';
	$dlr_response = $httpGatewayApi->queryDlr($msg_id);
	echo "The status is $dlr_response->status\n";
	echo "The reason is $dlr_response->reason_code\n";
	echo "The dlr id is $dlr_response->id\n";


	// send messages in bulk
	$to_numbers = array();
	for($i=0; $i<100; $i++){
        	array_push($to_numbers, '64251234567');
	}	
	$responses = $httpGatewayApi->pushToMany($message, $to_numbers, $from);
	foreach($responses as $response){
        	echo "The response data is response " . $response->response . " and http code " . $response->http_code . "\n";
	}

	
} catch (HttpGatewayException $e){
	echo 'Caught exception: ',  $e->getMessage(), " and code ", $e->getCode(), "\n";
}

?>
