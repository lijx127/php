</form>
<form action="./test.php" method="post" accept-charset="utf-8" onsubmit="document.charset='utf-8'">
标题:
<textarea rows="4" cols="50"  type="text" name="title" value=""></textarea>
<br>
描述:
<textarea rows="4" cols="50"  type="text" name="description" value=""></textarea>
<br>
关键词:
<textarea rows="4" cols="50"  type="text" name="keywords" value=""></textarea>
<br>
图片:
<textarea rows="4" cols="50"  type="text" name="img" value=""></textarea>
<br>
内容:
<textarea rows="24" cols="150"  type="text" name="contents" value=""></textarea>
<br>

<input type="submit" value="生成" style="display:block; width:80%; height:40px;cursor:pointer; margin:30px; font-size:24px;" />
</form> 
<?php
header('Content-Type:text/html;charset=gbk');
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

if($_POST){

    $com = new ComBaike();
    $q = $_POST['keywords'];
    $str = $com->getItem($q); //获取搜索内容
    $pat = '/<a(.*?)href="(.*?)"(.*?)>(.*?)<\/a>/i';     
    preg_match_all($pat, $str, $m);    
    //print_r($m[4]); 链接文字
    $con = implode(",", $m[4]);
    //生成文件夹
    $dates = date("Ymd");
    $path="./Search/".$dates."/";
    if(!is_dir($path)){
        mkdir($path,0777,true); 
    }
	
	
	$title=$_REQUEST['title'];  
	$description=$_REQUEST['description'];  
	$keywords=$_REQUEST['keywords'];  
	$contents=$_REQUEST['contents'];  
	$img=$_REQUEST['img']; 
	
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
	/*
    //生成文件
    $file = fopen($path.iconv("UTF-8","GBK",$q).".txt",'w');
    if(fwrite($file,$con)){
        echo $con;
        echo '<script>alert("success")</script>';
    }else{
        echo '<script>alert("error")</script>';
    }
    fclose($file);*/

}

?>