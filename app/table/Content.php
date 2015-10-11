<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/

include_once("AbstractTable.php");

/**
 * Description of SignalCom
 *
 * @author Lalit
*/
class Content extends AbstractTable {

	private $name;

	//put your code here
	public function __construct() {
		parent::__construct('content');
	}

	public function name($name) {
		$this->name = $name;
	}

	public function prepareQuery() {
		parent::prepareQuery();
		$this->query .= " where name='website' OR name='link'";
		if (isset($this->name) && !empty($this->name)) {
			$this->query .= " OR name = '" . $this->name . "';";
		}
	}
}
