<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Nodd_userinvites extends CI_Migration {
	public function up(){
		$sql = 'CREATE TABLE `nodd_userinvites` (
			 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			 `user_id` int(11) unsigned NOT NULL,
			 `beam_id` int(11) unsigned NOT NULL,
 			 `inviteduser_id` int(11) unsigned NOT NULL,
			 PRIMARY KEY (`id`),
			 foreign key(`user_id`) references nodd_users(`id`) on delete cascade,
			 foreign key(`inviteduser_id`) references nodd_users(`id`) on delete cascade,
			 foreign key(`beam_id`) references nodd_beams(`id`) on delete cascade
			) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$this->db->query($sql);
	}
	public function down(){
		//$this->dbforge->drop_table('nodd_organization');
	}

}
