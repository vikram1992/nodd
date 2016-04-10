<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Nodd_userbadges extends CI_Migration {
	public function up(){
		$sql = 'CREATE TABLE `nodd_userbadges` (
			 `user_id` int(11) unsigned NOT NULL,
			 `badge_id` int(11) unsigned NOT NULL,
 			 `received_date` datetime NOT NULL,
			 foreign key(`badge_id`) references nodd_badges(`id`) on delete cascade
			) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$this->db->query($sql);
	}
	public function down(){
		//$this->dbforge->drop_table('nodd_organization');
	}

}
