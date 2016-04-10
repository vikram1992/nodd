<?php

class NoddListModel extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    

    function my_nodd_model($user_id) {
        $list = $this->db->where('nb.user_id',$user_id)->where('nb.iscancelled',0)->select('nb.id,nu.id as user_id,nu.full_name,nu.image,nb.title,nb.description,nb.sug_place,nb.sug_noppl,DATE_FORMAT(nb.sug_date,"%Y-%m-%d") as sug_formatted_date,nb.sug_place,nb.fin_place,nb.fin_noppl,DATE_FORMAT(nb.fin_date,"%Y-%m-%d") as fin_formatted_date,nb.price_range,nb.isNoddOut')
        ->from('nodd_beams as nb')->join('nodd_users as nu','nu.id = nb.user_id')->get();
        return $list->result_array();
    }


    function my_nodd_user_list($beam_id) {
        $list = $this->db->select('nb.user_id,nb.isapproved,nu.image')->where('nb.beam_id',$beam_id)->from('nodd_beamjoins as nb')
        ->join('nodd_users as nu','nu.id = nb.user_id')->get();
        return $list->result_array();
    }

    function nodd_attending($user_id,$approval) {
        $list = $this->db->select('nb.user_id,nu.image,nb.beam_id')->where('nb.dropout',0)->where('nb.isapproved',$approval)
        ->where('nb.user_id',$user_id)->from('nodd_beamjoins as nb')
        ->join('nodd_users as nu','nu.id = nb.user_id')->get();
        return $list->result_array();        
    }

    function get_latitude_longitude($user_id) {
        $latlng = $this->db->select('latitude,longitude')->where('id',$user_id)->get('nodd_users');
        return $latlng->result_array()[0];
    }

    function near_me_model($setlat,$setlong,$user_id) {
        $query = "SELECT nu.id,( 3959 * acos( cos( radians(".$setlat.") ) * cos( radians(nu.latitude) ) * cos( radians(nu.longitude) - radians(".$setlong.") )
            + sin( radians(".$setlat.") ) * sin( radians(nu.latitude) ) ) ) AS 'distance'
            FROM nodd_users as nu inner join nodd_beams as nb on nb.user_id=nu.id and nb.user_id != ".$user_id." HAVING distance < ".CIRCLE;
        $result = $this->db->query($query);  
        return $result->result_array();
    }

    function my_circle_model($user_id) {
        $list = $this->db->select('followuser_id')->where('user_id',$user_id)->get('nodd_userfollow');
        return $list->result_array();
    }

    function nodd_attending_user_list($beam_id) {
        $list = $this->db->where('nb.id',$beam_id)->where('nb.iscancelled',0)->select('nb.id,nu.id as user_id,nu.full_name,nu.image,nb.title,nb.description,nb.sug_place,nb.sug_noppl,DATE_FORMAT(nb.sug_date,"%Y-%m-%d") as sug_formatted_date,nb.sug_place,nb.fin_place,nb.fin_noppl,DATE_FORMAT(nb.fin_date,"%Y-%m-%d") as fin_formatted_date,nb.price_range,nb.isNoddOut')
        ->from('nodd_beams as nb')->join('nodd_users as nu','nu.id = nb.user_id')->get();
        return $list->result_array();
    }
}

?>