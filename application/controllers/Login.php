<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Login extends REST_Controller
{
	public function __construct() {
		parent::__construct();
		$this->load->model('UserModel');
	}

	public function index_post() {
		$mobile_no = $this->post('mobile_no');
		$username = $this->post('username');
		$password = $this->post('password');
		if(!is_null($mobile_no)) {
			$key = 'mobile_no';
			$value = $mobile_no;
			$user_found = $this->UserModel->check_login_credentials('mobile_no',$mobile_no,$password);
		} else {
			$key = 'username';
			$value = $username;
			$user_found = $this->UserModel->check_login_credentials('username',$username,$password);
		}
		if($user_found) {
			$user_verifed = (int)$this->UserModel->is_mobile_verified($key,$value);
			if($user_verifed == 1) {
				$success = "You are successfully logged in";
				$message = $this->json->success_json($success);
			} else {
				$error = 'Please verify your mobile number';
				$message = $this->json->error_json($error);
			}
		} else {
			$error = 'Please enter your credentials properly';
			$message = $this->json->error_json($error);	
		}
		$this->response($message);
	}
}
?>