<?php
/**
 * Created by PhpStorm.
 * User: iMCDb
 * Date: 10/18/15
 * Time: 3:16 AM
 */

namespace app\utils {

    class TextUtils
    {
        // Sanitizes user input to help prevent XSS attacks
        public static function descript($text)
        {
            // Convert problematic ascii characters to their true values
            $search = array("40", "41", "58", "65", "66", "67", "68", "69", "70",
                "71", "72", "73", "74", "75", "76", "77", "78", "79", "80", "81",
                "82", "83", "84", "85", "86", "87", "88", "89", "90", "97", "98",
                "99", "100", "101", "102", "103", "104", "105", "106", "107",
                "108", "109", "110", "111", "112", "113", "114", "115", "116",
                "117", "118", "119", "120", "121", "122");

            $replace = array("(", ")", ":", "a", "b", "c", "d", "e", "f", "g", "h",
                "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u",
                "v", "w", "x", "y", "z", "a", "b", "c", "d", "e", "f", "g", "h",
                "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u",
                "v", "w", "x", "y", "z");

            $entities = count($search);

            for ($i = 0; $i < $entities; $i++) $text = preg_replace("#(&\#)(0*" . $search[$i] . "+);*#si", $replace[$i], $text);

            // the following is based on code from bitflux (http://blog.bitflux.ch/wiki/)
            // Kill hexadecimal characters completely
            $text = preg_replace('#(&\#x)([0-9A-F]+);*#si', "", $text);

            // remove any attribute starting with "on" or xmlns

            $text = preg_replace('#(<[^>]+[\\"\'\s])(onmouseover|onmousedown|onmouseup|onmouseout|onmousemove|onclick|ondblclick|onload|xmlns)[^>]*>#iU', ">", $text);

            // remove javascript: and vbscript: protocol

            $text = preg_replace('#([a-z]*)=([\`\'\"]*)script:#iU', '$1=$2nojscript...', $text);
            $text = preg_replace('#([a-z]*)=([\`\'\"]*)javascript:#iU', '$1=$2nojavascript...', $text);
            $text = preg_replace('#([a-z]*)=([\'\"]*)vbscript:#iU', '$1=$2novbscript...', $text);

            //<span style="width: expression(alert('Ping!'));"></span> (only affects ie...)
            $text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU', "$1>", $text);
            $text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU', "$1>", $text);
            return $text;
        }


        // Formats the text of the story when displayed on screen.
        public static function format_story($text)
        {
            $text = trim($text);
            if (strpos($text, "<br>") === false && strpos($text, "<p>") === false && strpos($text, "<br />") === false) $text = nl2br2($text);
            if (_CHARSET != "ISO-8859-1" && _CHARSET != "US-ASCII") return stripslashes($text);
            $badwordchars = array(chr(212), chr(213), chr(210), chr(211), chr(209), chr(208), chr(201), chr(145), chr(146), chr(147), chr(148), chr(151), chr(150), chr(133));
            $fixedwordchars = array('&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8212;', '&#8211;', '&#8230;', '&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8212;', '&#8211;', '&#8230;');
            $text = str_replace($badwordchars, $fixedwordchars, stripslashes($text));
            return $text;
        }
    }

}


