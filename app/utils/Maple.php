<?php
/**
 * Created by PhpStorm.
 * User: iMCDb
 * Date: 10/18/15
 * Time: 3:16 AM
 */

namespace app\utils {

    class Maple
    {
        public static $ALLOWED_TAGS;
        public static $SITE_KEY;

    }

    $SETTINGS = \Config::getSection("SETTINGS");
    Maple::$ALLOWED_TAGS = $SETTINGS["allowed_tags"];
    Maple::$SITE_KEY = $SETTINGS["sitekey"];


}


