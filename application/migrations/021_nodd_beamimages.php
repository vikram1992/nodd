<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Nodd_beamimages extends CI_Migration {
	public function up(){
		$sql = 'CREATE TABLE `nodd_beamimages` (
			 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			 `beam_id` int(11) unsigned NOT NULL,
			 `image` varchar(500),
			 primary key (id),
			 foreign key(`beam_id`) references nodd_beams(`id`) on delete cascade
			) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$this->db->query($sql);
	}
	public function down(){
		//$this->dbforge->drop_table('nodd_organization');
	}

}