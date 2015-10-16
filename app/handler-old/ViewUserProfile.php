<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once(MODEL_PATH . "/Stories.php");
include_once(MODEL_PATH . "/UserDetails.php");

/**
 *
 * @Handler(profile_user)
 *
 * @author lt
 *
 */
class ViewUserProfile extends AbstractHandler
{

    /**
     * @RequestMapping(url="json/user_stories",type=json)
     * @RequestParams(true)
     */
    public function invokeHandler(RequestData $data)
    {
        $uid = $data->get("uid");
        $stories = new Stories ();
        $author = new UserDetails ($uid);
        $author->fetchDetails();
        return $author;
    }
}
