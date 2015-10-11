<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/

class AbstractTable {

	public $tablename;
	public $query;
	public $data;

	public function __construct($tablename) {
		$this->tablename = $tablename;
	}

	public function prepareQuery() {
		return $this->query = 'select * from ' . $this->tablename;
	}

	public function execute() {
		$this->prepareQuery();
		//echo "--".$this->query;
		$this->data = mysql_query($this->query);
	}

	public function fetch() {
		return mysql_fetch_array($this->data);
	}

}
