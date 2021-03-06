<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/

namespace app\model {

    use \app\service\DBService;
    use \app\utils\TextUtils;
    use \app\utils\Maple;

    class Reviews
    {

        public $id;
        public $details;
        private static $QUERY_CHAPTER_REVIEWS = "SELECT review.reviewid, review.respond, review.review, review.uid as uid,
			review.reviewer, review.rating,
			UNIX_TIMESTAMP(review.date) as date
			FROM fanfiction_reviews as review
			WHERE review.review != 'No Review'";

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

        public static function getByChapter($sid, $chapid, $page = 0)
        {
            $RDb = DBService::getDB();
            //$RDb->printQ = TRUE;
            $reviews = $RDb->fetchAll("SELECT review.reviewid, review.respond, review.review, review.uid as uid,
			review.reviewer, review.rating,
			UNIX_TIMESTAMP(review.date) as date
			FROM fanfiction_reviews as review WHERE review.chapid = '%d' ORDER BY date asc limit " . ($page * 10) . ", 10", $chapid
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


        public static function addComment($sid, $chapid, $uid, $reviewer, $review, $rating)
        {
            $RDb = DBService::getDB();
            $review = TextUtils::format_story(strip_tags(TextUtils::descript($_POST['review']), Maple::$ALLOWED_TAGS));
            $RDb->update(
                "INSERT INTO fanfiction_reviews (item, type, reviewer, review, rating, date, uid, chapid)
				VALUES ('$sid', 'ST', '$reviewer', '$review', '$rating', now(), '" . $uid . "', '$chapid')"
            );
            $records = $RDb->fetchAll(
                "SELECT chapid,count(*) as reviews, sum(rating) as ratings FROM fanfiction_reviews
				WHERE item=%d AND type='ST' GROUP BY  chapid", $sid
            );

            $totalReviews = 0; $totalRatings = 0; $totalRating = 0;
            $chapterReviews = 0; $chapterRatings = 0; $chapterRating=0;

            if ($records != null && count($records)) {
                foreach ($records as $key => $record) {
                    $totalReviews = +$record->reviews;
                    $totalRatings = +$record->ratings;
                    if ($record->chapid == $chapid) {
                        $chapterReviews =$record->reviews;
                        $chapterRatings = $record->ratings;
                    }
                }
                if($chapterReviews>0){
                    $chapterRating = $chapterRatings / $chapterReviews;
                    $RDb->update("UPDATE fanfiction_chapters SET reviews = %d, rating = %d
				        WHERE chapid=%d", $chapterReviews, $chapterRating, $chapid);
                }
                if($totalReviews>0){
                    $totalRating = $totalRatings / $totalReviews;
                    $RDb->update("UPDATE fanfiction_stories SET reviews = %d, rating = %d
				        WHERE chapid=%d", $totalReviews, $totalRating, $sid);
                }
            }
            return array(
                "sid" => $sid,
                "rating" => $totalRating, "reviews" => $totalReviews,
                "chapter" => array(
                    "sid" => $chapid,
                    "rating" => $chapterRating,
                    "reviews" => $chapterReviews
                )
            );

        }

    }
}
