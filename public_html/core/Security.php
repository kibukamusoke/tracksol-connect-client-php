<?php
/**
 * IDE: PhpStorm.
 * Created by: Trevor
 * Date: 4/26/18
 * Time: 2:22 PM
 */

class Security{
    protected $__DB;
    protected $bad 	= array('\'','"','\.','script','cookie','document');
    public function __construct($__DB){
        $this->__DB = $__DB;
    }
    protected function MA_IS_INT($val){
        if(preg_match('/'. implode('|',$this->bad) .'/i', $val) or !is_numeric($val)){
            return false;
        }else{
            return true;
        }
    }
    protected function MA_IS_STR($val){
        if(preg_match('/'. implode('|', $this->bad) .'/i', $val)){
            return false;
        }else{
            return true;
        }
    }
    public function MA_INT($val){
        $int = (int)$val;
        return $int;
    }
    public function MA_STR($val){
        $str = str_replace($this->bad, '', $val);
        $str = mysqli_real_escape_string($this->__DB->connect, strip_tags($str));
        return trim($str);
    }
    public function MA_TOPIC_STR($val){
        $str = str_replace(array('<script>','document'),array('&lt;script&gt;','d0cument'),$val);
        $str = mysqli_real_escape_string($this->__DB->connect, $str);
        return trim($str);
    }
    public function MA_check($val, $type = 'STR'){
        switch ($type){
            case 'INT':
                if($this->MA_IS_INT($val)){
                    return $this->MA_INT($val);
                }else{
                    $this->MA_Hacker();
                }
                break;
            case 'STR':
                if($this->MA_IS_STR($val)){
                    return $this->MA_STR($val);
                }else{
                    $this->MA_Hacker();
                }
                break;
        }
    }
    public function MA_Hacker(){
        header('Location: error.php?type=bad_operation');
        exit;
    }
    public function GenerateToken(){
        $token = md5(base64_encode(microtime(true)));
        $_SESSION['token'] = $token;
        return $token;
    }
    public function CheckToken($token){
        if(isset($_SESSION['token']) && $token === $_SESSION['token']) {
            $this->GenerateToken();
            return true;
        }
        return false;
    }
}
?>