<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/

namespace app\model {

	use \app\service\DBService;

	class Chapter
	{

		public $chapid;

		public function  __construct($chapid)
		{
			$this->chapid = $chapid;
		}

		public static function getList($sid)
		{
			$RDb = DBService::getDB();
			$qury = "SELECT chap.*, penname, chapt.chaptags as chap_tags
				FROM (fanfiction_chapters as chap, " . TABLE_AUTHORS . " as auth,fanfiction_chaptags as chapt )
				WHERE sid = '$sid' AND chap.chapid=chapt.chapid AND chap.uid = auth.uid 
				AND chap.validated > 0 ORDER BY inorder";
			//echo $qury;
			return $RDb->fetchAll(
				$qury
			);
		}

	}
}
