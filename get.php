<?php  
  

$title=$_REQUEST['title'];  
$description=$_REQUEST['description'];  
$keywords=$_REQUEST['keywords'];  
$contents=$_REQUEST['contents'];  
$img=$_REQUEST['img'];  


class ComBaike{
    private $o_String=NULL;
    public function __construct(){
        include('cls.StringEx.php');
        $this->o_String=new StringEx();
    }
    public function getItem($word){
        $url = "http://www.baidu.com/s?wd=".$word;
        // 构造包头，模拟浏览器请求
        $header = array (
            "Host:www.baidu.com",
            "Content-Type:application/x-www-form-urlencoded",//post请求
            "Connection: keep-alive",
            'Referer:http://www.baidu.com',
            'User-Agent: Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; BIDUBrowser 2.6)'
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        $content = curl_exec ( $ch );
        if ($content == FALSE) {
        echo "error:" . curl_error ( $ch );
        }
        curl_close ( $ch );
        //输出结果echo $content;
        $this->o_String->string=$content;
        $s_begin='<div id="rs">';
        $s_end='</div>';
        $summary=$this->o_String->getPart($s_begin,$s_end);
        $s_begin='<div class="tt">相关搜索</div><table cellpadding="0"><tr><th>';
        $s_end='</th></tr></table></div>';
        $content=$this->o_String->getPart($s_begin,$s_end);
        return $content;
    }
    public function __destruct(){
        unset($this->o_String);    
    }
}



//模板文件路径  
$mobanpath="./moban.md";  
if(!file_exists($mobanpath)){  
    die("没有模板文件");  
}  
//打开模板文件  
$fp=fopen($mobanpath,'r');  
//读取模板文件  
$str=fread($fp,filesize($mobanpath)); 
 
$str = file_get_contents($mobanpath);  

//$str=htmlspecialchars($str);  
//将接收到的字段,替换模板文件的字段  
$str=str_replace("-title-",$title,$str);  
$str=str_replace("-description-",$description,$str);  
$str=str_replace("-keywords-",$keywords,$str);  
$str=str_replace("-contents-",$contents,$str);  
$str=str_replace("-img-",$img,$str);  
$str=str_replace("-baidu-",$con,$str);  
//当天新闻文件夹  
$foldername=date("Y-m-d");  
//文件夹路径  
$folderpath="./".$foldername;  
//如果没有这个文件夹就创建一个  
if(!file_exists($folderpath)){  
    mkdir($folderpath);  
}  
//生成文件名字  
$filename=$folderpath."-".date("H-i-s").".md";  
//生成文件路径  
$filepath="{$folderpath}/{$filename}";  
//判断是否有此文件  
if(!file_exists($filepath)){  
    //没有的话,创建文件  
    $fp=fopen($filepath,"w");  	
    fwrite($fp,$str);  
    fclose($fp);  
}  
  
//添加到数据库语句  
//  sql..........  
  
header("Location:go.html?msg=success");  
?>  