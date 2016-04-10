<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Nodd_userbookmark extends CI_Migration {
	public function up(){
		$sql = 'CREATE TABLE `nodd_userbookmark` (
			 `user_id` int(11) unsigned NOT NULL,
			 `beam_id` int(11) unsigned NOT NULL,
			 unique(beam_id,user_id),
			 foreign key(`user_id`) references nodd_users(`id`) on delete cascade,
			 foreign key(`beam_id`) references nodd_beams(`id`) on delete cascade
			) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$this->db->query($sql);
	}
	public function down(){
		//$this->dbforge->drop_table('nodd_organization');
	}

}
