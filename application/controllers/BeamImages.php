<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';


class BeamImages extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('UserModel');
		$this->load->model('BeamJoinsModel');
	}

	public function index_post() {
		$flag = false;
		$beam_id = $this->post('beam_id');
		$this->load->library('upload');
		$files = $_FILES;
		$count = count($_FILES['images']['name']);
		for($i=0; $i<$count; $i++)
		{
			$_FILES['images']['name']= $files['images']['name'][$i];
			$_FILES['images']['type']= $files['images']['type'][$i];
			$_FILES['images']['tmp_name']= $files['images']['tmp_name'][$i];
			$_FILES['images']['error']= $files['images']['error'][$i];
			$_FILES['images']['size']= $files['images']['size'][$i]; 
			$last_image_id = $this->UserModel->get_last_beam_image_id()[0]['id'];
			if (!is_null($last_image_id)) {
				$last_image_id = $last_image_id;
			} else {
				$last_image_id = 1;
			}
			$this->upload->initialize($this->set_upload_options($beam_id,$last_image_id));
			if($this->upload->do_upload('images') == False)
			{
				$flag = true;
			}
			else
			{
				$message = $this->upload->data();
				$file_path = str_replace($message['file_path'],str_replace('http://','',base_url()).'api/beam_images/',$message['file_path']);
				$full_path = $file_path.$message['file_name'];
				$data = array('beam_id'=>$beam_id,'image'=>$full_path);
				$image_uploaded = $this->UserModel->beam_image_insert($data);
				if ($image_uploaded) {
					$success = 'Image uploaded successfully';
					$message = $this->json->success_json($success);
				} else {
					$error = 'Error in uploading images';
					$message = $this->json->error_json($error);
				}
        	}
		}
		if ($flag) {
            $error = 'Image not uploaded';
			$message = $this->json->error_json($error);
		}
		return $this->response($message);
	}


	public function index_delete() {
		$image_id = $this->delete('image_id');
		$delete = $this->UserModel->delete_beam_image($image_id);
		if ($delete) {
            $success = 'Image deleted successfully';
			$message = $this->json->success_json($success);
		} else {
			$error = 'Error';
			$message = $this->json->error_json($error);		
		}
		return $this->response($message);
	}


	public function index_get() {
		$beam_id = $this->get('beam_id');
		$images_result = $this->UserModel->get_beam_images($beam_id);
		if ($images_result) {
			foreach ($images_result as $key => $value) {
				$images_array[$value['id']] = $value['image'];
			}
            $success = 'BeamImage List';
			$message = $this->json->success_json($success,$images_array);
		} else {
            $error = 'Image not uploaded';
			$message = $this->json->error_json($error);			
		}
		return $this->response($message);
	}

	private function set_upload_options($beam_id,$last_image_id)
	{   
		$config['upload_path'] = './beam_images/';
		$config['file_name'] = $beam_id.$last_image_id;
		$config['overwrite'] = TRUE;
		$config['allowed_types'] = 'gif|jpg|png';

		return $config;
	}
}