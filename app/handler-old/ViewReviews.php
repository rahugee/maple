<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once (MODEL_PATH . "/Reviews.php");

/**
 *
 * @Handler(view_comments)
 *
 * @author lt
 *        
 */
class ViewReviews extends AbstractHandler {
	public static $SIZE = 5;
	public static $GAP = 2; // ($SIZE/2);
	public static $CENTER = 4; // 2+$GAP;
	public function invokeHandler(User $user, RequestData $data) {
		$chapid = $data->get ( "chapid", NULL );
		$itemid = $data->get ( "sid", 0 );
		if ($chapid != NULL && is_numeric ( $chapid )) {
			return Reviews::getByChapter ( $itemid, $chapid );
		} else {
			return null;
		}
	}
}
