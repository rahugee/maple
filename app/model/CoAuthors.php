<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\model {

    use \app\service\DBService;

    class CoAuthors
    {
        public static function resolveCoauthors($sid)
        {
            $RDb = DBService::getDB();
            return $RDb->fetchAll("SELECT penname as penname, co.uid AS uid
					FROM " . TABLE_COAUTHORS . " AS co
					LEFT JOIN " . TABLE_AUTHORS . " AS au ON co.uid = au.uid
					WHERE co.sid = %s", $sid);
        }
    }
}
