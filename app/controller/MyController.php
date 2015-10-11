<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*
*/

namespace app\controller {

    require_once RUDRA_CORE . "controller/AbstractController.php";

    use \RudraX\Utils\Webapp;

    class MyController extends AbstractController
    {
        public function defineGlobalVars($file) {
            $GLOBALS_VARS = parse_ini_file ("./app/meta/" . $file, TRUE );
            foreach ( $GLOBALS_VARS as $key => $value ) {
                define ( $key, $value );
            }
        }

    }
}

