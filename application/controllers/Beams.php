<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';


class Beams extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('BeamModel');
		$this->load->model('BeamJoinsModel');
		$this->load->model('NoddListModel');
		$this->load->model('UserModel');
	}

	public function index_post() {
		$user_id = $this->post('user_id');
		$title = $this->post('title');
		$description = $this->post('description');
		$sug_place = $this->post('sug_place');
		$sug_date = $this->post('sug_date');
		$sug_noppl = $this->post('sug_noppl');
		$sug_venue = $this->post('sug_venue');
		$price_range = $this->post('price_range');
		// $user_verifed = (int)$this->UserModel->is_mobile_verified('id',$user_id);
		// if($user_verifed == 1) {
			//$sug_extract_date = date()
			$user_beam_count = $this->BeamJoinsModel->check_beam_by_user_id($user_id,$sug_date);
			$current_user_beam_count = $this->BeamModel->check_beam_by_user_id($user_id,$sug_date);
			if($current_user_beam_count == 0) {
				if($user_beam_count == 0) {
					if($sug_date <= date('Y-m-d')) {
						$error = 'Date should be future date';
						$message = $this->json->error_json($error);
					} else {
						$data = array('user_id'=>$user_id,'title'=>$title,'description'=>$description,'sug_place'=>$sug_place,'sug_date'=>$sug_date,
							'sug_noppl'=>$sug_noppl,'sug_venue'=>$sug_venue);
						$beam_id = $this->BeamModel->beam_insert($data);
						$no_nodd_created = $this->BeamModel->get_no_nodd_created($user_id);
						$no_nodd_created += 1;
						$user_update = $this->UserModel->update_entry($user_id,array('no_nodd_created'=>$no_nodd_created));
						if($beam_id) {
							$success = 'Your Beam has been accepted successfully';
							$message = $this->json->success_json($success,array('beam_id'=>$beam_id));
						} else {
							$error = 'You had already created the beam';
							$message = $this->json->error_json($error);
						}			
					}
				} else {
					$error = 'You already accepted a beam of other user on this day, so you cant create a beam on this day';
					$message = $this->json->error_json($error);
				}
			} else {
				$error = 'You already created a beam on this day, so you cant create a beam on this day';
				$message = $this->json->error_json($error);
			}
		// } else {
		// 	$error = 'Please verify your mobile number';
		// 	$message = $this->json->error_json($error);
		// }
		$this->response($message);
	}


	public function index_put() {
		$beam_id = $this->put('beam_id');
		$title = $this->put('title');
		$description = $this->put('description');
		$sug_place = $this->put('sug_place');
		$sug_date = $this->put('sug_date');
		$sug_noppl = $this->put('sug_noppl');
		$sug_venue = $this->put('sug_venue');
		$finalized = $this->put('finalized');
		$fin_place = $this->put('final_place');
		$fin_date = $this->put('final_date');
		$fin_noppl = $this->put('final_noppl');
		$fin_venue = $this->put('final_venue');
		$price_range = $this->put('price_range');
		$data = array('title'=>$title,'description'=>$description,'sug_place'=>$sug_place,'sug_date'=>$sug_date,
			'sug_noppl'=>$sug_noppl,'sug_venue'=>$sug_venue,'fin_place'=>$fin_place,'fin_date'=>$fin_date,
			'fin_noppl'=>$fin_noppl,'fin_venue'=>$fin_venue);
		foreach ($data as $key => $value) {
			if (!is_null($value)) {
				$sanitize_data[$key] = $value;
			}
		}
		$beam_id = $this->BeamModel->beam_update($beam_id,$sanitize_data);
		if($beam_id) {
			$success = 'Your Beam has been updated successfully';
			$message = $this->json->success_json($success);
		} else {
			$error = 'Error in updating the beam';
			$message = $this->json->error_json($error);
		}
		$this->response($message);
	}


	public function index_get() {
		$beam_id = $this->get('beam_id');
		$beam_data = $this->BeamModel->beam_get_by_id($beam_id);
		if($beam_data) {
			$results = $beam_data[0];
			$badge = $this->UserModel->get_userbadge($beam_data[0]['user_id']);
			if ($badge) {
				$results['badge'] = $badge[0]['badge'];
			}
			$user_list = $this->NoddListModel->my_nodd_user_list($beam_id);
			foreach ($user_list as $key => $value) {
				$results['user_list'][$key] = $value;
				$badge = $this->UserModel->get_userbadge($value['user_id']);
				if ($badge) {
					$results['user_list'][$key]['badge'] = $badge[0]['badge'];
				}
			}
			$success = 'Beam Data Available';
			$message = $this->json->success_json($success,$results);
		} else {
			$error = 'Please provide correct beam ID';
			$message = $this->json->error_json($error);
		}
		$this->response($message);
	}


	public function cancelbeam_put() {
		$beam_id = $this->put('beam_id');
		$data = array('iscancelled' => 1);
		$date = $this->BeamModel->beam_date($beam_id);
		if (count($date)) {
			$date = $date[0];
			if ($date['formatted_date'] == date('YYYY-MM-DD')) {
				$error = 'You cannot cancel the beam on the same day';
				$message = $this->json->error_json($error);
			} else {
				$cancel = $this->BeamModel->beam_update($beam_id,$data);
				if($cancel) {
					$success = 'Your Beam has been cancelled successfully';
					$message = $this->json->success_json($success);
				} else {
					$error = 'Error in cancelling the beam';
					$message = $this->json->error_json($error);
				}			
			}
		} else {
			$error = 'Invalid Beam ID';
			$message = $this->json->error_json($error);
		}
		$this->response($message);
	}


	public function nodd_user_invites_post()
	{
		$user_id = $this->post('user_id');
		$beam_id = $this->post('beam_id');
		$invited_user_id = $this->post('invited_user_id');
		$data = array('user_id'=>$user_id,'beam_id'=>$beam_id,'inviteduser_id'=>$invited_user_id);
		$user1_date = $this->BeamModel->beam_date($beam_id); 
		if (count($user1_date)) {
			$user1_date = $user1_date[0];
			$user2_beam_id = $this->BeamModel->get_invited_user_beam_id($invited_user_id,$user1_date['formatted_date']);
			if (count($user2_beam_id) == 0) {
				$result = $this->BeamModel->userinvites_model($data);
				if($result) {
					$success = 'User is invited successfully';
					$message = $this->json->success_json($success);
				} else {
					$error = 'Error in inviting user';
					$message = $this->json->error_json($error);
				}
			} else {
				$error = 'You and the person to whom you are sending the request are the organizer of their respective event on the same day';
				$message = $this->json->error_json($error);
			}
		} else {
			$error = 'Invalid Beam ID';
			$message = $this->json->error_json($error);
		}
		return $this->response($message);
	}

	public function bookmark_post() {
		$user_id = $this->post('user_id');
		$beam_id = $this->post('beam_id');
		$count = $this->BeamModel->check_bookmark_exist($user_id,$beam_id);
		if ($count == 0) {
			$data = array('user_id'=>$user_id,'beam_id'=>$beam_id);
			$result = $this->BeamModel->bookmark_model($data);
			$success = 'Bookmark successfully';
			$message = $this->json->success_json($success);
		} else {
			$error = 'This beam is already bookmark';
			$message = $this->json->error_json($error);
		}
		return $this->response($message);	
	}

	public function beamtags_post() {
		$beam_id = $this->post('beam_id');
		$tag_id = $this->post('tag_id');
		$count = $this->BeamModel->check_beamtag_exist($tag_id,$beam_id);
		if($count == 0) {
			$data = array('beam_id'=>$beam_id,'tag_id'=>$tag_id);
			$result = $this->BeamModel->beamtags_model($data);
			$success = 'Data inserted successfully';
			$message = $this->json->success_json($success);
		} else {
			$error = 'Error in inserting data';
			$message = $this->json->error_json($error);
		}
		return $this->response($message);
	}

}