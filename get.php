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
        // �����ͷ��ģ�����������
        $header = array (
            "Host:www.baidu.com",
            "Content-Type:application/x-www-form-urlencoded",//post����
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
        //������echo $content;
        $this->o_String->string=$content;
        $s_begin='<div id="rs">';
        $s_end='</div>';
        $summary=$this->o_String->getPart($s_begin,$s_end);
        $s_begin='<div class="tt">�������</div><table cellpadding="0"><tr><th>';
        $s_end='</th></tr></table></div>';
        $content=$this->o_String->getPart($s_begin,$s_end);
        return $content;
    }
    public function __destruct(){
        unset($this->o_String);    
    }
}



//ģ���ļ�·��  
$mobanpath="./moban.md";  
if(!file_exists($mobanpath)){  
    die("û��ģ���ļ�");  
}  
//��ģ���ļ�  
$fp=fopen($mobanpath,'r');  
//��ȡģ���ļ�  
$str=fread($fp,filesize($mobanpath)); 
 
$str = file_get_contents($mobanpath);  

//$str=htmlspecialchars($str);  
//�����յ����ֶ�,�滻ģ���ļ����ֶ�  
$str=str_replace("-title-",$title,$str);  
$str=str_replace("-description-",$description,$str);  
$str=str_replace("-keywords-",$keywords,$str);  
$str=str_replace("-contents-",$contents,$str);  
$str=str_replace("-img-",$img,$str);  
$str=str_replace("-baidu-",$con,$str);  
//���������ļ���  
$foldername=date("Y-m-d");  
//�ļ���·��  
$folderpath="./".$foldername;  
//���û������ļ��оʹ���һ��  
if(!file_exists($folderpath)){  
    mkdir($folderpath);  
}  
//�����ļ�����  
$filename=$folderpath."-".date("H-i-s").".md";  
//�����ļ�·��  
$filepath="{$folderpath}/{$filename}";  
//�ж��Ƿ��д��ļ�  
if(!file_exists($filepath)){  
    //û�еĻ�,�����ļ�  
    $fp=fopen($filepath,"w");  	
    fwrite($fp,$str);  
    fclose($fp);  
}  
  
//��ӵ����ݿ����  
//  sql..........  
  
header("Location:go.html?msg=success");  
?>  