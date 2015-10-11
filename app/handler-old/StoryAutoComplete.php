<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/
include_once(MODEL_PATH . "/WebSite.php");
include_once(MODEL_PATH . "/Categories.php");
include_once(MODEL_PATH . "/Stories.php");
/**
 * 
 * @Handler(story_autocomplete)
 * 
 * @author lt
 *
 */
class StoryAutoComplete extends AbstractHandler {

	public function invokeHandler(RequestData $data) {
		$search = $data->get("search","");
		
		$response = array();
		$RDb = DBService::getDB();
		$res =  $RDb->fetchAll("SELECT * FROM search_cache
				WHERE UPPER(search_text) like UPPER(%s)
				ORDER BY search_text","'%".$search."%'");
		if(count($res)==0){
			$res =  $RDb->fetchAll("SELECT title as search_text FROM fanfiction_stories
					WHERE UPPER(title) like UPPER(%s)
					ORDER BY title","'%".$search."%'");
		}
		if(count($res)==0){
			$response['error'] = true;
			return false;
		} else {
			$response['data'] = $res;
			$response['error'] = false;
			return $response;
		}
	}

}
