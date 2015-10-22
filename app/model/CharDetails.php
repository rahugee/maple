<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/
/**
 * Description of User, it basically extends AbstractUser and implemetns atleast two methods
 *
 */
namespace app\model {

	use \app\service\DBService;

	class CharDetails
	{

		public $id;
		public $details;

		public function  __construct($id)
		{
			$this->id = $id;
		}

		public function setDetails($details)
		{
			$this->details = $details;
		}

		public function fetchDetails()
		{
			//sprintf("SELECT * FROM charactor_user WHERE charid=%d", $this->id);
			$RDb = DBService::getDB();
			$details = $RDb->fetchAll(
				"SELECT * FROM charactor_user WHERE charid=%d", $this->id
			);
			$this->setDetails($details[0]);
		}

		public static function getChars( $search = "",$offSet = 0,$orderBy = "charname")
		{
			$RDb = DBService::getDB();
			$users = $RDb->fetchAll(
				"SELECT *, trim(charname) as charname FROM charactor_user AS c
				WHERE charname like %s
				ORDER BY %s LIMIT %d, 50", "'%" . $search . "%'", $orderBy, $offSet
			);
			return $users;
		}

		public static function getByChapter($sid, $chapid)
		{
			$RDb = DBService::getDB();
			$users = $RDb->fetchAll(
				"SELECT *, trim(charname) as charname
				FROM  charactor_chapter AS cc, charactor_user AS cu
				WHERE cc.charid=cu.charid AND sid = %d AND chapid = %d
				ORDER BY charname", $sid, $chapid
			);
			return $users;
		}

	}
}