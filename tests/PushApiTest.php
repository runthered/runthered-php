<?php
namespace RtrPushApi;

$response = '';
$http_code = '';

require_once('runthered/push_api.php');

function curl_exec($ch){
	global $response;
	return $response;
}

function curl_getinfo($ch){
	global $http_code;
	return $http_code;
}

class PushApiTest extends \PHPUnit_Framework_TestCase{
	public function testPushApiSuccess(){
		global $response, $http_code;
		$response = '{"jsonrpc": "2.0", "id": 12345, "result": {"status": "Accepted", "msg_id": "515cabc3464af599972c65bc"}}';
		$http_code = array("http_code"=>'200');
	
	    $username = 'testuser';
        $password = 'testuser';
        $service_key = '82221';
		$pushApi = new PushApi($username, $password, $service_key);
		
		$to = '6421859582';
        $from = '8222';
        $body = 'Hello World!';
        $id = 12345;
		
		$response = $pushApi->pushMessage($body,$to,$from,$id);
				
		$this->assertEquals($response->msg_id,"515cabc3464af599972c65bc");
		$this->assertEquals($response->status,"Accepted");
		$this->assertEquals($response->id,12345);
	}
	
	public function testPushApiDlrSuccess(){
		global $response, $http_code;
		$response = '{"jsonrpc": "2.0", "id": 12345, "result": {"status": "DELIVRD", "reason": "000", "msg_id": "55c3f9d7e138234bcb3d8b20"}}';
		$http_code = array("http_code"=>'200');
	
	    $username = 'testuser';
        $password = 'testuser';
        $service_key = '82221';
		$pushApi = new PushApi($username, $password, $service_key);
				
        $msg_id = "515cabc3464af599972c65bc";
        $id = 12345;
		
		$response = $pushApi->queryDlr($msg_id,$id);
				
		$this->assertEquals($response->status,"DELIVRD");
		$this->assertEquals($response->reason_code,"000");
		$this->assertEquals($response->id,12345);
	}
	
	public function testPushApiUnauthorised(){
		global $response, $http_code;
		$response = '{"jsonrpc": "2.0", "id": 12345, "result": {"status": "Accepted", "msg_id": "515cabc3464af599972c65bc"}}';
		$http_code = array("http_code"=>'401');
	
	    $username = 'testuser';
        $password = 'testuser';
        $service_key = '82221';
		$pushApi = new PushApi($username, $password, $service_key);
		
		$to = '6421859582';
        $from = '8222';
        $body = 'Hello World!';
        $id = 12345;
		
		$this->setExpectedException('RtrPushApi\PushApiException');
		
		$response = $pushApi->pushMessage($body,$to,$from,$id);
				
		
	}
	
	public function testPushApiDlrUnauthorised(){
		global $response, $http_code;
		$response = '{"jsonrpc": "2.0", "id": 12345, "result": {"status": "DELIVRD", "reason": "000", "msg_id": "55c3f9d7e138234bcb3d8b20"}}';
		$http_code = array("http_code"=>'401');
	
	    $username = 'testuser';
        $password = 'testuser';
        $service_key = '82221';
		$pushApi = new PushApi($username, $password, $service_key);
				
        $msg_id = "515cabc3464af599972c65bc";
        $id = 12345;
        
        $this->setExpectedException('RtrPushApi\PushApiException');
		
		$response = $pushApi->queryDlr($msg_id,$id);
				
	}
	
	public function testPushApiError(){
		global $response, $http_code;
		$response = '{"jsonrpc": "2.0", "id": 12345, "error": {"message": "Invalid shortcode.", "code": -1}}';
		$http_code = array("http_code"=>'200');
	
	    $username = 'testuser';
        $password = 'testuser';
        $service_key = '82221';
		$pushApi = new PushApi($username, $password, $service_key);
		
		$to = '6421859582';
        $from = '8222';
        $body = 'Hello World!';
        $id = 12345;
		
		$this->setExpectedException('RtrPushApi\PushApiException');
		
		$response = $pushApi->pushMessage($body,$to,$from,$id);
		
	}
	
	public function testPushApiDlrError(){
		global $response, $http_code;
		$response = '{"jsonrpc": "2.0", "id": 12345, "error": {"message": "Unknown Message Id.", "code": -11}}';
		$http_code = array("http_code"=>'200');
	
	    $username = 'testuser';
        $password = 'testuser';
        $service_key = '82221';
		$pushApi = new PushApi($username, $password, $service_key);
				
        $msg_id = "515cabc3464af599972c65bc";
        $id = 12345;
		
		$this->setExpectedException('RtrPushApi\PushApiException');
		
		$response = $pushApi->queryDlr($msg_id,$id);
	
	}
	
}

?>
