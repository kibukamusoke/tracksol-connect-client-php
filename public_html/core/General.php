<?php
/**
 * IDE: PhpStorm.
 * Created by: Trevor
 * Date: 4/26/18
 * Time: 2:24 PM
 */

class General
{

    public $__DB, $__SEC;
    private $constants;

    function __construct($__DB, $__SEC, $CONSTANTS)
    {
        $this->__DB = $__DB;
        $this->__SEC = $__SEC;
        $this->constants = $CONSTANTS;
    }

    public function prepareMessage($array)
    {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        if (is_array($array)) {
            echo json_encode($array);
        } else {
            echo $array;
        }
    }

    public function CountRows($table, $where = '')
    {
        if ($where != '') {
            $query = $this->__DB->select($table, '*', $where);
        } else {
            $query = $this->__DB->select($table, '`id`');
        }
        return $this->__DB->num_rows($query);
    }

    public function b64encode($string)
    {
        return urlencode(base64_encode($string));
    }

    public function b64decode($string)
    {
        return urldecode(base64_decode($string));
    }

    public function GetConfig($name, $for)
    {
        $query = $this->__DB->select('config', '`value`', "`name` = '{$name}' AND `for` = '{$for}'");
        $fetch = $this->__DB->fetch_assoc($query);
        return $fetch['value'];
    }

    public function TimeAgo($ptime, $chat = false)
    {
        if ($chat) {
            $t = time() - $ptime;
            $day = 24 * 60 * 60;
            if ($t > $day) {
                return date("H:i d/m/y", $ptime);
            } else {
                return date("H:i", $ptime);
            }
        }
        $etime = time() - $ptime;
        if ($etime < 1) {
            return '0 seconds';
        }
        $a = array(12 * 30 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60 => 'month',
            24 * 60 * 60 => 'day',
            60 * 60 => 'hour',
            60 => 'minute',
            1 => 'second'
        );
        foreach ($a as $secs => $str) {
            $d = $etime / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
            }
        }
    }

    public function SetSession($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function GetSession($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            return false;
        }
    }

    public function UnsetSession($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        } else {
            return false;
        }
    }

    public function randomNumber($length)
    {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }
        return $result;
    }

    public function DisplayError($error, $error_type = 'green')
    {
        /* toastr :
            1 - GREEN
            2 - YELLOW
            3 - RED
            4 - BLUE
        */
        $error_type = strtolower($error_type);
        switch ($error_type) {
            case 'green':
                $error_type = 'success';
                break;
            case 'yellow':
                $error_type = 'warning';
                break;
            case 'red':
                $error_type = 'danger';
                break;
            case 'blue':
                $error_type = 'info';
                break;
        }
        $msg = '<script type="text/javascript">';
        $msg .= 'window.onload = function( ) {';
        $msg .= "DisplayError(" . $error_type . ",'" . $error . "');";
        $msg .= '}</script>';
        return $msg;
    }

    function slack($message)
    {
        $message = array('payload' => json_encode(array('text' => $message)));
        $c = curl_init($this->constants['slack_url']);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $message);
        $result = curl_exec($c);
        curl_close($c);

        return $result;
    }


}