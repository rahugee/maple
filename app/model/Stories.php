<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/

namespace app\model {

    use \app\service\DBService;

    class Stories
    {

        private static $cats = null;

        public $RELEVANCE = 0.1;
        public $filter_cat = "";
        private $filter_clas = "";

        public function  __construct()
        {
            if (self::$cats == null) {
                self::$cats = new Categories();
            }
        }

        public function  setCategories($sel_cat)
        {
            foreach ($sel_cat as $ctg) {
                $this->filter_cat = $this->filter_cat . "
					OR stories.catid like '%," . $ctg . ",%'
					OR stories.catid like '" . $ctg . ",%'
					OR stories.catid like '%," . $ctg . "'
					OR stories.catid like '" . $ctg . "'";
            }
            if (count($sel_cat) != 0) {
                $this->filter_cat = "AND ( stories.catid = 'X'" . $this->filter_cat . ")";
            }
        }

        public function  setClasses($type, $language)
        {
            //$sel_clas = explode(",",$sel_clas_string);
            $class = array();
            if (strlen($language) > 0) {
                $class[] = $language;
            }
            if (strlen($type) > 0) {
                $class[] = $type;
            }
            $filter_clas = array();
            foreach ($class as $ctg) {
                $filter_clas[] =
                    "(stories.classes like '%," . $ctg . ",%'
					OR stories.classes like '" . $ctg . ",%'
					OR stories.classes like '%," . $ctg . "'
					OR stories.classes like '" . $ctg . "')";
            }
            if (count($filter_clas) != 0) {
                $this->filter_clas = "AND (" . implode(" AND ", $filter_clas) . ")";
            }
        }

        public function resolveValues($stories)
        {
            foreach ($stories AS $story) {
                CoAuthors::resolveCoauthors($story->sid);
                $story->cat_list = self::$cats->getList($story->catid);
            }
            return $stories;
        }

        public function byUser($uid, $offset = 0)
        {
            $RDb = DBService::getDB();
            $order_by = 'ORDER BY title';
            $stories = $RDb->fetchAll(
                "SELECT stories.*, penname, UNIX_TIMESTAMP(stories.date) as date,
				UNIX_TIMESTAMP(stories.updated) as updated
				FROM (" . TABLE_AUTHORS . " as authors, " . TABLE_STORIES . " as stories)
				LEFT JOIN " . TABLE_COAUTHORS . " as coauth ON coauth.sid = stories.sid
				WHERE authors.uid = stories.uid AND stories.validated > 0
				AND (stories.uid = '$uid' OR coauth.uid = '$uid')
				GROUP BY stories.sid " . $order_by . " LIMIT $offset, 500"
            );
            return $this->resolveValues($stories);
        }

        public function get($offset = 0, $order_by = 'updated', $order = 'DESC')
        {
            $RDb = DBService::getDB();
            //$order_by = 'ORDER BY updated DESC';
            $stories = $RDb->fetchAll(
                "SELECT stories.*, penname, UNIX_TIMESTAMP(stories.date) as date,
				UNIX_TIMESTAMP(stories.updated) as updated
				FROM (" . TABLE_AUTHORS . " as authors, " . TABLE_STORIES . " as stories)
				LEFT JOIN " . TABLE_COAUTHORS . " as coauth ON coauth.sid = stories.sid
				WHERE authors.uid = stories.uid AND stories.validated > 0
				GROUP BY stories.sid ORDER BY " . $order_by . " " . $order . " LIMIT $offset, 25"
            );
            return $this->resolveValues($stories);
        }

        public function searchByTitle($title = '', $offset = 0, $order_by = 'updated', $order = 'DESC')
        {
            $RDb = DBService::getDB();
            //$order_by = 'ORDER BY updated DESC';
            $stories = $RDb->fetchAll(
                "SELECT stories.*, penname, UNIX_TIMESTAMP(stories.date) as date,
				UNIX_TIMESTAMP(stories.updated) as updated,
				MATCH(title,summary,storynotes) AGAINST ('%s') as Relevance
				FROM (" . TABLE_AUTHORS . " as authors, " . TABLE_STORIES . " as stories)
				LEFT JOIN " . TABLE_COAUTHORS . " as coauth ON coauth.sid = stories.sid
				WHERE authors.uid = stories.uid %s %s
				GROUP BY stories.sid
				HAVING Relevance >= " . $this->RELEVANCE . "
				ORDER BY Relevance desc, " . $order_by . " " . $order . " LIMIT $offset, 25",
                $title, $this->filter_cat, $this->filter_clas
            );
            //	print_r($stories);
            //		AND	stories.title like %s
            //SELECT title, M FROM `fanfiction_stories` ORDER BY Relevance DESC
            return $this->resolveValues($stories);
        }

        public function searchByText($title = '', $offset = 0, $order_by = 'updated', $order = 'DESC')
        {
            $RDb = DBService::getDB();
            $stories = $RDb->fetchAll(
                "SELECT max(Relevance) as max_rel, me.* FROM (
				SELECT MATCH(chaps.title,chaps.notes,chaps.storytext,chaps.endnotes) AGAINST ('%s') as Relevance,
				stories.*, penname, UNIX_TIMESTAMP(stories.date) as date_stamp,
				UNIX_TIMESTAMP(stories.updated) as updated_stamp
				FROM (" . TABLE_CHAPTERS . " as chaps, " . TABLE_AUTHORS . " as authors, " . TABLE_STORIES . " as stories)
				LEFT JOIN fanfiction_coauthors as coauth ON coauth.sid = stories.sid
				WHERE authors.uid = stories.uid AND stories.validated > 0 AND chaps.sid = stories.sid 
				%%s %s) AS me
				GROUP BY me.sid 
				HAVING Relevance >= " . $this->RELEVANCE . "
				ORDER BY max_rel desc ,Relevance desc, " . $order_by . " DESC LIMIT $offset, 25
				",
                $title, $this->filter_cat, $this->filter_clas
            );
            return $this->resolveValues($stories);
        }

        public function searchByAll($title = '', $offset = 0, $order_by = 'updated', $order = 'DESC')
        {
            $RDb = DBService::getDB();
            //	$RDb->printQ = TRUE;
            $stories = $RDb->fetchAll(
                "SELECT max(c_rel+s_rel) as Relevance, me.* FROM (
				SELECT MATCH(stories.title,stories.summary,stories.storynotes) AGAINST ('%s') as s_rel,
				MATCH(chaps.title,chaps.notes,chaps.storytext,chaps.endnotes) AGAINST ('%s') as c_rel,
				stories.*, penname, UNIX_TIMESTAMP(stories.date) as date_stamp,
				UNIX_TIMESTAMP(stories.updated) as updated_stamp
				FROM (" . TABLE_CHAPTERS . " as chaps, " . TABLE_AUTHORS . " as authors, " . TABLE_STORIES . " as stories)
				LEFT JOIN fanfiction_coauthors as coauth ON coauth.sid = stories.sid
				WHERE authors.uid = stories.uid AND stories.validated > 0 AND chaps.sid = stories.sid 
				%s %s) AS me
				GROUP BY me.sid
				HAVING Relevance >= " . $this->RELEVANCE . "
				ORDER BY Relevance desc ,s_rel desc,c_rel desc, " . $order_by . " DESC LIMIT $offset, 25
				",
                $title, $title, $this->filter_cat, $this->filter_clas
            );
            //header("X-SQL:" . $RDb->sql);
            return $this->resolveValues($stories);
        }
    }
}


/*
 SELECT max(Relevance) as max_rel, me.* FROM (
 		SELECT MATCH(chaps.storytext) AGAINST ('%s') as Relevance,
 		stories.*, penname, UNIX_TIMESTAMP(stories.date) as date_stamp,
 		UNIX_TIMESTAMP(stories.updated) as updated_stamp
 		FROM (".TABLE_CHAPTERS." as chaps, ".TABLE_AUTHORS." as authors, ".TABLE_STORIES." as stories)
 		LEFT JOIN fanfiction_coauthors as coauth ON coauth.sid = stories.sid
 		WHERE authors.uid = stories.uid AND stories.validated > 0 AND chaps.sid = stories.sid) AS me
GROUP BY me.sid ORDER BY max_rel desc ,Relevance desc, ".$order_by." DESC LIMIT $offset, 50

*/

