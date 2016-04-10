<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Nodd_university extends CI_Migration {
	public function up(){
		$sql = 'CREATE TABLE `nodd_university` (
			 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			 `university` varchar(100) NOT NULL,
			 PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$this->db->query($sql);
	}
	public function down(){
		//$this->dbforge->drop_table('nodd_university');
	}
}
