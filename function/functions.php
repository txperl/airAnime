<?php
ini_set("error_reporting", "E_ALL & ~E_NOTICE");
$DATA_RES = [
    "bangumi" => [
        "title" => "番组计划",
        "urlTemplate" => "https://bangumi.tv/subject/{{id}}",
    ],
    "acfun" => [
        "title" => "AcFun",
        "urlTemplate" => "https://www.acfun.cn/bangumi/aa{{id}}",
    ],
    "bilibili" => [
        "title" => "哔哩哔哩",
        "urlTemplate" => "https://www.bilibili.com/bangumi/media/md{{id}}/",
    ],
    "bilibili_hk_mo_tw" => [
        "title" => "哔哩哔哩（港澳台）",
        "urlTemplate" => "https://www.bilibili.com/bangumi/media/md{{id}}/",
    ],
    "bilibili_hk_mo" => [
        "title" => "哔哩哔哩（港澳）",
        "urlTemplate" => "https://www.bilibili.com/bangumi/media/md{{id}}/",
    ],
    "bilibili_tw" => [
        "title" => "哔哩哔哩（台灣）",
        "urlTemplate" => "https://www.bilibili.com/bangumi/media/md{{id}}/",
    ],
    "sohu" => [
        "title" => "搜狐视频",
        "urlTemplate" => "https://tv.sohu.com/{{id}}",
    ],
    "youku" => [
        "title" => "优酷",
        "urlTemplate" => "https://list.youku.com/show/id_z{{id}}.html",
    ],
    "qq" => [
        "title" => "腾讯视频",
        "urlTemplate" => "https://v.qq.com/detail/{{id}}.html",
    ],
    "iqiyi" => [
        "title" => "爱奇艺",
        "urlTemplate" => "https://www.iqiyi.com/{{id}}.html",
    ],
    "letv" => [
        "title" => "乐视",
        "urlTemplate" => "https://www.le.com/comic/{{id}}.html",
    ],
    "pptv" => [
        "title" => "PPTV",
        "urlTemplate" => "http://v.pptv.com/page/{{id}}.html",
    ],
    "mgtv" => [
        "title" => "芒果tv",
        "urlTemplate" => "https://www.mgtv.com/h/{{id}}.html",
    ],
    "nicovideo" => [
        "title" => "Niconico",
        "urlTemplate" => "https://ch.nicovideo.jp/{{id}}",
    ],
    "netflix" => [
        "title" => "Netflix",
        "urlTemplate" => "https://www.netflix.com/title/{{id}}",
    ],
    "gamer" => [
        "title" => "動畫瘋",
        "urlTemplate" => "https://acg.gamer.com.tw/acgDetail.php?s={{id}}",
    ],
    "muse_hk" => [
        "title" => "木棉花 HK",
        "urlTemplate" => "https://www.youtube.com/playlist?list={{id}}",
    ],
    "ani_one" => [
        "title" => "Ani-One中文官方動畫頻道",
        "urlTemplate" => "https://www.youtube.com/playlist?list={{id}}",
    ],
    "ani_one_asia" => [
        "title" => "Ani-One Asia",
        "urlTemplate" => "https://www.youtube.com/playlist?list={{id}}",
    ],
    "viu" => [
        "title" => "Viu",
        "urlTemplate" => "https://www.viu.com/ott/hk/zh-hk/vod/{{id}}/",
    ],
    "mytv" => [
        "title" => "myTV SUPER",
        "urlTemplate" => "https://www.mytvsuper.com/tc/programme/{{id}}/",
    ],
    "disneyplus" => [
        "title" => "Disney+",
        "urlTemplate" => "https://www.disneyplus.com/series/view/{{id}}",
    ],
    "dmhy" => [
        "title" => "动漫花园",
        "urlTemplate" => "https://share.dmhy.org/topics/list?keyword={{id}}",
    ]
];

//简单余弦函数判断短文本相似度
function howtextsimilar($text1, $text2)
{
    if (strpos($text1, $text2) !== false || strpos($text2, $text1) !== false)
        return 1;

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
    global $DATA_RES;
    return str_replace("{{id}}", $code, $DATA_RES[$c]["urlTemplate"]);
}

function getResName($c)
{
    global $DATA_RES;
    return $DATA_RES[$c]["title"];
}

function apiGetCNName($c)
{
    $rst = [
        'bilibili' => '哔哩哔哩',
        'iqiyi' => '爱奇艺',
        'youku' => '优酷',
        'qqtv' => '腾讯视频',
        'qinmei' => 'Qinmei',
        'nicotv' => '妮可动漫',
        'anime1' => 'Anime1',
        'bimibimi' => 'Bimibimi',
        '8maple' => '枫林网',
        'opacg' => '欧派动漫',
        'acfun' => 'AcFun',
        'yhdm' => '樱花动漫',
        'halitv' => '哈哩哈哩',
        'moetv' => 'MoeTV',
        'c---' => '---',
        'qqmh' => '腾讯漫画',
        'bilibilimh' => '哔哩哔哩漫画',
        'soman' => '搜漫',
        'mangabz' => 'Mangabz',
        'dmzjmh' => '动漫之家',
        'manhuagui' => '漫画柜',
        'bt---' => '---',
        'mgjh' => '蜜柑计划',
        'agefans' => 'AGE动漫&百度云'
    ];

    return isset($rst[$c]) ? $rst[$c] : $c. ' (outdated)';
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
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:107.0) Gecko/20100101 Firefox/107.0'); //设置头部
        curl_setopt($ch, CURLOPT_REFERER, $url); //设置来源
        curl_setopt($ch, CURLOPT_ENCODING, "gzip"); //编码压缩
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //是否采集301、302之后的页面
        //curl_setopt($ch, CURLOPT_MAXREDIRS, 5); //查找次数，防止查找太深
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
    $num = 0;
    $text = '';
    $link = 'https://sp0.baidu.com/5a1Fazu8AA54nxGko9WTAnF6hhy/su?wd=' . urlencode($title) . '&json=1&p=3';
    $web = curl_get_contents($link);
    $web = mb_convert_encoding($web, 'utf-8', 'gbk');
    $web = getSubstr($web, ',"s":', '})');
    $oriData = json_decode($web, true);
    if ($oriData != '') {
        //除杂
        for ($i = 0; $i < count($oriData); $i++) {
            $oriData[$i] = strtolower($oriData[$i]);

            $oriData[$i] = str_replace('百度云', '', $oriData[$i]);
            $oriData[$i] = str_replace('樱花动漫', '', $oriData[$i]);

            for ($j = 0; $j < 3; $j++) {
                $lastni = mb_substr($oriData[$i], mb_strlen($oriData[$i]) - 2, mb_strlen($oriData[$i]), 'utf-8');
                if ($lastni == '无修' || $lastni == '手游' || $lastni == '壁纸' || $lastni == '在线' || $lastni == '观看' || $lastni == 'bd' || $lastni == '小说' || $lastni == '漫画' || $lastni == '动漫' || $lastni == '免费') {
                    $oriData[$i] = mb_substr($oriData[$i], 0, mb_strlen($oriData[$i]) - 2);
                }
            }

            $lastn = mb_substr($oriData[$i], mb_strlen($oriData[$i]) - 3, mb_strlen($oriData[$i]), 'utf-8');
            if ($lastn == '表情包') {
                $oriData[$i] = mb_substr($oriData[$i], 0, mb_strlen($oriData[$i]) - 3);
            }
        }

        $oriData = m_ArrayUnique($oriData);
        $num = count($oriData);
        $rst = $oriData;
        for ($i = 0; $i < count($oriData); $i++) {
            $text = $text . $oriData[$i];
        }
    }

    // 取出最长文本
    $max = 0;
    $turn = 0;
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
    $ftext = '';

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
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:107.0) Gecko/20100101 Firefox/107.0'); //用户访问代理 User-Agent
    //curl_setopt($ch, CURLOPT_REFERER,_REFERER_); //设置 referer
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //跟踪301
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //返回结果
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}

function curl_get_contents_form_post($url, $da)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:107.0) Gecko/20100101 Firefox/107.0');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $da);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
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

function delairAnimeHeader($arr, $lang = '', $isShortCut = false)
{
    if (!is_array($arr)) {
        return $arr;
    }

    if (isset($_GET['lang'])) {
        $lang = $_GET['lang'];
    }

    if (isset($_GET['shortcut'])) {
        $isShortCut = true;
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
        if ($lang == 'cn') {
            $langKey = apiGetCNName($key);
        } else {
            $langKey = $key;
        }
        if ($isShortCut) {
            $langKey = $langKey . ' (' . $c[2] . ')';
        }
        for ($i = 0; $i < $c[2]; $i++) {
            $tem = [];
            $tem['title'] = $arr[$key][0][$i];
            $tem['link'] = $arr[$key][1][$i];
            if ($key == 'dmzj' && substr_count($tem['link'], 'https', 0, 5) == 0) {
                $tem['link'] = 'https:' . $tem['link'];
            }
            $rst[$langKey][$i] = $tem;
        }
        if ($c[2] == 0) {
            if (!$isShortCut) {
                $rst[$langKey] = [];
            }
        }
    }

    return $rst;
}

function resAPIer($arr, $lang = '')
{
    if (count($arr) == 0) {
        return [];
    }

    if (isset($_GET['lang'])) {
        $lang = $_GET['lang'];
    }

    $rst = [];

    for ($i = 0; $i < count($arr); $i++) {
        $titles = json_decode($arr[$i]['zhtitle'], true);
        $sites = json_decode($arr[$i]['site'], true);

        for ($n = 0; $n < count($sites); $n++) {
            $tem = [];
            if ($lang == 'cn') {
                $tem['title'] = getResName($sites[$n]['site']);
            } else {
                $tem['title'] = $sites[$n]['site'];
            }
            $tem['link'] = getResUrl($sites[$n]['id'], $sites[$n]['site']);
            $rst[$titles[0]][$n] = $tem;
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
