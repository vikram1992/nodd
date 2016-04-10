<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';


class NoddList extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('UserModel');
		$this->load->model('NoddListModel');
	}

	public function my_nodd_created_get() {
		$final_result = '';
		$user_id = $this->get('user_id');
		$results = $this->NoddListModel->my_nodd_model($user_id);
		foreach ($results as $key => $value) {
			$final_result[$key] = $value;
			$badge = $this->UserModel->get_userbadge($value['user_id']);
			if ($badge) {
				$final_result[$key]['badge'] = $badge[0]['badge'];
			}
			$list = $this->NoddListModel->my_nodd_user_list($value['id']);
			foreach ($list as $list_key => $list_value) {
				$badge = $this->UserModel->get_userbadge($list_value['user_id']);
				if ($badge) {
					$list[$list_key]['badge'] = $badge[0]['badge'];
				}
			}
			$final_result[$key]['nodds'] = $list;
			// $list1 = $this->NoddListModel->nodd_attending($user_id);
			// $final_result[$key]['attending'] = $list1;
		}
		if($final_result == '') {
			$error = 'No beam found';
			$message = $this->json->error_json($error);
		} else {
			$success = 'Nodd User List';
			$message = $this->json->success_json($success,$final_result);
		}
		$this->response($message);
	}

	public function my_nodd_attending_get() {
		$final_result = '';
		$user_id = $this->get('user_id');
		$results = $this->NoddListModel->nodd_attending($user_id,1);
		foreach ($results as $key => $value) {
			$final_result[$key] = $value;
			$badge = $this->UserModel->get_userbadge($value['user_id']);
			if ($badge) {
				$final_result[$key]['badge'] = $badge[0]['badge'];
			}
			$list = $this->NoddListModel->nodd_attending_user_list($value['beam_id']);
			foreach ($list as $list_key => $list_value) {
				$badge = $this->UserModel->get_userbadge($list_value['user_id']);
				if ($badge) {
					$list[$list_key]['badge'] = $badge[0]['badge'];
				}
			}
			$final_result[$key]['nodd_attending_list'] = $list;
		}
		if($final_result == '') {
			$error = 'No beam found';
			$message = $this->json->error_json($error);
		} else {
			$success = 'Nodd User List';
			$message = $this->json->success_json($success,$final_result);
		}
		$this->response($message);
	}


	public function my_nodd_waiting_get() {
		$final_result = '';
		$user_id = $this->get('user_id');
		$results = $this->NoddListModel->nodd_attending($user_id,0);
		foreach ($results as $key => $value) {
			$final_result[$key] = $value;
			$badge = $this->UserModel->get_userbadge($value['user_id']);
			if ($badge) {
				$final_result[$key]['badge'] = $badge[0]['badge'];
			}
			$list = $this->NoddListModel->nodd_attending_user_list($value['beam_id']);
			foreach ($list as $list_key => $list_value) {
				$badge = $this->UserModel->get_userbadge($list_value['user_id']);
				if ($badge) {
					$list[$list_key]['badge'] = $badge[0]['badge'];
				}
			}
			$final_result[$key]['nodd_waiting_list'] = $list;
		}
		if($final_result == '') {
			$error = 'No beam found';
			$message = $this->json->error_json($error);
		} else {
			$success = 'Nodd User List';
			$message = $this->json->success_json($success,$final_result);
		}
		$this->response($message);
	}



	public function near_me_get() {
		$final_result = '';
		$user_id = $this->get('user_id');
		$latitude = $this->get('latitude');
		$longitude = $this->get('longitude');
		$user_list = $this->NoddListModel->near_me_model($latitude,$longitude,$user_id);
		foreach ($user_list as $user_key => $user_value) {
			$results = $this->NoddListModel->my_nodd_model($user_value['id']);
			foreach ($results as $key => $value) {
				$final_result[$key] = $value;
				$badge = $this->UserModel->get_userbadge($value['user_id']);
				if ($badge) {
					$final_result[$key]['badge'] = $badge[0]['badge'];
				}
				$list = $this->NoddListModel->my_nodd_user_list($value['id']);
				foreach ($list as $list_key => $list_value) {
					$badge = $this->UserModel->get_userbadge($list_value['user_id']);
					if ($badge) {
						$list[$list_key]['badge'] = $badge[0]['badge'];
					}
				}
				$final_result[$key]['list'] = $list;
			}
		}
		if($final_result == '') {
			$error = 'No beam found';
			$message = $this->json->error_json($error);
		} else {
			$success = 'Nodd User List';
			$message = $this->json->success_json($success,$final_result);
		}
		$this->response($message);	
	}


	public function circle_get() {
		$final_result = '';
		$user_id = $this->get('user_id');
		$user_list = $this->NoddListModel->my_circle_model($user_id);
		foreach ($user_list as $user_key => $user_value) {
			$results = $this->NoddListModel->my_nodd_model($user_value['followuser_id']);
			foreach ($results as $key => $value) {
				$final_result[$key] = $value;
				$badge = $this->UserModel->get_userbadge($value['user_id']);
				if ($badge) {
					$final_result[$key]['badge'] = $badge[0]['badge'];
				}
				$list = $this->NoddListModel->my_nodd_user_list($value['id']);
				foreach ($list as $list_key => $list_value) {
					$badge = $this->UserModel->get_userbadge($list_value['user_id']);
					if ($badge) {
						$list[$list_key]['badge'] = $badge[0]['badge'];
					}
				}
				$final_result[$key]['list'] = $list;
			}
		}
		if($final_result == '') {
			$error = 'No beam found';
			$message = $this->json->error_json($error);
		} else {
			$success = 'Nodd User List';
			$message = $this->json->success_json($success,$final_result);
		}
		$this->response($message);
	}

}