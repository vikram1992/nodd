<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';


class BeamJoins extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('UserModel');
		$this->load->model('BeamJoinsModel');
	}

	public function index_post() {
		$user_id = $this->post('user_id');
		$beam_id = $this->post('beam_id');
		$isapproved = $this->post('isapproved');
		$dropout = $this->post('dropout');
		$data = array('beam_id'=>$beam_id,'user_id'=>$user_id,'isapproved'=>$isapproved,'dropout'=>$dropout);
		foreach ($data as $key => $value) {
			if (!is_null($value)) {
				$sanitize_data[$key] = $value;
			}
		}
		$beamjoin_num = $this->BeamJoinsModel->beamjoin_check($user_id,$beam_id);
		if($beamjoin_num == 0) {
			$beamjoin = $this->BeamJoinsModel->beamjoin_insert($sanitize_data);
			if ($beamjoin) {
				$success = 'Beam join data has been inserted successfully';
				$message = $this->json->success_json($success);
			} else {
				$error = 'Error in inserting the record';
				$message = $this->json->error_json($error);				
			}
		} else {
			$error = 'Data already exist';
			$message = $this->json->error_json($error);
		}
		$this->response($message);	
	}


	public function index_put() {
		$user_id = $this->put('user_id');
		$beam_id = $this->put('beam_id');
		$isapproved = $this->put('isapproved');
		$dropout = $this->put('dropout');
		$data = array('isapproved'=>$isapproved,'dropout'=>$dropout);
		foreach ($data as $key => $value) {
			if (!is_null($value)) {
					$sanitize_data[$key] = $value;
			}
		}
		$update_beamjoins = $this->BeamJoinsModel->beamjoin_update($user_id,$beam_id,$sanitize_data);
		if($update_beamjoins) {
			$success = 'BeamJoin data updated successfully';
			$message = $this->json->success_json($success);
		} else {
			$error = 'Error in updating';
			$message = $this->json->error_json($error);			
		}
		$this->response($message);
	}


	public function index_get() {
		$user_id = $this->get('user_id');
		$beam_data = $this->BeamJoinsModel->beamjoin_get_by_id($user_id);
		if($beam_data) {
			$success = 'BeamJoin Data Available';
			$message = $this->json->success_json($success,$beam_data);
		} else {
			$error = 'No Beam Join Data';
			$message = $this->json->error_json($error);
		}
		$this->response($message);
	}

	public function nodding_list_get() {
		$beam_id = $this->get('beam_id');
		$list = $this->BeamJoinsModel->noddlist($beam_id);
		if($list) {
			foreach ($list as $key => $value) {
				$noddlist[$key]['user_id'] = $value['user_id'];
				$noddlist[$key]['isapproved'] = $value['isapproved'];
				$noddlist[$key]['dropout'] = $value['dropout'];
				$noddlist[$key]['image'] = $value['image'];				
			}
			$success = 'Nodding List';
			$message = $this->json->success_json($success,$noddlist);

		} else {
			$error = 'Empty Nodding List';
			$message = $this->json->error_json($error);			
		}
		return $this->response($message);
	}
}