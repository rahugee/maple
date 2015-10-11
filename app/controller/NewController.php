<?php
/*
 * To change this license header, choose License Headers in Project Properties. To change this template file, choose Tools | Templates and open the template in the editor.
 */

namespace app\controller {

	class NewController extends AbstractController {

		public function defineGlobalVars($file) {
			$GLOBALS_VARS = parse_ini_file ( CONFIG_PATH . "/" . $file, TRUE );
			foreach ( $GLOBALS_VARS as $key => $value ) {
				define ( $key, $value );
			}
		}
		
		
		/**
		 *
		 * @RequestMapping(url="jrson/{action}/cache",type=json,cache=true)
		 *
		 */
		public function cacheJSONController($action = "storieslist") {
			//$this->defineGlobalVars ( '../meta/dbvars.conf' );
			return $action;
		}
		
		/*
		 * RudraX::mapRequest ( "story/{sid}/{chap}", function ($page = "frontpage", $sid = NULL, $chap = NULL) {
		 * global $controller;
		 * defineGlobalVars ( 'dbvars.conf' );
		 * $controller = RudraX::getPageController ();
		 * if ($sid == NULL) {
		 * $controller->invokeHandler ( "SearchStory" );
		 * } else
		 * $controller->invokeHandler ( "viewStory" );
		 * } );
		 * RudraX::mapRequest ( "story/{sid}", function ($page = "frontpage", $sid = NULL) {
		 * global $controller;
		 * defineGlobalVars ( 'dbvars.conf' );
		 * $controller = RudraX::getPageController ();
		 * if ($sid == NULL) {
		 * $controller->invokeHandler ( "SearchStory" );
		 * } else
		 * $controller->invokeHandler ( "viewStory" );
		 * } );
		 * RudraX::mapRequest ( "story", function ($page = "frontpage") {
		 * global $controller;
		 * defineGlobalVars ( 'dbvars.conf' );
		 * $controller = RudraX::getPageController ();
		 * $controller->invokeHandler ( "SearchStory" );
		 * } );
		 * RudraX::mapRequest ( "user/{uid}", function ($page = "frontpage", $uid = NULL) {
		 * global $controller;
		 * defineGlobalVars ( 'dbvars.conf' );
		 * $controller = RudraX::getPageController ();
		 * if ($uid == NULL) {
		 * $controller->invokeHandler ( "profiles" );
		 * } else
		 * $controller->invokeHandler ( "profile" );
		 * } );
		 * RudraX::mapRequest ( "user", function ($page = "frontpage") {
		 * global $controller;
		 * defineGlobalVars ( 'dbvars.conf' );
		 * $controller = RudraX::getPageController ();
		 * $controller->invokeHandler ( "profiles" );
		 * } );
		 *
		 * RudraX::mapRequest ( "actor/{cid}", function ($page = "frontpage", $cid = NULL) {
		 * global $controller;
		 * defineGlobalVars ( 'dbvars.conf' );
		 * $controller = RudraX::getPageController ();
		 * if ($cid == NULL) {
		 * $controller->invokeHandler ( "charProfiles" );
		 * } else
		 * $controller->invokeHandler ( "charProfile" );
		 * } );
		 * RudraX::mapRequest ( "actor", function ($page = "frontpage") {
		 * global $controller;
		 * defineGlobalVars ( 'dbvars.conf' );
		 * $controller = RudraX::getPageController ();
		 * $controller->invokeHandler ( "charProfiles" );
		 * } );
		 *
		 */
	}


}

