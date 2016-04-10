<?php

class BeamModel extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    
    function beam_insert($data) {
        $this->db->insert('nodd_beams',$data);
        $id = $this->db->insert_id();
        return $id;
    }

    function check_beam_created($user_id,$date) {
        $beam_id = $this->db->where('user_id',$user_id)->where('sug_date',$date)->count_all_results('nodd_beams');
        return $beam_id;
    }

    function beam_update($beam_id,$data) {
        $updated_value = $this->db->where('id',$beam_id)->update('nodd_beams',$data);
        return $updated_value;
    }

    function beam_date($beam_id) {
        $date = $this->db->where('id',$beam_id)->select('DATE_FORMAT(sug_date,"%Y-%m-%d") as formatted_date')->get('nodd_beams');
        return $date->result_array();
    }

    function beam_get_by_id($beam_id) {
        $beam_data = $this->db->select('nb.*,nu.image')->from('nodd_beams as nb')
        ->join('nodd_users as nu','nu.id=nb.user_id')->where('nb.id',$beam_id)->get();
        return $beam_data->result_array();
    }

    function get_no_nodd_created($user_id) {
        $no_nodd_created = $this->db->where('id',$user_id)->count_all_results('nodd_users');
        return $no_nodd_created;
    }

    function userinvites_model($data) {
        $this->db->insert('nodd_userinvites',$data);
        $id = $this->db->insert_id();
        return $id;
    }

    function bookmark_model($data) {
        $this->db->insert('nodd_userbookmark',$data);
    }

    function check_bookmark_exist($user_id,$beam_id) {
        return $this->db->where('user_id',$user_id)->where('beam_id',$beam_id)->count_all_results('nodd_userbookmark');
    }

    function beamtags_model($data) {
        $this->db->insert('nodd_beamtags',$data);
    }

    function check_beamtag_exist($tag_id,$beam_id) {
        return $this->db->where('tag_id',$tag_id)->where('beam_id',$beam_id)->count_all_results('nodd_beamtags');
    }

    function check_beam_by_user_id($user_id,$sug_date) {
        $data = $this->db->select('count(id) as tot')->where('user_id',$user_id)->
                where('DATE(sug_date)',date('Y-m-d',strtotime($sug_date)))->get('nodd_beams');
        return $data->result_array()[0]['tot'];
    }

    function get_invited_user_beam_id($user_id,$sug_date) {
        $date = $this->db->where('user_id',$user_id)->select('DATE_FORMAT(sug_date,"%Y-%m-%d") as formatted_date,id')
        ->having('formatted_date',$sug_date)->get('nodd_beams');
        return $date->result_array();
    }

}

?>