<?php

namespace app\controller {

    use \app\model\UserDetails;

    class UserController extends AbstractController
    {


        /**
         * @RequestMapping(url="json/user_details",type=json, cache=true)
         * @RequestParams(true)
         */
        public function userDetails($uid) {
            $author = new UserDetails ( $uid );
            $author->fetchDetails ();
            return $author;
        }

    }
}

