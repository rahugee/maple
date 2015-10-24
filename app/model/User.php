<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/


namespace app\model {

    use \app\service\DBService;
    use \app\utils\Maple;

    /**
     * Description of User, it basically extends AbstractUser and implemetns atleast two methods
     *
     * @Model(sessionUser)
     */
    class User extends AbstractUser
    {

        public function getToken()
        {
            return $this->uid;
        }

        public function getName()
        {
            return $this->get('name');
        }
        public function getPenname()
        {
            return $this->get('penname');
        }

        public function auth($username, $passowrd)
        {
            $RDb = DBService::getDB();
            $res = $RDb->fetchAll(
                "SELECT *, auth.uid as uid FROM " . TABLE_AUTHORS . " as auth
				LEFT JOIN fanfiction_authorprefs AS ap ON ap.uid = auth.uid
				WHERE penname = '%s'", $username);

            if (count($res) == 0) {
                return false;
            } else {
                $encryptedpassword = md5($passowrd);
                $row = $res[0];
                if ($row->password != $encryptedpassword) {
                    return false;
                } else {
                    $name = empty($row->realname) ? $row->penname : $row->realname;
                    $this->set('name', $name);
                    $this->set('penname', $row->penname);
                    $this->uname = $row->penname;
                    $this->uid = $row->uid;
                    $this->setValid();

                    if(isset($_POST['cookiecheck'])) {
                        setcookie(Maple::$SITE_KEY."_useruid",$row->uid, time()+60*60*24*30, "/");
                        setcookie(Maple::$SITE_KEY."_salt", md5($row->email+$encryptedpassword),  time()+60*60*24*30, "/");
                    }
                    if(!isset($_SESSION)) session_start( );
                    $_SESSION[Maple::$SITE_KEY."_useruid"] = $row->uid;
                    $_SESSION[Maple::$SITE_KEY."_salt"] = md5($row->email+$encryptedpassword);
                }
                return true;
            }
        }

        public function oldValidation(){
            if (!empty($_COOKIE[Maple::$SITE_KEY."_useruid"])) {
                return true;
            }
            if(!empty($_SESSION[Maple::$SITE_KEY."_useruid"])){
                return true;
            }
        }

        public function validateUser($required=false)
        {
            if (parent::validate() && $this->oldValidation()) {
                //Save and get Othervalues
                return true;
            } else if($required){
                $this->basicAuth();
                header('X-auth-event : true');
            }
            return false;
        }

        public function basicAuth()
        {
            global $_SERVER;
            global $_SESSION;
            // the valid_user checks the user/password (very primitive test in this example)
            if (!$this->auth($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
                session_destroy();
                header("WWW-Authenticate: Basic realm=\"My Realm\"");
                header("HTTP/1.0 401 Unauthorized");
                exit();
            }
            // OK, the user is authenticated
            $_SESSION['PHP_AUTH_USER'] = $_SERVER['PHP_AUTH_USER'];
        }

        public function basicUnAuth()
        {
            global $_SESSION;
            global $HTTP_SERVER_VARS;
            global $PHP_SELF;
            // We mark the session as requiring a re-auth
            $_SESSION['reauth-in-progress'] = 1;
            // This forces the authentication cache clearing
            header("WWW-Authenticate: Basic realm=\"My Realm\"");
            header('HTTP/1.1 401 Unauthorized');
            die('Admin access turned off');
            // In case of the user clicks "cancel" in the dialog box
            print '<a href="http://' . $HTTP_SERVER_VARS['HTTP_HOST'] . $PHP_SELF . '">click me</a>';
            exit();
        }

        public function unauth()
        {
            global $HTTP_SERVER_VARS;
            global $PHP_SELF;
            header('X-auth-event : true');
            if (isset($_SESSION['reauth-in-progress'])) {
                session_destroy();
                //header("Location: http://" . $HTTP_SERVER_VARS['HTTP_HOST'] . $PHP_SELF);
            } else
                self::basicUnAuth();
        }

    }
}
