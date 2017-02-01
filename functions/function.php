<?php
/*以下是取中间文本的函数 
  getSubstr=调用名称
  $str=预取全文本 
  $leftStr=左边文本
  $rightStr=右边文本
*/
function getSubstr($str, $leftStr, $rightStr)
{
    $left = strpos($str, $leftStr);
    //echo '左边:'.$left;
    $right = strpos($str, $rightStr,$left);
    //echo '<br>右边:'.$right;
    if($left < 0 or $right < $left) return '';
    return substr($str, $left + strlen($leftStr), $right-$left-strlen($leftStr));
}
/*以下是取中间文本的函数(正则) 
*/
function getNeedBetween($data,$zz){
    $str = $data;
    preg_match($zz, $str, $a);
    $b = $a[1];
    return $b;
}
function getHTTPS($url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_REFERER, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  $result = curl_exec($ch);
  curl_close($ch);
  return $result;
}
function curl_get_contents($url) {   
    $ch = curl_init();   
    curl_setopt($ch, CURLOPT_URL, $url);            //设置访问的url地址   
    //curl_setopt($ch,CURLOPT_HEADER,1);            //是否显示头部信息   
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);           //设置超时   
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko');   //用户访问代理 User-Agent   
    //curl_setopt($ch, CURLOPT_REFERER,_REFERER_);        //设置 referer   
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);      //跟踪301   
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        //返回结果   
    $r = curl_exec($ch);   
    curl_close($ch);   
    return $r;   
}
//多线程抓取网页
function curl_multi($urls) {  
    if (!is_array($urls) or count($urls) == 0) {  
        return false;  
    }   
    $num=count($urls);  
    $curl = $curl2 = $text = array();  
    $handle = curl_multi_init();  
    function createCh($url) {  
        $ch = curl_init();  
        curl_setopt ($ch, CURLOPT_URL, $url);  
        curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko');//设置头部  
        curl_setopt ($ch, CURLOPT_REFERER, $url); //设置来源  
        curl_setopt ($ch, CURLOPT_ENCODING, "gzip"); // 编码压缩  
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);  
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);//是否采集301、302之后的页面  
        curl_setopt ($ch, CURLOPT_MAXREDIRS, 5);//查找次数，防止查找太深  
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查  
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在         
        curl_setopt ($ch, CURLOPT_TIMEOUT, 20);  
        curl_setopt ($ch, CURLOPT_HEADER, 0);//输出头部  
        return $ch;  
    }  
    foreach($urls as $k=>$v){  
        $url=$urls[$k];  
        $curl[$k] = createCh($url);  
        curl_multi_add_handle ($handle,$curl[$k]);  
    }  
    $active = null;  
    do {  
        $mrc = curl_multi_exec($handle, $active);  
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);  
  
    while ($active && $mrc == CURLM_OK) {  
        if (curl_multi_select($handle) != -1) {  
            usleep(100);  
        }  
        do {  
            $mrc = curl_multi_exec($handle, $active);  
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);  
    }   
  
    foreach ($curl as $k => $v) {  
        if (curl_error($curl[$k]) == "") {  
            $text[$k] = (string) curl_multi_getcontent($curl[$k]);   
        }  
        curl_multi_remove_handle($handle, $curl[$k]);  
        curl_close($curl[$k]);  
    }   
    curl_multi_close($handle);  
    return $text;  
}
// 转换编码，将Unicode编码转换成可以浏览的utf-8编码
function unicode_decode($name){
  $json = '{"str":"'.$name.'"}';
  $arr = json_decode($json,true);
  if(empty($arr)) return '';
  return $arr['str'];
}
?>