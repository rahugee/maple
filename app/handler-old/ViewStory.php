<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once (MODEL_PATH . "/Stories.php");
include_once (MODEL_PATH . "/Story.php");
include_once (MODEL_PATH . "/CharDetails.php");
include_once (MODEL_PATH . "/Reviews.php");
/**
 *
 * @Handler(view_story)
 *
 * @author lt
 *        
 */
class ViewStory extends AbstractHandler {
	public static $SIZE = 5;
	public static $GAP = 2; // ($SIZE/2);
	public static $CENTER = 4; // 2+$GAP;
	public function invokeHandler(RequestData $data, User $user) {
		$sid = $data->get ( "sid", "");
		return new Story ($sid);
	}
}
