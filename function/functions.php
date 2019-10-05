<?php
ini_set("error_reporting", "E_ALL & ~E_NOTICE");
//简单余弦函数判断短文本相似度
function howtextsimilar($text1, $text2)
{
    $gentext = $text1 . $text2;
    $fword = '';
    $word = array(); //文本单分割结果
    $num1 = array(); //文本1向量构建
    $num2 = array(); //文本2向量构建

    //取单个词并加入数组
    for ($i = 0; $i < mb_strlen($gentext); $i++) {
        $f = mb_substr($gentext, $i, 1, 'utf-8');
        if (substr_count($fword, $f) == 0) {
            $fword = $fword . $f;
        }
    }
    for ($i = 0; $i < mb_strlen($fword); $i++) {
        $f = mb_substr($fword, $i, 1, 'utf-8');
        array_push($word, $f);
    }

    //判断词频
    for ($i = 0; $i < mb_strlen($fword); $i++) {
        $ntext1 = substr_count($text1, $word[$i]);
        $ntext2 = substr_count($text2, $word[$i]);
        array_push($num1, $ntext1);
        array_push($num2, $ntext2);
    }

    //计算余弦值
    $sum = 0;
    $sumT1 = 0;
    $sumT2 = 0;
    for ($i = 0; $i < mb_strlen($fword); $i++) {
        $sum = $sum + $num1[$i] * $num2[$i];
        $sumT1 = $sumT1 + pow($num1[$i], 2);
        $sumT2 = $sumT2 + pow($num2[$i], 2);
    }
    $cos = $sum / (sqrt($sumT1 * $sumT2));

    return $cos;
}

function getResUrl($code, $c)
{
    $url = '';
    if ($c == 'bangumi') {
        $url = 'http://bangumi.tv/subject/' . $code;
    }
    if ($c == 'saraba1st') {
        $url = 'https://bbs.saraba1st.com/2b/thread-' . $code . '-1-1.html';
    }
    if ($c == 'acfun') {
        $url = 'http://www.acfun.cn/bangumi/aa' . $code;
    }
    if ($c == 'bilibili') {
        $url = 'https://bangumi.bilibili.com/anime/' . $code;
    }
    if ($c == 'tucao') {
        $url = 'http://www.tucao.tv/index.php?m=search&c=index&a=init2&q=' . $code;
    }
    if ($c == 'sohu') {
        $url = 'https://tv.sohu.com/' . $code;
    }
    if ($c == 'youku') {
        $url = 'https://list.youku.com/show/id_z' . $code . '.html';
    }
    if ($c == 'tudou') {
        $url = 'https://www.tudou.com/albumcover/' . $code . '.html';
    }
    if ($c == 'qq') {
        $url = 'https://v.qq.com/detail/' . $code . '.html';
    }
    if ($c == 'iqiyi') {
        $url = 'https://www.iqiyi.com/' . $code . '.html';
    }
    if ($c == 'letv') {
        $url = 'https://www.le.com/comic/' . $code . '.html';
    }
    if ($c == 'pptv') {
        $url = 'http://v.pptv.com/page/' . $code . '.html';
    }
    if ($c == 'kankan') {
        $url = 'http://movie.kankan.com/movie/' . $code;
    }
    if ($c == 'mgtv') {
        $url = 'https://www.mgtv.com/h/' . $code . '.html';
    }
    if ($c == 'nicovideo') {
        $url = 'http://ch.nicovideo.jp/' . $code;
    }
    if ($c == 'netflix') {
        $url = 'https://www.netflix.com/title/' . $code;
    }
    if ($c == 'dmhy') {
        $url = 'https://share.dmhy.org/topics/list?keyword=' . $code;
    }
    if ($c == 'nyaa') {
        $url = 'https://www.nyaa.se/?page=search&term=' . $code;
    }

    return $url;
}

function getResName($c)
{
    $name = '';
    if ($c == 'bangumi') {
        $name = 'Bangumi';
    }
    if ($c == 'saraba1st') {
        $name = 'Saraba1st';
    }
    if ($c == 'acfun') {
        $name = 'AcFun';
    }
    if ($c == 'bilibili') {
        $name = '哔哩哔哩';
    }
    if ($c == 'tucao') {
        $name = 'TUCAO';
    }
    if ($c == 'sohu') {
        $name = '搜狐视频';
    }
    if ($c == 'youku') {
        $name = '优酷';
    }
    if ($c == 'tudou') {
        $name = '土豆';
    }
    if ($c == 'qq') {
        $name = '腾讯视频';
    }
    if ($c == 'iqiyi') {
        $name = '爱奇艺';
    }
    if ($c == 'letv') {
        $name = '乐视';
    }
    if ($c == 'pptv') {
        $name = 'PPTV';
    }
    if ($c == 'kankan') {
        $name = '响巢看看';
    }
    if ($c == 'mgtv') {
        $name = '芒果TV';
    }
    if ($c == 'nicovideo') {
        $name = 'Niconico';
    }
    if ($c == 'netflix') {
        $name = 'Netflix';
    }
    if ($c == 'dmhy') {
        $name = '动漫花园';
    }
    if ($c == 'nyaa') {
        $name = 'nyaa';
    }

    return $name;
}

function ifExistin($data, $c)
{
    $rst = array();

    for ($i = 0; $i < count($data); $i++) {
        $zhtitle = json_decode($data[$i]['zhtitle'], true);
        $zhtitle = $zhtitle[0];

        if (stripos($zhtitle, $c) !== false) {
            array_push($rst, $data[$i]);
        }
    }

    return $rst;
}

function ifExistinOnline($data, $coss, $c)
{
    $rst = array();
    $frst = array();
    $fcoss = array();

    for ($i = 0; $i < count($data); $i++) {
        $zhtitle = $data[$i]['title'];

        if (stripos($zhtitle, $c) !== false) {
            array_push($frst, $data[$i]);
            array_push($fcoss, $coss[$i]);
        }
    }

    $rst[0] = $frst;
    $rst[1] = $fcoss;

    return $rst;
}

//多线程抓取网页
function curl_multi($urls)
{
    if (!is_array($urls) or count($urls) == 0) {
        return false;
    }
    $num = count($urls);
    $curl = $curl2 = $text = array();
    $handle = curl_multi_init();
    function createCh($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko'); //设置头部
        curl_setopt($ch, CURLOPT_REFERER, $url); //设置来源
        curl_setopt($ch, CURLOPT_ENCODING, "gzip"); //编码压缩
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //是否采集301、302之后的页面
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5); //查找次数，防止查找太深
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_TIMEOUT, 6); //设置超时
        curl_setopt($ch, CURLOPT_HEADER, 0); //输出头部
        return $ch;
    }
    foreach ($urls as $k => $v) {
        $url = $urls[$k];
        $curl[$k] = createCh($url);
        curl_multi_add_handle($handle, $curl[$k]);
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

// 推测搜索内容
function whatstitle($title)
{
    // 取百度联想结果
    $rst = array(); //百度联想结果
    $f_rst = array(); //判断中的暂时数据
    $a_rst = array(); //最终数组1
    $b_rst = array(); //最终数组2
    $aexist = '';
    $link = curl_get_contents('https://sp0.baidu.com/5a1Fazu8AA54nxGko9WTAnF6hhy/su?wd=' . urlencode($title) . '&json=1&p=3');
    $link = mb_convert_encoding($link, 'utf-8', 'gbk');
    $link = getSubstr($link, 's":[', ']});');
    $num = substr_count($link, '","') + 1;
    $text = '';
    if ($num != 1) {
        for ($i = 0; $i < $num; $i++) {
            if (getSubstr($link, '"', '",') == '') {
                $f = getSubstr($link, '"', '"');
            } else {
                $f = getSubstr($link, '"', '",');
            }
            $link = str_replace('"' . $f . '"', '', $link);

            // 除杂
            $f = str_replace('第一季', '', $f);
            $f = str_replace('第二季', '', $f);
            $f = str_replace('第三季', '', $f);
            $f = str_replace('第四季', '', $f);
            $f = str_replace('第五季', '', $f);
            $f = str_replace('第六季', '', $f);
            $f = str_replace('第一部', '', $f);
            $f = str_replace('第二部', '', $f);
            $f = str_replace('第三部', '', $f);
            $f = str_replace('第四部', '', $f);
            $f = str_replace('第五部', '', $f);
            $f = str_replace('第六部', '', $f);
            $n = mb_substr($f, mb_strlen($f) - 1, mb_strlen($f), 'utf-8');
            if ($n == '1' or $n == '2' or $n == '3' or $n == '4' or $n == '5' or $n == '6' or $n == '7' or $n == '8' or $n == '9') {
                $f = mb_substr($f, 0, mb_strlen($f) - 1);
            }
            $n = mb_substr($f, mb_strlen($f) - 2, mb_strlen($f), 'utf-8');
            if ($n == '01' or $n == '02' or $n == '03' or $n == '04' or $n == '05' or $n == '06' or $n == '07' or $n == '08' or $n == '09') {
                $f = mb_substr($f, 0, mb_strlen($f) - 2);
            }

            array_push($rst, $f);
            $text = $text . $f;
        }
    }

    // 取出最长文本
    $max = 0;
    for ($i = 0; $i < count($rst); $i++) {
        $tlength = mb_strlen($rst[$i]);
        if ($tlength > $max) {
            $max = $tlength;
            $turn = $i;
        }
    }
    $ltitle = $rst[$turn];

    // 反复判断出现次数并筛选结果
    for ($i = 0; $i < $max; $i++) {
        $iftitle = mb_substr($ltitle, 0, $max - $i, 'utf-8');
        if (substr_count($text, $iftitle) > count($rst) / 2) { // 大于一半就收手,233333.
            $ftitle = $iftitle;
            break;
        }
    }

    // 判断是否存在完全相同文本
    for ($i = 0; $i < count($rst); $i++) {
        if ($title == $rst[$i]) {
            $ftitle = $title;
            $aexist = 1;
            break;
        }
        if ($ftitle == $rst[$i]) {
            $aexist = 1;
            break;
        }
    }

    // 若以上未判断出结果，则继续判断
    // 模拟筛选出出现次数最多的一个可用结果
    if ($aexist != 1) {
        $ftitle = '';
        for ($i = 0; $i < count($rst); $i++) {
            $t = $rst[$i];
            for ($a = 0; $a < mb_strlen($t) - mb_strlen($title); $a++) {
                $f = mb_substr($t, 0, mb_strlen($title) + $a + 1, 'utf-8');
                if (substr_count($text, $f) > 1) {
                    array_push($f_rst, $f);
                    if ($a == mb_strlen($t) - mb_strlen($title) - 1) {
                        if ($f_rst[0] != '') {
                            array_push($a_rst, $f_rst);
                            $f_rst = array();
                        }
                    }
                } else {
                    if ($f_rst[0] != '') {
                        array_push($a_rst, $f_rst);
                        $f_rst = array();
                    }
                    break;
                }
            }
        }
    }

    // 最终结果选择并再次筛选最多出现次数
    if ($ftitle != '') {
        $ftitle = $ftitle;
    } elseif (count($a_rst) == 0) {
        $ftitle = $title;
    } elseif (count($a_rst) == 1) {
        $ftitle = $a_rst[0][count($a_rst[0]) - 1];
    } else {
        for ($i = 0; $i < count($a_rst); $i++) {
            array_push($b_rst, $a_rst[$i][count($a_rst[$i]) - 1]);
            $ftext = $ftext . $a_rst[$i][count($a_rst[$i]) - 1];
        }
        $maxn = 0;
        for ($i = 0; $i < count($b_rst); $i++) {
            $t = $b_rst[$i];
            $num = substr_count($ftext, $t);
            if ($num > $maxn) {
                $ftitle = $t;
            }
        }
    }

    $ftitle = str_replace(' ', '', $ftitle);
    return $ftitle;
}

function curl_get_contents($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); //设置访问的url地址
    //curl_setopt($ch,CURLOPT_HEADER,1); //是否显示头部信息
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); //设置超时
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko'); //用户访问代理 User-Agent
    //curl_setopt($ch, CURLOPT_REFERER,_REFERER_); //设置 referer
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //跟踪301
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //返回结果
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}

function curl_get_contents_form_post($url, $da)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $da);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    //curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}

/*以下是取中间文本的函数 
  getSubstr=调用名称
  $str=预取全文本 
  $leftStr=左边文本
  $rightStr=右边文本*/
function getSubstr($str, $leftStr, $rightStr)
{
    $left = strpos($str, $leftStr);
    //echo '左边:'.$left;
    $right = strpos($str, $rightStr, $left);
    //echo '<br>右边:'.$right;
    if ($left < 0 or $right < $left) return '';
    return substr($str, $left + strlen($leftStr), $right - $left - strlen($leftStr));
}

/*以下是取中间文本的函数(正则)*/
function getNeedBetween($data, $zz)
{
    $str = $data;
    preg_match($zz, $str, $a);
    $b = $a[1];
    return $b;
}

function RemoveXSS($val)
{
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
        $val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val); // with a ;
        // @ @ 0{0,7} matches '0' zero to seven times 
        $val = preg_replace('/(�{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val); // with a ;
    }

    // now the only remaining whitespace attacks are \t, \n, and \r
    $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
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
            $replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }

    return $val;
}

function delairAnimeHeader($arr)
{
    if (!is_array($arr)) {
        return $arr;
    }

    $arr_key = array_keys($arr);

    for ($i = 0; $i < count($arr_key); $i++) {
        $key = $arr_key[$i];
        array_splice($arr[$key][0], 0, 1);
        array_splice($arr[$key][1], 0, 1);
    }

    $rst = array();

    foreach ($arr as $key => $c) {
        if ($key == 'allNum') {
            continue;
        }
        for ($i = 0; $i < $c[2]; $i++) {
            $tem = [];
            $tem['title'] = $arr[$key][0][$i];
            $tem['link'] = $arr[$key][1][$i];
            $rst[$key][$i] = $tem;
        }
        if ($c[2] == 0) {
            $rst[$key] = [];
        }
    }

    return $rst;
}

function m_ArrayUnique($arr, $reserveKey = false)
{
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
