<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Login extends REST_Controller
{
	public function __construct() {
		parent::__construct();
		$this->load->model('UserModel');
		$this->load->library('encrypt');
	}

	public function index_post() {
		$mobile_no = $this->post('mobile_no');
		$username = $this->post('username');
		$password = $this->encrypt->encode($this->post('password'));
		var_dump($password);die;
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
			$success = "You are successfully logged in";
			$message = $this->json->success_json($success);
		} else {
			$error = 'Please enter your credentials properly';
			$message = $this->json->error_json($error);	
		}
		//return $this->response($message);
	}

	public function social_login_post() {
		$social_login_type = $this->post('social_login_type');
		$social_user_id = $this->post('social_user_id');
		$user_id = $this->post('user_id');
		if(is_null($user_id)) {
			$message = $this->social_login_get_user($social_login_type,$social_user_id);
		} else {
			$message = $this->social_login_third_party($social_login_type,$social_user_id,$user_id);
		}
		return $this->response($message);
	}


	public function social_login_get_user($social_login_type,$social_user_id) {
		if ($social_login_type == 'fb') {
			$user = $this->UserModel->social_login_facebook_select($social_user_id);
			if($user) {
				$success = "You are successfully logged in";
				$data = $user[0];
				$message = $this->json->success_json($success,$data);
			} else {
				$error = 'Internal Server Error';
				$message = $this->json->error_json($error);
			}
		} elseif($social_login_type =='li') {
			$user = $this->UserModel->social_login_linkedin_select($social_user_id);
			if($user) {
				$success = "You are successfully logged in";
				$message = $this->json->success_json($success);
			} else {
				$error = 'Internal Server Error';
				$message = $this->json->error_json($error);
			}
		}
		return $message;
	}

	public function social_login_third_party($social_login_type,$social_user_id,$user_id) {
		$facebook_user_id = NULL;
		$linkedin_user_id = NULL;
		if ($social_login_type == 'fb') {
			$facebook_user_id = $social_user_id;
		} elseif($social_login_type =='li') {
			$linkedin_user_id = $social_user_id;
		}
		$data = array('user_id'=>$user_id,'facebook_user_id'=>$facebook_user_id,'linkedin_user_id'=>$linkedin_user_id);
		$id = $this->UserModel->social_login_insert($data);
		if ($id) {
			$success = "You are successfully logged in";
			$message = $this->json->success_json($success);
		} else {
			$error = 'Internal Server Error';
			$message = $this->json->error_json($error);
		}
		return $message;
	}
}
?>