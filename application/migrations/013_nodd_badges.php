<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Nodd_badges extends CI_Migration {
	public function up(){
		$sql = 'CREATE TABLE `nodd_badges` (
			 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			 `badge` varchar(100) NOT NULL,
 			 `badge_no` int(11) NOT NULL,
			 PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$this->db->query($sql);
	}
	public function down(){
		//$this->dbforge->drop_table('nodd_organization');
	}

}
