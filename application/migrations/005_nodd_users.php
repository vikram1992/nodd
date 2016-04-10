<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Nodd_users extends CI_Migration {
	public function up(){
		$sql = 'CREATE TABLE `nodd_users` (
			 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			 `full_name` varchar(150) NOT NULL,
			 `username` varchar(30) NOT NULL,
			 `password` varchar(100) NOT NULL,
			 `token` varchar(50) NOT NULL,
			 `mobile_no` varchar(20) NOT NULL,
			 `otp` varchar(10) NOT NULL,
			 `reference_code` varchar(50),
			 `organization_id` int(11) unsigned,
			 `designation_id` int(11) unsigned,
			 `university_id` int(11) unsigned,
			 `qualification_id` int(11) unsigned,
			 `latitude` decimal(10,6) NOT NULL,
			 `longitude` decimal(10,6) NOT NULL,
			 `referred_by` int(11),
			 `no_nodd_attended` int(11),
			 `no_nodd_created` int(11),
			 `no_of_follower` int(11),
			 `no_of_following` int(11),
			 `email` varchar(200),
			 `image` varchar(500),
			 PRIMARY KEY (`id`),
			 UNIQUE(`username`),
			 UNIQUE(`password`),
			 UNIQUE (`mobile_no`),
			 foreign key(`designation_id`) references nodd_designation(`id`) on delete cascade,
			 foreign key(`organization_id`) references nodd_organization(`id`) on delete cascade,
			 foreign key(`qualification_id`) references nodd_qualification(`id`) on delete cascade,
			 foreign key(`university_id`) references nodd_university(`id`) on delete cascade
			) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$this->db->query($sql);
	}
	public function down(){
		//$this->dbforge->drop_table('nodd_organization');
	}

}