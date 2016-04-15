<?php

class BeamJoinsModel extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    
    function beamjoin_insert($data) {
        $insert = $this->db->insert('nodd_beamjoins',$data);
        //$num_rows = $this->db->affected_rows();
        return $insert;
    }

    function beamjoin_check($user_id,$beam_id) {
        $num_rows = $this->db->where('user_id',$user_id)->where('beam_id',$beam_id)->count_all_results('nodd_beamjoins');
        return $num_rows;
    }

    function beamjoin_update($user_id,$beam_id,$data) {
        $updated_value = $this->db->where('user_id',$user_id)->where('beam_id',$beam_id)->update('nodd_beamjoins',$data);
        return $updated_value;
    }

    function beam_date($beam_id) {
        $date = $this->db->where('id',$beam_id)->select('DATE_FORMAT(sug_date,"%Y-%m-%d") as formatted_date')->get('nodd_beamjoins');
        return $date->result_array()[0];
    }

    function beamjoin_get_by_id($user_id) {
        $beam_data = $this->db->where('user_id',$user_id)->where('dropout',0)->select('*')->get('nodd_beamjoins');
        return $beam_data->result_array();
    }

    function get_no_nodd_attended($user_id) {
        $no_nodd_created = $this->db->where('user_id',$user_id)->where('isApproved',1)->count_all_results('nodd_users');
        return $no_nodd_created;
    }

    function check_beam_by_user_id($user_id,$sug_date) {
        $date = $this->db->select('count(u.id) as tot')->from('nodd_users as u')->join('nodd_beamjoins as b','u.id = b.user_id','inner')
                ->join('nodd_beams as b1','b1.id = b.beam_id','inner')->where('u.id',$user_id)->where('iscancelled',0)->
                where('DATE(b1.sug_date)',date('Y-m-d',strtotime($sug_date)))->get();
        return $date->result_array()[0]['tot'];
    }

    function noddlist($beam_id){
        $list = $this->db->where('nb.beam_id',$beam_id)->select('nb.*,nu.image')->from('nodd_beamjoins as nb')
        ->join('nodd_users as nu','nu.id = nb.user_id')->get();
        return $list->result_array();
    }

}

?>