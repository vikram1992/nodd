<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Nodd_share extends CI_Migration {

	public function up(){
		$sql = 'CREATE TABLE `nodd_share` (
			 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			 `referencecode_id` int(11) unsigned NOT NULL,
			 `mobile_no` varchar(20) NOT NULL,
			 PRIMARY KEY(`id`),
			 foreign key(`referencecode_id`) references nodd_referencecode(`id`) on delete cascade
			) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$this->db->query($sql);
	}

	public function down(){
		//$this->dbforge->drop_table('nodd_organization');
	}

}
