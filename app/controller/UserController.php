<?php

namespace app\controller {

    use \app\model\UserDetails;

    class UserController extends AbstractController
    {

        /**
         * @RequestMapping(url="json/logout",method="GET",type="json")
         * @RequestParams(true)
         */
        public function logout()
        {
            $this->user->unauth();
            return null;
        }

        /**
         * @RequestMapping(url="json/login",method="GET",type="json")
         * @RequestParams(true)
         */
        public function login()
        {
            return $this->user->validateUser(true);
        }

        /**
         * @RequestMapping(url="json/user_details",type=json, cache=true)
         * @RequestParams(true)
         */
        public function userDetails($uid)
        {
            $author = new UserDetails ($uid);
            $author->fetchDetails();
            return $author;
        }


        /**
         * @RequestMapping(url="json/mydetails",method="GET",type="json")
         * @RequestParams(true)
         */
        public function getDetails()
        {
            $this->user->validateUser();
            if ($this->user->isValid()) {
                return array("name" => $this->user->uname, "valid" => $this->user->isValid(), "uid" => $this->user->uid,
                    "auth" => $_SERVER['PHP_AUTH_USER']);
            }
            return array("name" => "guest", "valid" => false, "uid" => -1,
                "auth" => "guest");
        }

    }
}

