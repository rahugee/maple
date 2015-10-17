<?php

namespace app\controller {

    use \app\service\DBService;
    use \app\model\Stories;
    use \app\model\Story;

    class StoriesController extends AbstractController
    {

        /**
         * @RequestMapping(url="json/story_autocomplete",type=json,cache=true)
         * @RequestParams(true)
         */
        public function storyAutoComplete($search = "")
        {
            $response = array();
            $RDb = DBService::getDB();
            $res = $RDb->fetchAll("SELECT * FROM search_cache
				WHERE UPPER(search_text) like UPPER(%s)
				ORDER BY search_text", "'%" . $search . "%'");
            if (count($res) == 0) {
                $res = $RDb->fetchAll("SELECT title as search_text FROM fanfiction_stories
					WHERE UPPER(title) like UPPER(%s)
					ORDER BY title", "'%" . $search . "%'");
            }
            if (count($res) == 0) {
                $response['error'] = true;
                return false;
            } else {
                $response['data'] = $res;
                $response['error'] = false;
                return $response;
            }
        }

        /**
         * @RequestMapping(url="json/storieslist",type=json, cache=true)
         * @RequestParams(true)
         */
        public function searchStories($search = "", $order_by = "updated", $search_by = "title",
                                      $categories = array(), $language = "", $type = "", $page=0)
        {

            $search = trim(preg_replace('/ +/', ' ', $search));
            $offset = $page*50;
            $stories = new Stories ();
            $stories->RELEVANCE = empty($search) ? 0 : 0.1;
            $stories->setCategories($categories);
            $stories->setClasses($type, $language);
            if ($search_by == 'title') {
                $stories_results = $stories->searchByTitle($search, $offset, $order_by);
            } else if ($search_by == 'text') {
                $stories_results = $stories->searchByText($search, $offset, $order_by);
            } else {
                $stories_results = $stories->searchByAll($search, $offset, $order_by);
            }

            if (count($stories_results)) {
                // echo "REPLACE INTO search_cache(search_text) values(".$search.")";
                $RDb = DBService::getDB();
                $res = $RDb->update("REPLACE INTO search_cache(search_text) values('" . $search . "')");
            }
            // print_r($stories->get(0));

            return $stories_results;
        }

        /**
         * @RequestMapping(url="json/view_story",type=json, cache=true)
         * @RequestParams(true)
         */
        public function viewStory($sid)
        {
            return new Story ($sid);
        }

        public static $SIZE = 5;
        public static $GAP = 2; // ($SIZE/2);
        public static $CENTER = 4; // 2+$GAP;

        /**
         * @RequestMapping(url="json/view_comments",type=json)
         * @RequestParams(true)
         */
        public function viewComments($sid = 0, $chapid = NULL, $page=NULL)
        {
            if ($chapid != NULL && is_numeric($chapid)) {
                return \app\model\Reviews::getByChapter($sid, $chapid, $page);
            } else {
                return null;
            }
        }

        /**
         * @RequestMapping(url="json/user_stories",type=json)
         * @RequestParams(true)
         */
        public function storiesByUser($uid = null)
        {
            $stories = new Stories();
            if ($uid != NULL && is_numeric($uid)) {
                return $stories->byUser($uid);
            } else {
                return null;
            }

        }
    }
}

