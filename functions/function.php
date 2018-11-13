<?php
/*以下是取中间文本的函数 
  getSubstr=调用名称
  $str=预取全文本 
  $leftStr=左边文本
  $rightStr=右边文本*/
function getSubstr($str, $leftStr, $rightStr) {
    $left = strpos($str, $leftStr);
    //echo '左边:'.$left;
    $right = strpos($str, $rightStr,$left);
    //echo '<br>右边:'.$right;
    if($left < 0 or $right < $left) return '';
    return substr($str, $left + strlen($leftStr), $right-$left-strlen($leftStr));
}
/*以下是取中间文本的函数(正则)*/
function getNeedBetween($data,$zz) {
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
function RemoveXSS($val) { 
   // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed 
   // this prevents some character re-spacing such as <java\0script> 
   // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs 
   $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val); 

   // straight replacements, the user should never need these since they're normal characters 
   // this prevents like <IMG SRC=@avascript:alert('XSS')> 
   $search = 'abcdefghijklmnopqrstuvwxyz';
   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
   $search .= '1234567890!@#$%^&*()';
   $search .= '~`";:?+/={}[]-_|\'\\';
   for ($i = 0; $i < strlen($search); $i++) {
      // ;? matches the ;, which is optional
      // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

      // @ @ search for the hex values
      $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
      // @ @ 0{0,7} matches '0' zero to seven times 
      $val = preg_replace('/(�{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
   }

   // now the only remaining whitespace attacks are \t, \n, and \r
   $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
   $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
   $ra = array_merge($ra1, $ra2);

   $found = true; // keep replacing as long as the previous round replaced something
   while ($found == true) {
      $val_before = $val;
      for ($i = 0; $i < sizeof($ra); $i++) {
         $pattern = '/';
         for ($j = 0; $j < strlen($ra[$i]); $j++) {
            if ($j > 0) {
               $pattern .= '('; 
               $pattern .= '(&#[xX]0{0,8}([9ab]);)';
               $pattern .= '|'; 
               $pattern .= '|(�{0,8}([9|10|13]);)';
               $pattern .= ')*';
            }
            $pattern .= $ra[$i][$j];
         }
         $pattern .= '/i'; 
         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag 
         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags 
         if ($val_before == $val) { 
            // no replacements were made, so exit the loop 
            $found = false; 
         } 
      } 
   } 
   return $val; 
}
function m_ArrayUnique($arr, $reserveKey = false){
	if (is_array($arr) && !empty($arr)) {
		foreach ($arr as $key => $value) {
			$tmpArr[$key] = serialize($value) . '';
		}
		$tmpArr = array_unique($tmpArr);
		$arr = array();
		foreach ($tmpArr as $key => $value) {
			if ($reserveKey) {
				$arr[$key] = unserialize($value);
			} else {
				$arr[] = unserialize($value);
			}
		}
	}
	return $arr;
}
?>