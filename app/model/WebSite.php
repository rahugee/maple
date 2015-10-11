<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/

namespace app\model {

	use \app\service\DBService;

	class WebSite {

		public static $webcache;
		public $info = array();

		public $id;

		public function  __construct(){
			if(self::$webcache ==NULL) self::$webcache = new RxCache('website');
			$this->info = self::$webcache->get('stats');
			if(!$this->info){
				$this->updateStats();
				self::$webcache->set('stats',$this->info);
			}
		}
		public function getTotalMembers(){
			return $this->info['members'];
		}
		public function getTotalStories(){
			return $this->info['stories'];
		}
		public function getTotalChapters(){
			return $this->info['chapters'];
		}
		public function getTotalReviews(){
			return $this->info['reviews'];
		}
		public function getTotalAuthors(){
			return $this->info['authors'];
		}

		public function updateStats(){
			$RDb = DBService::getDB();
			$mems =  $RDb->fetchAll(
					"SELECT COUNT(uid) as members FROM ".TABLE_AUTHORS
			);
			$this->info['members'] = $mems[0]->members;
			$stats =  $RDb->fetchAll(
					"SELECT * FROM fanfiction_stats LIMIT 1"
			);

			$this->info["stories"] = $stats[0]->stories;
			$this->info["authors"] = $stats[0]->authors;
			$this->info["members"] = $stats[0]->members;
			$this->info["reviews"] = $stats[0]->reviews;
			$this->info["reviewers"] = $stats[0]->reviewers;
			$this->info["wordcount"] = $stats[0]->wordcount;
			$this->info["chapters"] = $stats[0]->chapters;
			$this->info["series"] = $stats[0]->series;
		}

	}
}

