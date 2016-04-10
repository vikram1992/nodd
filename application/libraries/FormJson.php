<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class FormJson 
{

	public function success_json($message,$data=null) {
		$response['success'] = true;
		$response['error'] = false;
		$response['message'] = $message;
		if(!is_null($data)) {
			$response['data'] = $data;
		}
		return $response;		
	}

	public function error_json($message) {
		$response['success'] = false;
		$response['error'] = $message;
		return $response;		
	}

}