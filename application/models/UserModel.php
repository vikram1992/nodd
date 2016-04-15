<?php

class UserModel extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    
    function insert_entry($data) {
        $this->db->insert('nodd_users',$data);
        $id = $this->db->insert_id();
        return $id;
    }

    function update_entry($user_id,$sanitize_data) {
        $updated_value = $this->db->where('id',$user_id)->update('nodd_users',$sanitize_data);
        return $updated_value;
    }

    function delete_interest($user_id) {
        $delete = $this->db->where('user_id',$user_id)->delete('nodd_interest');
        return $delete;
    }

    function delete_sub_interest($user_id) {
        $delete = $this->db->where('user_id',$user_id)->delete('nodd_subinterest');
        return $delete;
    }

    function insert_interest_entry($data) {
        $this->db->insert('nodd_interest',$data);
        $id = $this->db->insert_id();
        return $id;
    }

    function insert_sub_interest_entry($data) {
        $delete = $this->db->insert('nodd_subinterest',$data);
        return $delete;
    }

    function get_user_by_mobileno($mobile_no) {
        $this->db->where('mobile_no',$mobile_no);
        $found_user = $this->db->count_all_results('nodd_users');
        return $found_user;
    }

    function insert_otp($mobile_no,$data) {
        $updated = $this->db->where('mobile_no',$mobile_no)->update('nodd_users',$data);
        return $updated;
    }

    function verify_otp($mobile_no,$otp) {
        $otp_correct = $this->db->where('mobile_no',$mobile_no)->where('otp',$otp)->count_all_results('nodd_users');
        return $otp_correct;
    }

    function update_password($mobile_no,$data) {
        $updated = $this->db->where('mobile_no',$mobile_no)->update('nodd_users',$data);
        return $updated;
    }

    function check_login_credentials($key,$credential,$password) {
        $user_found = $this->db->where(''.$key.'',$credential)->where('password',$password)->count_all_results('nodd_users');
        return $user_found;
    }

    function user_get_by_id($user_id) {
        $users = $this->db->where('id',$user_id)->
        select('full_name,mobile_no,no_nodd_attended,no_nodd_created,no_of_follower,no_of_following,organization_id,designation_id,university_id,qualification_id,image')->
        get('nodd_users');
        return $users->result_array();
    }

    public function interest_get_by_id($user_id) {
        $interest = $this->db->where('user_id',$user_id)->select('id,interest')->get('nodd_interest');
        return $interest->result_array();
    }

    public function sub_interest_get_by_id($user_id,$interest_id) {
        $interest = $this->db->where('user_id',$user_id)->where('interest_id',$interest_id)->select('sub_interest')->get('nodd_subinterest');
        return $interest->result_array();
    }

    public function organization_get_by_id($organization_id) {
        $organization = $this->db->where('id',$organization_id)->select('name')->get('nodd_organization');
        return $organization->result_array()[0]['name'];
    }

    public function qualification_get_by_id($qualification_id) {
        $organization = $this->db->where('id',$organization_id)->select('qualification')->get('nodd_qualification');
        return $organization->result_array()[0]['qualification'];
    }

    public function designation_get_by_id($designation_id) {
        $organization = $this->db->where('id',$organization_id)->select('designation')->get('nodd_designation');
        return $organization->result_array()[0]['designation'];
    }

    public function university_get_by_id($university_id) {
        $organization = $this->db->where('id',$organization_id)->select('university')->get('nodd_university');
        return $organization->result_array()[0]['university'];
    }

    public function userfollow_model($data) {
        $this->db->insert('nodd_userfollow',$data);
        $id = $this->db->insert_id();
        return $id;
    }

    public function userbadges_model($data) {
        $this->db->insert('nodd_userbadges',$data);
        $id = $this->db->insert_id();
        return $id;        
    }

    public function get_userbadge($user_id) {
        $list = $this->db->where('nu.user_id',$user_id)->select('nb.badge')->from('nodd_userbadges as nu')
        ->join('nodd_badges as nb','nb.badge_no = nu.badge_id')->get();
        return $list->result_array();
    }

    public function usertags_model($data) {
        $this->db->insert('nodd_usertags',$data);
        $id = $this->db->insert_id();
        return $id;        
    }
    
    function get_user_by_mobile_no($mobile_no) {
        $user_found = $this->db->where('mobile_no',$mobile_no)->count_all_results('nodd_users');
        return $user_found;
    }
    
    public function clear_otp_and_mobile_verify($mobile_no,$data) {
	   $updated = $this->db->where('mobile_no',$mobile_no)->update('nodd_users',$data);
    }

    public function is_mobile_verified($key,$value) {
       $verify = $this->db->where(''.$key.'',$value)->select('mobile_verified')->get('nodd_users');
        return $verify->result_array()[0]['mobile_verified'];
    }

    public function share_insert($data)
    {
        $this->db->insert('nodd_share',$data);
        $id = $this->db->insert_id();
        return $id;   
    }

    public function check_usertags_exist($user_id,$tag_id) {
        return $this->db->where('user_id',$user_id)->where('tag_id',$tag_id)->count_all_results('nodd_usertags');
    }

    public function check_userbadge_exist($user_id,$badge_id) {
        return $this->db->where('user_id',$user_id)->where('badge_id',$badge_id)->count_all_results('nodd_userbadges');
    }

    public function nodd_referencecode_insert($data) {
        $this->db->insert('nodd_referencecode',$data);
        $id = $this->db->insert_id();
        return $id;
    }

    public function check_reference_code($reference_code_id) {
        $count = $this->db->where('referencecode_id',$reference_code_id)->count_all_results('nodd_share');
        return $count;

    }

    public function get_reference_code_mobile_no($reference_code) {
        $code = $this->db->where('reference_code',$reference_code)->select('id')->get('nodd_referencecode');
        return $code->result_array();
    }

    public function user_name_get_by_id($user_id) {
        $users = $this->db->where('id',$user_id)->select('mobile_no,full_name')->get('nodd_users');
        return $users->result_array()[0];
    }


    public function nodd_referencecode_update($data,$mobile_no) {
        $updated = $this->db->where('mobile_no',$mobile_no)->update('nodd_referencecode',$data);
        return $updated;
    }

    public function get_reference_code($mobile_no) {
        $result = $this->db->where('mobile_no',$mobile_no)->select('reference_code')->get('nodd_referencecode');
        return $result->result_array();
    }

    public function nodd_referencecode_select($mobile_no,$reference_code) {
        $count = $this->db->where('reference_code',$reference_code)->where('mobile_no',$mobile_no)->count_all_results('nodd_referencecode');
        return $count;
    }

    public function get_following_user_list($user_id) {
        $list = $this->db->where('nuf.user_id',$user_id)->select('nuf.followuser_id,nu.image')->
        from('nodd_userfollow as nuf')->join('nodd_users as nu','nu.id = nuf.user_id')->get();
        return $list->result_array();
    }

    public function get_follower_user_list($user_id) {
        $list = $this->db->where('nuf.followuser_id',$user_id)->select('nuf.user_id,nu.image')->
        from('nodd_userfollow as nuf')->join('nodd_users as nu','nu.id = nuf.user_id')->get();
        return $list->result_array();
    }    

    public function get_user_follower_count($user_id) {
        $count = $this->db->where('user_id',$user_id)->count_all_results('nodd_userfollow');
        return $count;
    }

    public function get_user_following_count($user_id) {
        $count = $this->db->where('followuser_id',$user_id)->count_all_results('nodd_userfollow');
        return $count;  
    }

    public function beam_image_insert($data) {
        $this->db->insert('nodd_beamimages',$data);
        $id = $this->db->insert_id();
        return $id;        
    }

    public function get_last_beam_image_id() {
        $im = $this->db->select_max('id')->get('nodd_beamimages');
        return $im->result_array();
    }

    public function get_beam_images($beam_id) {
        $images = $this->db->where('beam_id',$beam_id)->select('id,image')->get('nodd_beamimages');
        return $images->result_array();
    }

    public function delete_beam_image($beam_image_id) {
        return $this->db->where('id',$beam_image_id)->delete('nodd_beamimages');
    }

    public function beam_rating_insert($data) {
        $this->db->insert('nodd_beamrating',$data);
        $id = $this->db->insert_id();
        return $id;
    }

    public function common_friends($user_id,$other_user_id) {
        $sql = 'SELECT nuf.followuser_id,nu.image FROM nodd_userfollow  as nuf INNER JOIN nodd_users as nu 
        on nu.id = nuf.followuser_id WHERE user_id = '.$user_id.' AND 
        nuf.followuser_id IN (SELECT followuser_id FROM nodd_userfollow WHERE user_id = '.$other_user_id.')';
        $list = $this->db->query($sql);
        return $list->result_array();
    }

    public function social_login_insert($data) {
        $this->db->insert('nodd_sociallogin',$data);
        $id = $this->db->insert_id();
        return $id;
    }

    public function social_login_facebook_select($social_user_id) {
        $user = $this->db->where('facebook_user_id',$social_user_id)->select('user_id')->get('nodd_sociallogin');
        return $user->result_array();
    }

    public function social_login_linkedin_select($social_user_id) {
        $user = $this->db->where('linkedin_user_id',$social_user_id)->select('user_id')->get('nodd_sociallogin');
        return $user->result_array();
    }    

    public function organization_list_select() {
        $list = $this->db->select('id,name')->get('nodd_organization');
        return $list->result_array();
    }

    public function designation_list_select() {
        $list = $this->db->select('id,name')->get('nodd_designation');
        return $list->result_array();
    }  

    public function about_user_select($table_name,$name,$column_name) {
        $list = $this->db->where($column_name,$name)->select('id')->get($table_name);
        return $list->result_array();
    }  

    public function about_user_insert($table_name,$data) {
        $this->db->insert($table_name,$data);
        $id = $this->db->insert_id();
        return $id;
    }

}

?>