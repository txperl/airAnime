<?php
class animeOnline
{
    private $arr = [
        'local' => [],
        'online' => []
    ]; // 分类
    private $urlsArr = []; // 展开 online 中的 url
    public $urlsData = []; // 整理后的抓取网页的结果
    public $keyTitle = ''; // 搜索关键词
    public $autoTitle = ''; // 联系关键词

    public function __construct($arrs)
    {
        foreach ($arrs as $key => $c) {
            if ($c['type'] == 'getLocal') {
                $this->arr['local'][$key] = $c;
            } elseif ($c['type'] == 'getOnline') {
                $this->arr['online'][$key] = $c;
                $tmp = $c['url'];
                for ($i = 0; $i < count($tmp); $i++) {
                    if (is_array($tmp[$i]) && $tmp[$i][0] == 'POST') {
                        $this->urlsArr[$key . '_' . $i] = ['POST', $tmp[$i][1], $tmp[$i][2]];
                    } else {
                        $this->urlsArr[$key . '_' . $i] = ['GET', is_array($tmp[$i]) ? $tmp[$i][1] : $tmp[$i], ''];
                    }
                }
            }
        }
    }

    // [执行搜索]
    public function doS($keyTitle, $isLocal = true, $urlData = '')
    {
        $rst = [];
        $rst['allNum'] = 0;
        if ($urlData == '') {
            $urlData = $this->urlsData;
        }
        $this->keyTitle = $keyTitle;
        $this->autoTitle = whatstitle($keyTitle); // 标题联想

        // 本地 json 文件搜索
        if ($isLocal) {
            foreach ($this->arr['local'] as $key => $c) {
                $rst[$key] = $this->localSearch($c['uri'], $c['thred'], $keyTitle); // 本地搜索
                $rst['allNum'] += $rst[$key][2]; // 历史遗留...统计总数
            }
        }

        // 在线源搜索
        if (count($urlData)) {
            foreach ($urlData as $key => $c) {
                $way = $this->arr['online'][$key];
                if ($way['fun'][0] == 'json') {
                    // json 方式
                    $tmpData = ($way['fun'][1] != '') ? $way['fun'][1] : $urlData[$key];
                    $rst[$key] = $this->uniJsonMatch($tmpData, $way['fun'][2], $way['fun'][3], $way['fun'][4], $way['fun'][5]);
                    $rst['allNum'] += $rst[$key][2]; // 历史遗留...统计总数
                } elseif ($way['fun'][0] == 'reg') {
                    // 正则方式
                    $tmpData = ($way['fun'][1] != '') ? $way['fun'][1] : $urlData[$key];
                    $rst[$key] = $this->uniRegMatch($tmpData, $way['fun'][2], $way['fun'][3], $way['fun'][4], $way['fun'][5]);
                    $rst['allNum'] += $rst[$key][2]; // 历史遗留...统计总数
                }
            }
        }

        return $rst;
    }

    // [抓取网页并整理分组]
    public function getUrlsData()
    {
        $webData = $this->curl_multi($this->urlsArr); // 抓取网页
        $this->urlsData = $this->modifyUrlsArr($webData); // 重新分组
    }

    //// 本地数据源搜索类
    ///
    // [通用本地 json 搜索]
    // uri: 字符串，json 文件路径
    // stdCos: 一维数组，两次匹配的阈值
    // keytitle: 字符串，欲查找字符
    // 返回: 数组，查询结果
    private function localSearch($uri, $stdCos, $keytitle)
    {
        $file = $uri;
        $bca = file_get_contents($file);
        $bca = json_decode($bca, true);
        $rst = array();
        $rst_t = array("airAnime_title");
        $rst_l = array("airAnime_link");
        $rst_f = array();
        $coss = array();
        $rst_c = array("airAnime_cos");

        // 开始搜索，用余弦函数计算相关度
        for ($i = 0; $i < count($bca); $i++) {
            if ($bca[$i]['title'] == '') {
                continue;
            }
            $cos = howtextsimilar(strtoupper($bca[$i]['title']), strtoupper($keytitle));
            if ($cos >= $stdCos[0]) {
                $cos2 = howtextsimilar(strtoupper($bca[$i]['title']), strtoupper($this->autoTitle));
                array_push($rst, $bca[$i]);
                array_push($coss, $cos2 * 10);
            }
        }

        // 若结果数大于 20，则开始筛选，仅保留完整关键词出现的结果
        if (count($rst) >= 20) {
            $rst = ifExistinOnline($rst, $coss, $keytitle);
            $coss = $rst[1];
            $rst = $rst[0];
        }

        // 如果没了...用 PHP 自带函数计算相关度
        if (count($rst) == 0) {
            $rst = array();
            $coss = array();
            for ($i = 0; $i < count($bca); $i++) {
                if ($bca[$i]['title'] == '') {
                    continue;
                }
                similar_text(strtoupper($bca[$i]['title']), strtoupper($keytitle), $cos);
                if ($cos >= $stdCos[1] * 100) {
                    similar_text(strtoupper($bca[$i]['title']), strtoupper($this->autoTitle), $cos2);
                    array_push($rst, $bca[$i]);
                    array_push($coss, $cos2);
                }
            }
        }

        $number = count($rst);

        for ($i = 0; $i < count($rst); $i++) {
            array_push($rst_t, $rst[$i]['title']);
            array_push($rst_l, $rst[$i]['link']);
            array_push($rst_c, $coss[$i]);
        }

        array_push($rst_f, $rst_t);
        array_push($rst_f, $rst_l);
        array_push($rst_f, $number);

        return $this->sortCoss($rst_f, $rst_c);
    }

    // [简单插排，以实现相似度排序]
    private function sortCoss($c, $coss)
    {
        for ($i = 1; $i < count($coss); $i++) {
            $insertVal = $coss[$i];
            $insertVal2 = $c[0][$i];
            $insertVal3 = $c[1][$i];

            $insertIndex = $i - 1;
            while ($insertIndex >= 1 && $insertVal > $coss[$insertIndex]) {
                $coss[$insertIndex + 1] = $coss[$insertIndex];
                $c[0][$insertIndex + 1] = $c[0][$insertIndex];
                $c[1][$insertIndex + 1] = $c[1][$insertIndex];
                $insertIndex--;
            }
            $coss[$insertIndex + 1] = $insertVal;
            $c[0][$insertIndex + 1] = $insertVal2;
            $c[1][$insertIndex + 1] = $insertVal3;
        }

        return $c;
    }

    //// 在线数据源搜索类
    ///
    // [将抓取的数据重新分组]
    private function modifyUrlsArr($data)
    {
        $tmp = [];

        foreach ($data as $key => $c) {
            $f = explode('_', $key);
            $tmp[$f[0]][(int) $f[1]] = $c;
        }

        return $tmp;
    }

    // [通用 json 匹配]
    // oriData: 数组，网页抓取后的原始数据
    // tIdx: 字符串，title 的查询路径
    // lIdx: 字符串，link 的查询路径
    // funPre: 一维数组，前处理函数名
    // funNex: 一维数组，后处理函数名
    // 返回: 数组，查询结果
    private function uniJsonMatch($oriData, $tIdx, $lIdx, $funPre, $funNex)
    {
        $rstTitle = array("airAnime_title");
        $rstLink = array("airAnime_link");
        $rst = array();

        // 前期数据处理，传入完整抓取数据
        foreach ($funPre as $key => $fun) {
            $tmpFun = 'extraPre_' . $fun;
            $oriData = $tmpFun($oriData);
        }

        for ($n = 0; $n < count($oriData); $n++) {
            $data = $oriData[$n];
            $data = json_decode($data, true);

            $titleUri = $this->deJsonPath(explode('{-}', $tIdx[$n]));
            $linkUri = $this->deJsonPath(explode('{-}', $lIdx[$n]));

            $rstTitle = array_merge_recursive($rstTitle, $this->walkJsonPath($data, $titleUri)); // 取出 titl
            $rstLink = array_merge_recursive($rstLink, $this->walkJsonPath($data, $linkUri)); // 取出 link
        }

        $rst[0] = $rstTitle;
        $rst[1] = $rstLink;
        $rst[2] = count($rstTitle) - 1;

        // 后期数据处理，传入整理后最终结果
        foreach ($funNex as $key => $fun) {
            $tmpFun = 'extraNex_' . $fun;
            $rst = $tmpFun($rst);
        }

        return $rst;
    }

    // [通用正则匹配]
    // oriData: 数组，网页抓取后的原始数据
    // regs: 一维数组，正则表达式，仅可设置两个具体匹配值
    // tIdx: 一维数组，每个表达式 title 的索引，仅可为 0 或 1
    // funPre: 一维数组，前处理函数名
    // funNex: 一维数组，后处理函数名
    // 返回: 数组，查询结果
    private function uniRegMatch($oriData, $regs, $tIdxs, $funPre, $funNex)
    {
        $rstTitle = array("airAnime_title");
        $rstLink = array("airAnime_link");
        $rst = array();

        // 前期数据处理，传入完整抓取数据
        foreach ($funPre as $key => $fun) {
            $tmpFun = 'extraPre_' . $fun;
            $oriData = $tmpFun($oriData);
        }

        for ($n = 0; $n < count($oriData); $n++) {
            $data = $oriData[$n];

            // 正则匹配
            foreach ($regs as $key => $reg) {
                $tIdx = $tIdxs[$key];
                preg_match_all($reg, $data, $tmp);

                for ($i = 0; $i < count($tmp[$tIdx]); $i++) {
                    array_push($rstTitle, $tmp[$tIdx + 1][$i]);
                    array_push($rstLink, $tmp[2 - $tIdx][$i]);
                }
            }
        }

        $rst[0] = $rstTitle;
        $rst[1] = $rstLink;
        $rst[2] = count($rstTitle) - 1;

        // 后期数据处理，传入整理后最终结果
        foreach ($funNex as $key => $fun) {
            $tmpFun = 'extraNex_' . $fun;
            $rst = $tmpFun($rst);
        }

        return $rst;
    }

    private function createCh($url, $postData = '')
    {
        $ch = curl_init();

        if (!empty($postData) && $postData != '') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko'); //设置头部
        curl_setopt($ch, CURLOPT_REFERER, $url); //设置来源
        curl_setopt($ch, CURLOPT_ENCODING, "gzip"); //编码压缩
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //是否采集301、302之后的页面
        // curl_setopt($ch, CURLOPT_MAXREDIRS, 5); //查找次数，防止查找太深
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_TIMEOUT, 6); //设置超时
        curl_setopt($ch, CURLOPT_HEADER, 0); //输出头部
        return $ch;
    }

    // [多线程抓取网页]
    private function curl_multi($urls)
    {
        if (!is_array($urls) or count($urls) == 0) {
            return false;
        }
        $num = count($urls);
        $curl = $curl2 = $text = array();
        $handle = curl_multi_init();
        foreach ($urls as $k => $v) {
            $url = $v[1];
            $curl[$k] = $this->createCh($url, $v[2]);
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

    private function deJsonPath($c)
    {
        $code = [
            '{(0)}' => 0
        ];

        for ($i = 0; $i < count($c); $i++) {
            $c[$i] = isset($code[$c[$i]]) ? $code[$c[$i]] : $c[$i];
        }

        return $c;
    }

    private function walkJsonPath($data, $path)
    {
        $rst = [];
        $wayIdx = $data;

        for ($i = 0; $i < count($path); $i++) {
            if ($path[$i] == '{index}') {
                foreach ($wayIdx as $key => $c) {
                    $tmp = $c;
                    for ($j = $i + 1; $j < count($path); $j++) {
                        if (isset($tmp[$path[$j]])) {
                            $tmp = $tmp[$path[$j]];
                        } else {
                            break;
                        }
                    }
                    if ($j == count($path)) {
                        array_push($rst, $tmp);
                    }
                }
                break;
            } else {
                if (isset($wayIdx[$path[$i]])) {
                    $wayIdx = $wayIdx[$path[$i]];
                } else {
                    break;
                }
            }
        }

        return $rst;
    }
}
