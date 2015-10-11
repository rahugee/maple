<?php

/**
 * @Handler(index)
 * 
 * @author lt
 *
 */
class IndexPage extends AbstractHandler {

	/**
	 * @param Smarty $viewModel
	 * @param Header $header
	 * @param DataModel $dataModel
	 * @param User $user
	 * @param string $view
	 * @param string $module
	 * @return string
	 */
	public function invokeHandler(Smarty $viewModel,Header $header, DataModel $dataModel,
			User $user,$view="empty",$module="index_page") {

		$header->title("ThEroticStories");
		
		$header->import($module);
		
		return $view;
	}

}