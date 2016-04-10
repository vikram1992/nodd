<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Nodd_usertags extends CI_Migration {
	public function up(){
		$sql = 'CREATE TABLE `nodd_usertags` (
			 `user_id` int(11) unsigned NOT NULL,
 			 `tag_id` int(11) unsigned NOT NULL,
			 foreign key(`user_id`) references nodd_users(`id`) on delete cascade,
			 foreign key(`tag_id`) references nodd_tags(`id`) on delete cascade
			) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$this->db->query($sql);
	}
	public function down(){
		//$this->dbforge->drop_table('nodd_organization');
	}

}
