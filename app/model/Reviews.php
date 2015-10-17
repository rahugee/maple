<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/

namespace app\model {

	use \app\service\DBService;

	class Reviews
	{

		public $id;
		public $details;
		private static $query = "SELECT review.reviewid, review.respond, review.review, review.uid as uid,
			review.reviewer, review.rating,
			UNIX_TIMESTAMP(review.date) as date,
			chapter.sid as sid, chapter.title as title, stories.title as story_title, chapter.inorder as inorder
			FROM fanfiction_reviews as review, fanfiction_chapters as chapter,  fanfiction_stories as stories
			WHERE chapter.chapid = review.chapid AND chapter.sid = stories.sid AND review.review != 'No Review'";

		public static function getChars($offSet = 0, $searchBy = "", $orderBy = "charname")
		{
			$RDb = DBService::getDB();
			$users = $RDb->fetchAll(
				"SELECT *, trim(charname) as charname FROM charactor_user AS c
				WHERE charname like %s
				ORDER BY %s LIMIT %d, 50", "'%" . $searchBy . "%'", $orderBy, $offSet
			);
			return $users;
		}

		public static function getByStory($sid)
		{
			$RDb = DBService::getDB();
			//$RDb->printQ = TRUE;
			$reviews = $RDb->fetchAll(self::$query .
				" AND chapter.sid = '%d' ORDER BY date desc", $sid
			);
			return $reviews;
		}

		public static function getByChapter($sid, $chapid, $page=0)
		{
			$RDb = DBService::getDB();
			//$RDb->printQ = TRUE;
			$reviews = $RDb->fetchAll(self::$query .
				" AND chapter.chapid = '%d' ORDER BY date desc limit ".($page*10).", 10", $chapid
			);
			return $reviews;
		}

		public static function getByUser($uid)
		{
			$RDb = DBService::getDB();
			//$RDb->printQ = TRUE;
			$reviews = $RDb->fetchAll(
				self::$query .
				" AND review.uid = %d ORDER BY date desc", $uid
			);
			return $reviews;
		}

		public static function getRatingByChapterForUser($sid, $chapid, $uid)
		{
			$RDb = DBService::getDB();
			//$RDb->printQ = TRUE;
			$review = $RDb->fetch(
				"SELECT *
				FROM all_ratings
				WHERE chapid=%d AND uid=%d", $chapid, $uid
			);
			if ($review == NULL) return 0;
			else return $review->rating;
		}

	}
}
