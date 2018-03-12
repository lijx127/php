<?php
header('Content-Type: text/html; charset=UTF-8');
class StringEx{
    public $string='';
    public function __construct($string=''){
        $this->string=$string;
    }
    public function pregGetPart($s_begin,$s_end){
        $s_begin==preg_quote($s_begin);
        $s_begin=str_replace('/','\/',$s_begin);
        $s_end=preg_quote($s_end);
        $s_end=str_replace('/','\/',$s_end);
        $pattern='/'.$s_begin.'(.*?)'.$s_end.'/';
        $result=preg_match($pattern,$this->string,$a_match);
        if(!$result){
            return $result;
        }else{
            return isset($a_match[1])?$a_match[1]:'';
        }
    }
    public function strstrGetPart($s_begin,$s_end){
        $string=strstr($this->string,$s_begin);
        $string=strstr($string,$s_end,true);
        $string=str_replace($s_begin,'',$string);
        $string=str_replace($s_end,'',$string);
        return $string;
    }
    public function getPart($s_begin,$s_end){
        $result=$this->pregGetPart($s_begin,$s_end);
        if(!$result){
            $result=$this->strstrGetPart($s_begin,$s_end);
        }
        return $result;
    }
}
?>
