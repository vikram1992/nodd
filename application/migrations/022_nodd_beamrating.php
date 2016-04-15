<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Nodd_beamrating extends CI_Migration {

	public function up(){
		$sql = 'CREATE TABLE `nodd_beamrating` (
			 `id` int(11) NOT NULL AUTO_INCREMENT,
			 `user_id` int(11) unsigned NOT NULL,
			 `beam_id` int(11) unsigned NOT NULL,
			 `rating` varchar(20) NOT NULL,
			 PRIMARY KEY (`id`),
			 FOREIGN KEY (`beam_id`) REFERENCES `nodd_beams` (`id`) ON DELETE CASCADE,
			 FOREIGN KEY (`user_id`) REFERENCES `nodd_users` (`id`) ON DELETE CASCADE
			) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$this->db->query($sql);
	}

	public function down(){
		//$this->dbforge->drop_table('nodd_organization');
	}

}
