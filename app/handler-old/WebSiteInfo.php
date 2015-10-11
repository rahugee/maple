<?php

include_once (MODEL_PATH . "/WebSite.php");
include_once (MODEL_PATH . "/Categories.php");

/**
 * @Handler(websiteinfo)
 * @RequestParams(true)
 */
class WebSiteInfo extends AbstractHandler {
	
	/**
	 *
	 * @param Smarty $viewModel        	
	 * @param Header $header        	
	 * @param DataModel $dataModel        	
	 * @param User $user        	
	 * @param string $view        	
	 * @param string $module        	
	 * @return string
	 */
	public function invokeHandler(User $user, $view = "empty", $module = "index_page", RequestData $data,$info="NO") {
		if ($info == "STATS") {
			return new WebSite ();
		} else if ($info == "CATEGORIES") {
			return new Categories ();
		} else if ($info == "CLASSES") {
			$RDb = DBService::getDB();
			return $RDb->fetchAll ( "SELECT class_id id, class_name name, classtype_id, classtype_name,classtype_title
					FROM `fanfiction_classes` as c,fanfiction_classtypes as ct 
					WHERE ct.classtype_id=c.class_type" );
		}
		return $data;
	}
}