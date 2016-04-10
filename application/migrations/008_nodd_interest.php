<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Nodd_interest extends CI_Migration {
	public function up(){
		$sql = 'CREATE TABLE `nodd_interest` (
			 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			 `user_id` int(11) unsigned NOT NULL,
			 `interest` varchar(100) NOT NULL,
			 PRIMARY KEY (`id`),
			 foreign key(`user_id`) references nodd_users(`id`) on delete cascade
			) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$this->db->query($sql);
	}
	public function down(){
		//$this->dbforge->drop_table('nodd_organization');
	}

}
