<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once (MODEL_PATH . "/Stories.php");
/**
 *
 * @Handler(storieslist_user)
 *
 * @author lt
 *        
 */
class StoriesListByUser extends AbstractHandler {
	public function invokeHandler(RequestData $data) {
		
		$uid = $data->get ( "uid");
		$stories = new Stories();
		if ($uid != NULL && is_numeric ( $uid )) {
			return $stories->byUser($uid);
		} else {
			return null;
		}
	}
}
