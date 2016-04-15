<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Nodd_sociallogin extends CI_Migration {

	public function up(){
		$sql = 'CREATE TABLE `nodd_sociallogin` (
			 `id` int(11) NOT NULL AUTO_INCREMENT,
			 `user_id` int(11) unsigned NOT NULL,
			 `facebook_user_id` int(11) NOT NULL,
			 `linkedin_user_id` int(11) NOT NULL,
			 PRIMARY KEY (`id`),
			 FOREIGN KEY (`user_id`) REFERENCES `nodd_users` (`id`) ON DELETE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$this->db->query($sql);
	}

	public function down(){
		//$this->dbforge->drop_table('nodd_organization');
	}

}
