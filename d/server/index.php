<?php
$timeout = stream_context_create(array(    
   'http' => array(    
       'timeout' => 6 //设置一个超时时间，单位为秒    
       )    
   )    
);
$title=$_SERVER['QUERY_STRING'];
if ($title!='') {
	$web='http://mikanani.me/RSS/Search?searchstr='.$title;
	$data=file_get_contents($web);
	echo $data;
}
?>