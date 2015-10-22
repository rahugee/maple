<?php

namespace app\controller {

    use \app\model\CharDetails;
    use \app\model\Stories;

    class ActorController extends AbstractController
    {


        /**
         * @RequestMapping(url="json/actor_details",type=json, cache=true)
         * @RequestParams(true)
         */
        public function actorDetails($aid) {
            $author = new CharDetails ( $aid );
            $author->fetchDetails ();
            return $author;
        }


        /**
         * @RequestMapping(url="json/actor_search",type=json, cache=true)
         * @RequestParams(true)
         */
        public function actorSearch($search) {
            return CharDetails::getChars($search);
        }

        /**
         * @RequestMapping(url="json/actor_stories",type=json)
         * @RequestParams(true)
         */
        public function storiesByActor($aid = null)
        {
            $stories = new Stories();
            if ($aid != NULL && is_numeric($aid)) {
                return $stories->byActor($aid);
            } else {
                return null;
            }

        }
    }
}

