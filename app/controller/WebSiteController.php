<?php

namespace app\controller {

    use \RudraX\Utils\Webapp;
    use \app\service\DBService;

    class WebSiteController extends AbstractController
    {

        /**
         *
         * @RequestMapping(url="json/websiteinfo",type=json, cache=true)
         * @RequestParams(true)
         */
        public function websiteinfo($info = "NO")
        {

            if ($info == "STATS") {
                return new \app\model\WebSite ();
            } else if ($info == "CATEGORIES") {
                return new \app\model\Categories ();
            } else if ($info == "CLASSES") {
                $RDb = DBService::getDB();
                return $RDb->fetchAll("SELECT class_id id, class_name name, classtype_id, classtype_name,classtype_title
                        FROM `fanfiction_classes` as c,fanfiction_classtypes as ct 
                        WHERE ct.classtype_id=c.class_type");
            }
            return array();
        }

        /**
         * @RequestMapping(url="json/logout",method="GET",type="json")
         * @RequestParams(true)
         */
        public function loagout($model)
        {
            $this->user->unauth();
            return null;
        }

        /**
         * @RequestMapping(url="",method="GET",type="template")
         * @RequestParams(true)
         */
        public function renderTemplate($model)
        {
            //$this->user->validate();
//            if (!$this->user->isValid()) {
//                $this->user->auth(null, null);
//            }
            $model->assign("context_path", Webapp::$BASE_URL);
            $model->assign("cdn_server", \Config::get("CDN_SERVER").Webapp::$BASE_URL . "/");
            return "index";
        }

    }
}

