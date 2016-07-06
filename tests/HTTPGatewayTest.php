<?php
namespace RtrHttpGateway;

$response = '';
$http_code = '';

require_once('runthered/http_gateway.php');

function curl_exec($ch){
	global $response;
	return $response;
}

function curl_getinfo($ch){
	global $http_code;
	return $http_code;
}

class HTTPGatewayTest extends \PHPUnit_Framework_TestCase{
	public function testHTTPGatewaySuccess(){
		global $response, $http_code;
		$response = '515cabc3464af599972c65bc';
		$http_code = array("http_code"=>'200');
	
	    $username = 'testuser';
        $password = 'testuser';
        $service_key = '82221';
		$httpGatewayApi = new HttpGatewayApi($username, $password, $service_key);
		
		$to = '6421859582';
        $from_number = '8222';
        $message = 'Hello World!';
        		
		$response = $httpGatewayApi->pushMessage($message, $to, $from_number);
				
		$this->assertEquals($response,"515cabc3464af599972c65bc");
		
	}

	public function testHTTPGatewayPushToManySuccess(){
                global $response, $http_code;
                $response = '515cabc3464af599972c65bc';
                $http_code = array("http_code"=>'200');

      		$username = 'testuser';
	        $password = 'testuser';
       		$service_key = '82221';
                $httpGatewayApi = new HttpGatewayApi($username, $password, $service_key);

                $to = '6421859582';
		$to2 = '6421859583';
		$to_numbers = array($to, $to2);

	        $from_number = '8222';
	        $message = 'Hello World!';

                $responses = $httpGatewayApi->pushToMany($message, $to_numbers, $from_number);
		foreach($responses as $response){
	                $this->assertEquals($response->response,"515cabc3464af599972c65bc");
			$this->assertEquals($response->http_code,"200");
			
		}
        }

	public function testHTTPGatewayPushToManyUnauthorised(){
                global $response, $http_code;
                $response = 'Unauthorised';
                $http_code = array("http_code"=>'401');

                $username = 'testuser';
                $password = 'testuser';
                $service_key = '82221';
                $httpGatewayApi = new HttpGatewayApi($username, $password, $service_key);

                $to = '6421859582';
                $to2 = '6421859583';
                $to_numbers = array($to, $to2);

                $from_number = '8222';
                $message = 'Hello World!';

                $responses = $httpGatewayApi->pushToMany($message, $to_numbers, $from_number);
                foreach($responses as $response){
                        $this->assertEquals($response->response,"Unauthorised");
                        $this->assertEquals($response->http_code,"401");

                }
        }

	
	public function testHTTPGatewayDlrSuccess(){
		global $response, $http_code;
		$response = '{"id": "515cabc3464af599972c65bc", "status": "DELIVRD", "reason": "000"}';
		$http_code = array("http_code"=>'200');
	
	    $username = 'testuser';
        $password = 'testuser';
        $service_key = '82221';
		$httpGatewayApi = new HttpGatewayApi($username, $password, $service_key);
				
        $msg_id = "515cabc3464af599972c65bc";
        		
		$response = $httpGatewayApi->queryDlr($msg_id);
				
		$this->assertEquals($response->status,"DELIVRD");
		$this->assertEquals($response->reason_code,"000");
		$this->assertEquals($response->id,"515cabc3464af599972c65bc");
	}
	
	public function testHTTPGatewayUnauthorised(){
		global $response, $http_code;
		$response = 'Unauthorized';
		$http_code = array("http_code"=>'401');
	
	    $username = 'testuser';
        $password = 'testuser';
        $service_key = '82221';
		$httpGatewayApi = new HttpGatewayApi($username, $password, $service_key);
		
		$to = '6421859582';
        $from_number = '8222';
        $message = 'Hello World!';
        		
		$this->setExpectedException('RtrHttpGateway\HttpGatewayException');
		
		$response = $httpGatewayApi->pushMessage($message, $to, $from_number);
				
		
	}
	
	public function testHTTPGatewayDlrUnauthorised(){
		global $response, $http_code;
		$response = 'Unauthorized';
		$http_code = array("http_code"=>'401');
	
	    $username = 'testuser';
        $password = 'testuser';
        $service_key = '82221';
		$httpGatewayApi = new HttpGatewayApi($username, $password, $service_key);
				
        $msg_id = "515cabc3464af599972c65bc";
                
        $this->setExpectedException('RtrHttpGateway\HttpGatewayException');
		
		$response = $httpGatewayApi->queryDlr($msg_id);
				
	}
	
	public function testHTTPGatewayError(){
		global $response, $http_code;
		$response = 'Invalid mobile number: no to number';
		$http_code = array("http_code"=>'400');
	
	    $username = 'testuser';
        $password = 'testuser';
        $service_key = '82221';
		$httpGatewayApi = new HttpGatewayApi($username, $password, $service_key);
		
		$to = '';
        $from_number = '8222';
        $message = 'Hello World!';
        		
		$this->setExpectedException('RtrHttpGateway\HttpGatewayException');
		
		$response = $httpGatewayApi->pushMessage($message, $to, $from_number);
		
	}
	
	public function testHTTPGatewayDlrError(){
		global $response, $http_code;
		$response = '{"message": "Invalid Message Id: The supplied message id is invalid", "code": 400}';
		$http_code = array("http_code"=>'400');
	
	    $username = 'testuser';
        $password = 'testuser';
        $service_key = '82221';
		$httpGatewayApi = new HttpGatewayApi($username, $password, $service_key);
				
        $msg_id = "515cabc3464af599972c65bc";
        		
		$this->setExpectedException('RtrHttpGateway\HttpGatewayException');
		
		$response = $httpGatewayApi->queryDlr($msg_id);
	
	}
	
}

?>
