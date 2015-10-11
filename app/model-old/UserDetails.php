<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/
class UserDetails {

	public $id;
	public $details;

	public function  __construct($id){
		$this->id = $id;
	}

	public function setDetails($details){

	}

	public function fetchDetails(){
		$RDb = DBService::getDB();
		$details =  $RDb->fetchAll(
				"SELECT * FROM fanfiction_authors WHERE uid=%d",$this->id
		);
		$this->details = $details[0];
	}

	public function getRealName(){
		$details = $this->details;
		return $details->realname;
	}

	public static function getUsers($minStoryCount=0,$offSet=0,$limit=20,$orderBy="penname",$search=""){
		$RDb = DBService::getDB();
// 		printf("SELECT * FROM fanfiction_authors AS u,fanfiction_authorstats AS stats
// 				WHERE u.uid=stats.uid AND u.stories>%d LIMIT %d, 20",$minStoryCount,$offSet);
		$users =  $RDb->fetchAll(
				"SELECT *, trim(penname) as penname FROM fanfiction_authors AS u,fanfiction_authorstats AS stats
				WHERE u.uid=stats.uid AND stats.stories>=%d AND penname like %s
				ORDER BY %s LIMIT %d,%d",$minStoryCount,"'%".$search."%'",$orderBy,$offSet,$limit
		);
		return $users;
	}

}
