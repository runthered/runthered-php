<?php
namespace RtrPushApi;

class PushApiResponse {
	public $status;
	public $msg_id;
	public $id;

	public function __construct($status, $msg_id, $id){
		$this->status = $status;
		$this->msg_id = $msg_id;
		$this->id = $id;
	}
}

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

class PushApiException extends \Exception{
	
}


class PushApi {
	private $url;
	private $username;
	private $password;
	private $service_key;

	public function __construct($username, $password, $service_key, $url='https://connect.runthered.com:10443/public_api/service'){
		$this->url = $url;
		$this->username = $username;
		$this->password = $password;
		$this->service_key = $service_key;
	}
	
	private function doJsonRequest($data){
		$data_string = json_encode($data);
                $ch = curl_init($this->url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string))
                );
                curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
                $output = curl_exec($ch);
                $info = curl_getinfo($ch);
                curl_close($ch);
                $http_code = $info['http_code'];
		if($http_code != '200'){
			throw new PushApiException($output, $http_code);
		}else{
			$data = json_decode($output, true);
			if(!array_key_exists('result', $data)){
				$error = $data['error'];
	                        $message = $error['message'];
        	                $code = $error['code'];
                	        throw new PushApiException($message, $code);
			}
	                return $data;
	
		}

	}

	public function pushMessage($body, $to, $from=NULL, $id=1){
		$json_data = array("jsonrpc" => "2.0", "method" => "sendsms", "params" => array("service_key" => "$this->service_key", "to" => "$to", "body" => "$body"), "id" => $id);  
		if (isset($from)){
			$json_data["params"]["frm"] = $from;
		}
		$data = $this->doJsonRequest($json_data);	
		$id = $data['id'];
		$result = $data['result'];
		$status = $result['status'];
		$msg_id = $result['msg_id'];
		return new PushApiResponse($status, $msg_id, $id);
	}

	public function queryDlr($msg_id, $id=1){
		$json_data = array("jsonrpc" => "2.0", "method" => "querydlr", "params" => array("service_key" => "$this->service_key", "msg_id" => "$msg_id"), "id" => $id);

		$data = $this->doJsonRequest($json_data);
		$id = $data['id'];
                $result = $data['result'];
                $status = $result['status'];
		$reason_code = $result['reason'];
               	$msg_id = $result['msg_id'];
                return new DlrQueryResponse($status, $reason_code, $id);

	}

} 

?>
