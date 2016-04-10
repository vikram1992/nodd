<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Nodd_organization extends CI_Migration {
	public function up(){
		$sql = 'CREATE TABLE `nodd_organization` (
			 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			 `name` varchar(100) NOT NULL,
			 `rank` int(11) NOT NULL,
			 PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$this->db->query($sql);		
	}
	public function down(){
		//$this->dbforge->drop_table('nodd_organization');
	}

}
