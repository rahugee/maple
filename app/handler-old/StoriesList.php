<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once (MODEL_PATH . "/WebSite.php");
include_once (MODEL_PATH . "/Categories.php");
include_once (MODEL_PATH . "/Stories.php");

/**
 * @Handler(storieslist)
 * @RequestParams(true)
 * 
 *
 */
class StoriesList extends AbstractHandler {
	public function invokeHandler(RequestData $data, $search="") {
		
		$order_by = $data->get ( "order_by", "updated" );
		$search_by = $data->get ( "search_by", "title" );
		$categories = $data->get ( "categories", array () );
		$language = $data->get ( "language", "");
		$type = $data->get ( "type", "");
		$stories = new Stories ();
		$stories->RELEVANCE = empty($search) ? 0 : 0.1;
		$stories->setCategories ( $categories );
		$stories->setClasses($type, $language );
		if ($search_by == 'title') {
			$stories_results = $stories->searchByTitle ( $search, 0, $order_by );
		} else if ($search_by == 'text') {
			$stories_results = $stories->searchByText ( $search, 0, $order_by );
		} else {
			$stories_results = $stories->searchByAll ( $search, 0, $order_by );
		}
		
		if (count ( $stories_results )) {
			// echo "REPLACE INTO search_cache(search_text) values(".$search.")";
			$RDb = DBService::getDB();
			$res = $RDb->update ( "REPLACE INTO search_cache(search_text) values('" . $search . "')" );
		}
		// print_r($stories->get(0));
		
		return $stories_results;
	}
}
