<?php
class allOutput
{
    function __doOutputRes($data)
    {
        $f = '';

        for ($i = 0; $i < count($data); $i++) {
            // <div mdui-menu="{target: '#example-attri'}" class="mdui-chip"><span class="mdui-chip-title">Example Chip</span></div><ul class="mdui-menu" id="example-attri"><li class="mdui-menu-item"><a href="javascript:;" class="mdui-ripple">Refresh</a></li></ul>
            $zhtitle = json_decode($data[$i]['zhtitle'], true);
            $site = json_decode($data[$i]['site'], true);
            $li = '';

            for ($n = 0; $n < count($site); $n++) {
                $code = $site[$n]['id'];
                $c = $site[$n]['site'];
                $li = $li . '<li class="mdui-menu-item"><a target="_blank" href="' . getResUrl($code, $c) . '" class="mdui-ripple">' . getResName($c) . '</a></li>';
            }

            $f = $f . '<div mdui-menu="{target: \'#attr' . $i . '\'}" class="mdui-chip"><span class="mdui-chip-title">' . $zhtitle[0] . '</span></div> <ul class="mdui-menu" id="attr' . $i . '">' . $li . '</ul>';
        }
        echo $f;
    }

    function __doOutputSOnline($data, $site, $url, $keytitle, $icon = '')
    {
        //<div class="mdui-panel-item"><div class="mdui-panel-item-header">哔哩哔哩 (1)</div><div class="mdui-panel-item-body"><a target="_blank" href="https://www.bilibili.com/bangumi/media/md11932" style="color:#555;"><div class="mdui-chip"><span class="mdui-chip-title">戒律的复活</span></div></a> </div></div>
        //<div class="mdui-panel-item-actions"><button class="mdui-btn mdui-ripple" mdui-panel-item-close>cancel</button><button class="mdui-btn mdui-ripple">save</button></div>
        $f = '';
        $li = '';
        $num = $data[2];
        $title = $data[0];
        $link = $data[1];
        if ($icon != '') {
            $icon_url = 'assert/images/icon_' . $icon;
        } else {
            $icon_url = 'http://' . $url . '/favicon.ico';
        }
        for ($i = 0; $i < $num; $i++) {
            $li = $li . '<a target="_blank" href="' . $link[$i + 1] . '" style="color:#555;"><div class="mdui-chip"><span class="mdui-chip-title">' . $title[$i + 1] . '</span></div></a> ';
        }
        $but = '<div class="mdui-panel-item-actions"><a target="_blank" href="http://' . $url . '" class="mdui-btn mdui-ripple">' . $site . '</a><a target="_blank" href="https://www.google.com/search?q=site%3A' . $url . '%20' . urlencode($keytitle) . '" class="mdui-btn mdui-ripple">Google</a><a target="_blank" href="https://bing.com/search?q=site%3A' . $url . '%20' . urlencode($keytitle) . '" class="mdui-btn mdui-ripple">Bing</a><a target="_blank" href="https://www.baidu.com/s?wd=site%3A' . $url . '%20' . urlencode($keytitle) . '" class="mdui-btn mdui-ripple">Baidu</a></div>';

        echo '<div class="mdui-panel-item"><div class="mdui-panel-item-header"><img src="' . $icon_url . '" class="icon-website">' . $site . ' (<b>' . $num . '</b>)</div><div class="mdui-panel-item-body">' . $li . $but . '</div></div>';
    }

    function __doOutputBTS($data, $site, $url, $keytitle, $icon = '')
    {
        //<div class="mdui-panel-item"><div class="mdui-panel-item-header">哔哩哔哩 (1)</div><div class="mdui-panel-item-body"><a target="_blank" href="https://www.bilibili.com/bangumi/media/md11932" style="color:#555;"><div class="mdui-chip"><span class="mdui-chip-title">戒律的复活</span></div></a> </div></div>
        //<div class="mdui-panel-item-actions"><button class="mdui-btn mdui-ripple" mdui-panel-item-close>cancel</button><button class="mdui-btn mdui-ripple">save</button></div>
        $f = '';
        $li = '';
        $num = $data[2];
        $title = $data[0];
        $link = $data[1];
        if ($icon != '') {
            $icon_url = 'assert/images/icon_' . $icon;
        } else {
            $icon_url = 'http://' . $url . '/favicon.ico';
        }
        for ($i = 0; $i < $num; $i++) {
            $li = $li . '<div mdui-menu="{target: \'#bttr' . $i . '\'}" class="mdui-chip"><span class="mdui-chip-title">' . $title[$i + 1] . '</span></div> <ul class="mdui-menu" id="bttr' . $i . '"><li class="mdui-menu-item"><a target="_blank" href="' . $link[$i + 1] . '" class="mdui-ripple">打开</a></li><li class="mdui-menu-item"><a href="javascript:agefansGetPan(\'' . $link[$i + 1] . '\',\'' . $title[$i + 1] . '\');" class="mdui-ripple">百度云</a></li></ul>';
        }
        $but = '<div class="mdui-panel-item-actions"><a target="_blank" href="http://' . $url . '" class="mdui-btn mdui-ripple">' . $site . '</a><a target="_blank" href="https://www.google.com/#q=site%3A' . $url . '%20' . urlencode($keytitle) . '" class="mdui-btn mdui-ripple">Google</a><a target="_blank" href="https://bing.com/search?q=site%3A' . $url . '%20' . urlencode($keytitle) . '" class="mdui-btn mdui-ripple">Bing</a><a target="_blank" href="https://www.baidu.com/s?wd=site%3A' . $url . '%20' . urlencode($keytitle) . '" class="mdui-btn mdui-ripple">Baidu</a></div>';

        echo '<div class="mdui-panel-item"><div class="mdui-panel-item-header"><img src="' . $icon_url . '" class="icon-website">' . $site . ' (<b>' . $num . '</b>)</div><div class="mdui-panel-item-body">' . $li . $but . '</div></div>';
    }

    function __doOutputOri($data)
    {
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        print_r($data);
    }
}
