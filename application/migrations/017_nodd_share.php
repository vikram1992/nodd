<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Nodd_share extends CI_Migration {
	public function up(){
		$sql = 'CREATE TABLE `nodd_share` (
			 `reference_code` varchar(50) NOT NULL,
			 `mobile_no` varchar(20) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$this->db->query($sql);
	}
	public function down(){
		//$this->dbforge->drop_table('nodd_organization');
	}

}
