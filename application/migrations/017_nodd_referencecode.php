<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Nodd_referencecode extends CI_Migration {

	public function up(){
		$sql = 'CREATE TABLE `nodd_referencecode` (
			 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			 `mobile_no` varchar(20) NOT NULL,
 			 `reference_code` varchar(50) NULL,
 			 UNIQUE (`mobile_no`),
 			 PRIMARY KEY(`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$this->db->query($sql);
	}
	
	public function down(){
		//$this->dbforge->drop_table('nodd_organization');
	}

}
