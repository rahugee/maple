<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\model {

	use \app\service\DBService;

	class Story
	{
		public $id;

		public function __construct($sid)
		{
			$this->id = $sid;
			$this->fetch();
		}

		public function fetch()
		{
			$RDb = DBService::getDB();
			$stories = $RDb->fetchAll("SELECT penname, auth.uid as uid, story.*, UNIX_TIMESTAMP(story.date) as date,
				UNIX_TIMESTAMP(story.updated) as updated, story.validated as valid
				FROM " . TABLE_STORIES . " as story, " . TABLE_AUTHORS . " as auth
				WHERE story.sid = '" . $this->id . "' AND story.uid = auth.uid");
			$this->info = $stories [0];

            //var_dump($this->info);

			if (isset ($this->info)) {
				$stats = $RDb->fetchAll("SELECT * FROM stats_stories
					WHERE  sid = '" . $this->id . "'");
				$this->stats = $stats [0];

				if ($this->info->coauthors) {
					$this->coauthors = CoAuthors::resolveCoauthors($this->id);
				}

				$this->chapters = Chapter::getList($this->id);
			}
		}
	}
}
