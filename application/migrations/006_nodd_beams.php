<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Nodd_beams extends CI_Migration {
	public function up(){
		$sql = 'CREATE TABLE `nodd_beams` (
			 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			 `user_id` int(11) unsigned NOT NULL,
			 `title` varchar(200) NOT NULL,
			 `description` varchar(2000) NOT NULL,
			 `sug_place` varchar(100) NOT NULL,
			 `sug_date` datetime NOT NULL,
			 `sug_noppl` int(11) NOT NULL,
			 `sug_venue` varchar(100) NOT NULL,
			 `isFinalized` tinyint(1) DEFAULT 0,
			 `fin_place` varchar(100),
			 `fin_date` datetime,
			 `fin_noppl` int(11),
			 `fin_venue` varchar(100),
			 `price_range` varchar(10),
			 `isNoddOut` tinyint(1) DEFAULT 0,
 			 `iscancelled` tinyint(1) DEFAULT 0,
			 PRIMARY KEY (`id`),
			 foreign key(`user_id`) references nodd_users(`id`) on delete cascade,
			 CONSTRAINT `unique_beam` UNIQUE (`user_id`,`sug_date`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$this->db->query($sql);
	}
	public function down(){
		//$this->dbforge->drop_table('nodd_organization');
	}

}
