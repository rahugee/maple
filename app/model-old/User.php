<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/

include_once (RUDRA . "/boot/model/AbstractUser.php");

/**
 * Description of User, it basically extends AbstractUser and implemetns atleast two methods
 *
 * @Model(sessionUser)
*/
class User extends AbstractUser {

	public function getToken(){
		return $this->uid;
	}

	public function auth($username, $passowrd) {
		$RDb = DBService::getDB();
		$res =  $RDb->fetchAll(
				"SELECT *, auth.uid as uid FROM ".TABLE_AUTHORS." as auth
				LEFT JOIN fanfiction_authorprefs AS ap ON ap.uid = auth.uid
				WHERE penname = '%s'",$username);
		
		if(count($res)==0){
			return false;
		} else {
			$encryptedpassword = md5($passowrd);
			$row = $res[0];
			if($row->password!=$encryptedpassword){
				return false;
			} else {
				$name = empty($row->realname) ? $row->penname : $row->realname;
				$this->set('name',$name);
				$this->uname = $row->penname;
				$this->uid  = $row->uid;
				$this->setValid();
			}
			return true;
		}
	}
	public function getName() {
		return $this->get('name');
	}

	public function unauth() {
		$this->setInValid();
	}

}
