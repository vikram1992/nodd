<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';


class User extends REST_Controller {

	public function __construct() {
		parent::__construct();
		//$this->load->library('json');
		$this->load->model('UserModel');
		$this->load->helper('url');
		$this->load->library('encrypt');
	}

	public function index_get() {
		$user_id = $this->get('user_id');
		$data = $this->UserModel->user_get_by_id($user_id);
		if(count($data) > 0){
			//$user_verifed = (int)$this->UserModel->is_mobile_verified('id',$user_id);
			//if($user_verifed == 1) {
				$data = $data[0];
				$interest = $this->UserModel->interest_get_by_id($user_id);
				$badge = $this->UserModel->get_userbadge($user_id);
				if ($badge) {
					$data['badge'] = $badge[0]['badge'];
				}
				foreach ($interest as $key => $value) {
					$formatted_interest[] = $value['interest'];
					$sub_interest = $this->UserModel->sub_interest_get_by_id($user_id,$value['id']);
					$sub_interest_array = array();
					if($sub_interest) {
						foreach ($sub_interest as $sub_key => $sub_value) {
							$sub_interest_array[] = $sub_value['sub_interest'];
						}
						$formatted_interest[]['sub_interest'] = $sub_interest_array;
					}
				}
				$following_count = $this->UserModel->get_user_following_count($user_id);
				$follower_count = $this->UserModel->get_user_follower_count($user_id);
				$this->UserModel->update_entry($user_id,array('no_of_follower'=>$follower_count,'no_of_following'=>$following_count));

				$referral_code = $this->UserModel->get_reference_code($data['mobile_no']);
				$data['referral_code'] = $referral_code[0]['reference_code'];
				if (!is_null($data['organization_id'])) {
					$data['organization'] = $this->UserModel->organization_get_by_id($data['organization_id']);
					unset($data['organization_id']);
				}
				if (!is_null($data['qualification_id'])) {
					$data['organization'] = $this->UserModel->qualification_get_by_id($data['qualification_id']);
					unset($data['qualification_id']);
				}
				if (!is_null($data['designation_id'])) {
					$data['organization'] = $this->UserModel->designation_get_by_id($data['designation_id']);
					unset($data['designation_id']);
				}
				if (!is_null($data['university_id'])) {
					$data['organization'] = $this->UserModel->university_get_by_id($data['university_id']);
					unset($data['university_id']);
				}
				if(isset($formatted_interest)) {
					$data['interest'] = $formatted_interest;
				}
				$success = 'User Details';
				$message = $this->json->success_json($success,$data);
			// } 
			// else {
			// 	$error = 'Please verify your mobile number';
			// 	$message = $this->json->error_json($error);
			// }
		} else {
				$error = 'Invalid User ID';
				$message = $this->json->error_json($error);						
		}
		$this->response($message);
	}

	public function index_post() {
		$mobile_no = $this->post('mobile_no');
		$otp = mt_rand(1000,9999);
		$user_found = $this->UserModel->get_user_by_mobile_no($mobile_no);
		if ($user_found) {
			$error = 'This mobile number is already registered';
			$message = $this->json->error_json($error);
		} else {
			$insert_data = array('mobile_no'=>$mobile_no,'otp'=>$otp);
			$id = $this->UserModel->insert_entry($insert_data);
			if ($id) {
				$success = 'First part of registration is complete. Please complete your profile';
				$data = array('user_id'=>$id);
				$message = $this->json->success_json($success,$data);
			} else {
				$error = 'You are already registered. Please complete your profile';
				$message = $this->json->error_json($error);
			}
		}
		return $this->response($message);
	}

	public function check_user_details($table_name,$name,$column_name) {
		if(!is_null($name)) {
			$list = $this->UserModel->about_user_select($table_name,$name,$column_name);
			if ($list) {
				$id = $list[0]['id'];
				return $id;
			} else {
				$data = array($column_name=>$name);
				$id = $this->UserModel->about_user_insert($table_name,$data);
				return $id;
			}
		}
	}

	public function index_put() {
		$user_id = $this->put('user_id');
		$session_created = $this->put('session_created');   //Check whether user is logged in or not
		$password = $this->put('password');
		$organization = $this->put('organization');
		$designation = $this->put('designation');
		$university = $this->put('university');
		$qualification = $this->put('qualification');
		$longitude = $this->put('longitude');
		$latitude = $this->put('latitude');
		$interest_pipe = $this->put('interest');
		$token = $this->put('token');
		$email = $this->put('email');
		$full_name = $this->put('full_name');
		$username = $this->put('username');
		$interest_split = explode('|', $interest_pipe);
		if(!is_null($password)) {
			$password = $this->encrypt->encode($password);
		}
		$organization_id = $this->check_user_details('nodd_organization',$organization,'name');
		$designation_id = $this->check_user_details('nodd_designation',$designation,'designation');
		$university_id = $this->check_user_details('nodd_university',$university,'university');
		$qualification_id = $this->check_user_details('nodd_qualification',$qualification,'qualification');


		$put_data = array('organization_id'=>$organization_id,'designation_id'=>$designation_id,'university_id'=>$university_id,
						'qualification_id'=>$qualification_id,'latitude'=>$latitude,'longitude'=>$longitude,
						'token'=>$token,'password'=>$password,'email'=>$email,'full_name'=>$full_name,'username'=>$username);
		foreach ($put_data as $key => $value) {
			if (!is_null($value)) {
				$sanitize_data[$key] = $value;
			}
		}
		$interest_update = false;
		if(!is_null($interest_pipe)) {
			$delete = $this->UserModel->delete_interest($user_id);
			$flag = true;
			foreach ($interest_split as $key => $value) {
				$find_sub_interest = strrpos($value,'!');
				if ($find_sub_interest == false) {
					$data = array('user_id'=>$user_id,'interest'=>$value);
					$interest_update = $this->UserModel->insert_interest_entry($data);
				} else {
					if ($flag) {
						$delete = $this->UserModel->delete_sub_interest($user_id);
						$flag = false;
					}
					$str_split = explode('!', $value);
					$data = array('user_id'=>$user_id,'interest'=>$str_split[0]);
					$interest_id = $this->UserModel->insert_interest_entry($data);
					$sub_interest_split = explode('@', $str_split[1]);
					foreach ($sub_interest_split as $sub_key => $sub_value) {
						$interest_update = true;
						$sub_data = array('user_id'=>$user_id,'interest_id'=>$interest_id,'sub_interest'=>$sub_value);
						$sub_interest_update = $this->UserModel->insert_sub_interest_entry($sub_data);
					}
				}
			}
		}
		if(isset($sanitize_data)) { 
			$updated_value = $this->UserModel->update_entry($user_id,$sanitize_data);
		} else {
			$updated_value = false;
		}
		if ($session_created && ($updated_value || $interest_update)) {
			$success = "Your profile is updated successfully";
			$message = $this->json->success_json($success);
		} elseif(!$session_created && ($updated_value || $interest_update)) {
			$success = "Registration is done successfully";
			$message = $this->json->success_json($success);
		} else {
			$error = 'Your profile data is already updated';
			$message = $this->json->error_json($error);							
		}
		$this->response($message);
	}

	public function forgotpassword_put() {
		$unique = mt_rand(1000,9999);
		$mobile_no = $this->put('mobile_no');
		$found_user = $this->UserModel->get_user_by_mobileno($mobile_no);
		if ($found_user == 1) {
			$updated = $this->UserModel->insert_otp($mobile_no,array('otp'=>$unique));
			if($updated) {
				//send otp by sms
				$success = "OTP has been successfully sended you.";
				$message = $this->json->success_json($success);
			} else {
				$error = 'Error in sending the code';
				$message = $this->json->error_json($error);
			}
		} else {
			$error = 'Please enter mobile number correctly';
			$message = $this->json->error_json($error);	
		}
		$this->response($message);
	}

	public function verifypassword_put() {
		$otp = $this->put('otp');
		$mobile_no = $this->put('mobile_no');
		$flow_step = $this->put('flow_step');  //1 for inital step and 2 for all other next steps
		$password = $this->put('password');
		$otp_correct = $this->UserModel->verify_otp($mobile_no,$otp);
		if($otp_correct == 1) {
			if($flow_step == 2) {
				$updated = $this->UserModel->update_password($mobile_no,array('password'=>$password));
				if($updated) {
					$this->UserModel->clear_otp_and_mobile_verify($mobile_no,array('otp'=>'NULL'));
					$success = "Password has been successfully updated";
					$message = $this->json->success_json($success);				
				} else {
					$error = "Error in updating OTP";
					$message = $this->json->success_json($error);
				}
			} else {
				$this->UserModel->clear_otp_and_mobile_verify($mobile_no,array('otp'=>'NULL'));
				$success = "Correct OTP";
				$message = $this->json->success_json($success);
			}
		} else {
			$error = "Incorrect OTP";
			$message = $this->json->success_json($error);
		}
		$this->response($message);
	}

	public function userfollow_post() {
		$user_id = $this->post('user_id');
		$follow_user_id = $this->post('follow_user_id');
		$data = array('user_id'=>$user_id,'followuser_id'=>$follow_user_id);
		$result = $this->UserModel->userfollow_model($data);
		if($result) {
			$success = 'Data inserted successfully';
			$message = $this->json->success_json($success);
		} else {
			$error = 'Error in inserting data';
			$message = $this->json->error_json($error);
		}
		return $this->response($message);	
	}

	public function userbadges_post() {
		$user_id = $this->post('user_id');
		$badge_id = $this->post('badge_id');
		$date = date('Y-m-d H:i:s');
		$count = $this->UserModel->check_userbadge_exist($user_id,$badge_id);
		if ($count == 0) {
			$data = array('user_id'=>$user_id,'badge_id'=>$badge_id,'received_date'=>$date);
			$result = $this->UserModel->userbadges_model($data);
			$success = 'Data inserted successfully';
			$message = $this->json->success_json($success);
		} else {
			$error = 'You already given a badge to this user';
			$message = $this->json->error_json($error);
		}
		return $this->response($message);
	}

	public function usertags_post() {
		$user_id = $this->post('user_id');
		$tag_id = $this->post('tag_id');
		$count = $this->UserModel->check_usertags_exist($user_id,$tag_id);
		if ($count == 0) {
			$data = array('user_id'=>$user_id,'tag_id'=>$tag_id);
			$result = $this->UserModel->usertags_model($data);
			$success = 'Data inserted successfully';
			$message = $this->json->success_json($success);
		} else {
			$error = 'You already given a tag to this user';
			$message = $this->json->error_json($error);
		}
		return $this->response($message);
	}


	/*
		Send invite code to user
	*/
	public function send_invitation_code_post()
	{	
		$mobile_no = $this->post('mobile_no');
		$reference_code = $this->post('reference_code');
		$reference_code_id = $this->UserModel->get_reference_code_mobile_no($reference_code);
		if ($reference_code_id) {
			$reference_code_id = $reference_code_id[0]['id'];
			$data = array('referencecode_id'=>$reference_code_id,'mobile_no'=>$mobile_no);
			$insert_id = $this->UserModel->share_insert($data);
			if ($insert_id) {
				$success = 'Your reference_code has been shared successfully to that user';
				$message = $this->json->success_json($success);
			} else {
				$error = 'Error in sending the code';
				$message = $this->json->error_json($error);
			}
		} else {
			$error = 'Invalid invitation code';
			$message = $this->json->error_json($error);
		}
		return $this->response($message);
	}	


	public function generate_referral_code_put()
	{
		$mobile_no = $this->put('user_id');
		$control = $this->put('control');  //1 for admin 2 for other users
		$result = $this->UserModel->user_name_get_by_id($mobile_no);
		if ($result ) {
			$unique = mt_rand(1000,9999);
			$short_name = substr($result['full_name'], 0, 3);
			$reference_code = $short_name.$unique;
			if($control == 1) {
				$data = array('reference_code'=>$reference_code,'mobile_no'=>$result['mobile_no']);
				$this->UserModel->nodd_referencecode_insert($data);
			} else {
				$data = array('reference_code'=>$reference_code);
				$this->UserModel->nodd_referencecode_update($data,$result['mobile_no']);
			}
			$success = 'Invitation Code Generated';
			$message = $this->json->success_json($success);
		} else {
			$error = 'Error in generating Code';
			$message = $this->json->error_json($error);
		}
		return $this->response($message);
	}



	/*
		get mobile number and send invitation code once if verified
	*/
	public function register_mobile_no_post()
	{
		$mobile_no = $this->post('mobile_no');
		$reference_code = $this->post('reference_code');
		$data = array('mobile_no' => $mobile_no,'reference_code'=>$reference_code);
		$return_value = $this->UserModel->nodd_referencecode_insert($data);
		if ($return_value) {
			//Write mail code and create one function to insert reference code in db
			if(is_null($reference_code)) {
				$success = 'Your mobile number has been taken successfully. Please wait till we verify from our side.';
				$message = $this->json->success_json($success);
			} else {
				$success = 'Your mobile number has been taken successfully. You can continue...';
				$message = $this->json->success_json($success);
			}
		} else {
			$error = 'Your number is already registered';
			$message = $this->json->error_json($error);
		}
		return $this->response($message);
	}

	/*
		At the time of registration verify invite code
	*/
	public function verify_invitation_code_put()
	{
		$mobile_no = $this->put('mobile_no');
		$reference_code = $this->put('reference_code');
		$count = $this->UserModel->nodd_referencecode_select($mobile_no,$reference_code);
		if ($count == 0) {
			$reference_code_id = $this->UserModel->get_reference_code_mobile_no($reference_code);
			if ($reference_code_id) {
				$reference_code_id = $reference_code_id[0]['id'];
				$count = $this->UserModel->check_reference_code($reference_code_id);
				if ($count) {
					$success = 'Your invitation code has been matched successfully';
					$message = $this->json->success_json($success);
				} else {
					$error = 'Invalid invitation code';
					$message = $this->json->error_json($error);
				}
			} else {
					$error = 'Invalid inviation Code';
					$message = $this->json->error_json($error);
			}
		} else {
			$success = 'Your invitation code has been matched successfully';
			$message = $this->json->success_json($success);
		}
		return $this->response($message);
	}


	/*
		Assign invitation code of admin to those users who are requesting for the invite code
	*/
	public function assign_invitation_code_put() {
		$mobile_no = $this->put('mobile_no');
		$admin_mobile_no = $this->put('admin_mobile_no');
		$reference_code = $this->UserModel->get_reference_code($admin_mobile_no);
		if($reference_code) {
			$reference_code = $reference_code[0]['reference_code'];
			$data = array('reference_code'=>$reference_code);
			$this->UserModel->nodd_referencecode_update($data,$mobile_no);
			$success = 'The invitation code has been mapped successfully.';
			$message = $this->json->success_json($success);
		} else {
			$error = 'Put correct admin\'s mobile numbers';
			$message = $this->json->error_json($error);
		}
		return $this->response($message);
	}


	public function do_upload_image($user_id,$image1) {
		$config['upload_path'] = './uploads/';
		$config['file_name'] = $user_id;
		$config['overwrite'] = TRUE;
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '1000';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload($image1))
		{
			$error = $this->upload->display_errors('', '');
			$message = $this->json->error_json($error);
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$message = $this->json->success_json($data);
		}
		return $message;
	}

	public function upload_image_post() {
		$user_id = $this->post('user_id');
		$image = 'image';
		$message = $this->do_upload_image($user_id,$image);
		if ($message['success']) {
			$file_path = str_replace($message['message']['upload_data']['file_path'],str_replace('http://','',base_url()).'api/uploads/',$message['message']['upload_data']['file_path']);
			$full_path = $file_path.$message['message']['upload_data']['file_name'];

			$data = array('image'=>$full_path);
			$updated = $this->UserModel->update_entry($user_id,$data);
			if (!$updated) {
				$error = 'Error in writing database';
				$message = $this->json->error_json($error);
			} else {
				$success = 'Image uploaded successfully';
				$message = $this->json->success_json($success);
			}
			return $this->response($message);
		}
		return $this->response($message);
	}

	public function show_user_following_list_get() {
		$user_id = $this->get('user_id');
		$list = $this->UserModel->get_following_user_list($user_id);
		if ($list) {
			$data = $list;
			$success = 'User List';
			$message = $this->json->success_json($success,$data);
		} else {
			$error = 'Empty list';
			$message = $this->json->error_json($error);			
		}
		return $this->response($message);
	}

	public function show_user_follower_list_get() {
		$user_id = $this->get('user_id');
		$list = $this->UserModel->get_follower_user_list($user_id);
		if ($list) {
			$data = $list;
			$success = 'User List';
			$message = $this->json->success_json($success,$data);
		} else {
			$error = 'Empty list';
			$message = $this->json->error_json($error);			
		}
		return $this->response($message);
	}	

	public function send_email_get() {

		$config = Array(
		'protocol' => 'smtp',
		'smtp_host' => 'smtp.noddapp.in',
		'smtp_port' => 25,
		'smtp_user' => 'vikram@noddapp.in', 
		'smtp_pass' => '!2vikram', 
		'charset' => 'iso-8859-1',
		'wordwrap' => TRUE
		);
		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");

		$this->email->from('vikram@noddapp.in', 'Vikram');
		$this->email->to('vikgr88@gmail.com');
		$this->email->subject('Test');
		$msg = "Test";

		$this->email->message($msg);

		if ($this->email->send()) {
			$success = 'Email send successfully';
			$message = $this->json->success_json($success);
		} else {
			$error = $this->email->print_debugger();
			$message = $this->json->error_json($error);	
		}
		return $this->response($message);
	}


	public function beam_rating_post() {
		$user_id = $this->post('user_id');
		$beam_id = $this->post('beam_id');
		$rating = $this->post('rating');
		$data = array('user_id'=>$user_id,'beam_id'=>$beam_id,'rating'=>$rating);
		$inserted = $this->UserModel->beam_rating_insert($data);
		if ($inserted) {
			$success = 'Inserted successfully';
			$message = $this->json->success_json($success);
		} else {
			$error = 'Error';
			$message = $this->json->error_json($error);
		}
		return $this->response($message);

	}

	public function common_friends_get() {
		$user_id = $this->get('user_id');
		$other_user_id = $this->get('other_user_id');
		$list = $this->UserModel->common_friends($user_id,$other_user_id);
		if ($list) {
			foreach ($list as $key => $value) {
			 	$common_friends[$value['followuser_id']] = $value['image'];
			}
			$success = 'Common connection list';
			$message = $this->json->success_json($success,$common_friends);
		} else {
			$error = 'No Common List';
			$message = $this->json->error_json($error);
		}
		return $this->response($message);
	}

	public function organization_list_get() {
		$list = $this->UserModel->organization_list_select();
		if ($list) {
			foreach ($list as $key => $value) {
				$organization[$key]['id'] = $value['id'];
				$organization[$key]['name'] = $value['name'];		
			}
			$success = 'Organzation list';
			$message = $this->json->success_json($success,$organization);
		} else {
			$error = 'No organization List';
			$message = $this->json->error_json($error);			
		}
		return $this->response($message);
	}

	public function designation_list_get() {
		$list = $this->UserModel->designation_list_select();
		if ($list) {
			foreach ($list as $key => $value) {
				$designation[$key]['id'] = $value['id'];
				$designation[$key]['name'] = $value['name'];		
			}
			$success = 'Designation list';
			$message = $this->json->success_json($success,$designation);
		} else {
			$error = 'No designation List';
			$message = $this->json->error_json($error);			
		}
		return $this->response($message);
	}


}
















