<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/

namespace app\model {

	use \app\service\DBService;

	class Categories {

		public static $webcache = null;
		public $info = null;

		public function  __construct(){
			if(self::$webcache ==NULL) self::$webcache = new RxCache('website');
			$this->info = self::$webcache->get('cats');
			if(!$this->info){
				self::updateCategories();
				self::$webcache->set('cats',$this->info);
			}
		}

		public function updateCategories(){
			$RDb = DBService::getDB();
			$this->info = array();
			$cats =  $RDb->fetchAll(
				"SELECT * FROM fanfiction_categories WHERE parentcatid = '-1' ORDER BY displayorder"
				);
			foreach ($cats AS $cat){
				$this->info[$cat->catid] = new Category($cat);
			}
		}

		public function getList($catids=NULL) {
			if($catids==NULL){
				return $this->info;
			}
			if(!is_array($catids)) $catids = explode(",", $catids);
			$cat_list = array();
			foreach ($catids AS $catid){
				if(isset($this->info[$catid]))
					$cat_list[] = $this->info[$catid];
			}
			return $cat_list;

		}

	}
}

