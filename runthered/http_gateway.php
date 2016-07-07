<?php
namespace RtrHttpGateway;

class DlrQueryResponse {
	public $status;
        public $reason_code;
	public $id;

        public function __construct($status, $reason_code, $id){
                $this->status = $status;
                $this->reason_code = $reason_code;
		$this->id = $id;
        }

}

class ResponseData {
	public $response;
	public $http_code;

	public function __construct($response, $http_code){
                $this->response = $response;
                $this->http_code = $http_code;
        }

} 

class HttpGatewayException extends \Exception{
	
}


class HttpGatewayApi {
	private $url;
	private $dlr_url;
	private $username;
	private $password;
	private $service_key;
	
	public function __construct($username, $password, $service_key, $url='https://connect.runthered.com:14004/public_api/sms/gateway/', $dlr_url='https://connect.runthered.com:14004/public_api/sms/dlr/'){
		$this->url = $url;
		$this->dlr_url = $dlr_url;
		$this->username = $username;
		$this->password = $password;
		$this->service_key = $service_key;
	}
	
	private function doRequest($data_to_submit, $url, $method='POST'){
		if($method == 'GET'){
			$url = $url . '?' . http_build_query($data_to_submit);
		}

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		if($method == 'POST'){
			curl_setopt($ch,CURLOPT_POST, sizeof($data_to_submit));
                	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_to_submit);
		}
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
                $output = curl_exec($ch);
                $info = curl_getinfo($ch);
                curl_close($ch);
                $http_code = $info['http_code'];
		if($http_code != '200'){
			throw new HttpGatewayException($output, $http_code);
		}else{
			return $output;
		}

	}

	public function pushToMany($message, $to_numbers, $from_number=NULL, $billingCode=NULL, $partnerReference=NULL){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_FTPLISTONLY, 1);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$responses = array();	
		foreach($to_numbers as $to){
			$data_to_submit = array();
	                $data_to_submit['message'] = $message;
        	        $data_to_submit['to'] = $to;
                	if (isset($from_number)){
                        	$data_to_submit['from'] = $from_number;
	                }
        	        if (isset($billingCode)){
                	        $data_to_submit['billingCode'] = $billingCode;
	                }
        	        if (isset($partnerReference)){
                	        $data_to_submit['partnerReference'] = $partnerReference;
	                }
			$url = $this->url . $this->service_key . '?' . http_build_query($data_to_submit);
			curl_setopt($ch, CURLOPT_URL, $url);
			$output = curl_exec($ch);
	                $info = curl_getinfo($ch);
                	$http_code = $info['http_code'];
        	        $responseData = new ResponseData($output, $http_code);
			array_push($responses, $responseData);
	        }
		curl_close($ch);	
		return $responses;
	}

	public function pushToManyPost($message, $to_numbers, $from_number=NULL, $billingCode=NULL, $partnerReference=NULL){
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_FTPLISTONLY, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_URL, $this->url . $this->service_key);
                curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $responses = array();
                foreach($to_numbers as $to){
                        $data_to_submit = array();
                        $data_to_submit['message'] = $message;
                        $data_to_submit['to'] = $to;
                        if (isset($from_number)){
                                $data_to_submit['from'] = $from_number;
                        }
                        if (isset($billingCode)){
                                $data_to_submit['billingCode'] = $billingCode;
                        }
                        if (isset($partnerReference)){
                                $data_to_submit['partnerReference'] = $partnerReference;
                        }
			curl_setopt($ch, CURLOPT_POST, sizeof($data_to_submit));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_to_submit);
                        $output = curl_exec($ch);
                        $info = curl_getinfo($ch);
                        $http_code = $info['http_code'];
                        $responseData = new ResponseData($output, $http_code);
                        array_push($responses, $responseData);
                }
                curl_close($ch);
                return $responses;
        }

	public function pushMessage($message, $to, $from_number=NULL, $billingCode=NULL, $partnerReference=NULL){
		$data_to_post = array();
		$data_to_post['message'] = $message;
		$data_to_post['to'] = $to;
		if (isset($from_number)){
			$data_to_post['from'] = $from_number;
		}	
		if (isset($billingCode)){
			$data_to_post['billingCode'] = $billingCode;
		}	
		if (isset($partnerReference)){
			$data_to_post['partnerReference'] = $partnerReference;
		}	

		$data = $this->doRequest($data_to_post, $this->url . $this->service_key, $method='POST');	
		return $data;
	}

	public function queryDlr($msg_id){
		$data_to_get = array();
		$data_to_get['id'] = $msg_id;	

		$output = $this->doRequest($data_to_get, $this->dlr_url . $this->service_key, $method='GET');
		$data = json_decode($output, true);
		$msg_id = $data['id'];
                $status = $data['status'];
		$reason_code = $data['reason'];
                return new DlrQueryResponse($status, $reason_code, $msg_id);

	}

} 

?>
