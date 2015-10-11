<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/

namespace app\model {
	
	class Category {

		public $id;
		public $info;

		public function  __construct($info){
			$this->info = $info;
			$this->id = $info->catid;
		}
		public function getId(){
			return $this->id;
		}

		public function getName(){
			return $this->info->category;
		}
		public function getCount(){
			return $this->info->numitems;
		}
	}
}

