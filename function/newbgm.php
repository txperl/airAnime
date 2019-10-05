<?php
ini_set("error_reporting", "E_ALL & ~E_NOTICE");
date_default_timezone_set('Asia/Shanghai');
function getTodays()
{
    $rst = array();
    $frst = array();
    $file = "./data/201904.json"; // !!!季度更变
    $bcon = file_get_contents($file);
    $bcon = json_decode($bcon, true);
    $today = date("N") - 1;
    $bcon = $bcon[$today];
    $num = count($bcon['items']);
    array_push($rst, '#快乐' . $bcon['weekday']['cn']);
    $m = $num % 5;
    if ($m != 0) {
        $mm = round(100 / $m, 6);
    }

    for ($i = 0; $i < $num; $i++) {
        if ($bcon['items'][$i]['name_cn'] == '') {
            $name_cn = $bcon['items'][$i]['name'];
        } else {
            $name_cn = $bcon['items'][$i]['name_cn'];
        }
        $img = $bcon['items'][$i]['images']['large'];
        if (!$img) {
            $img = 'https://i.loli.net/2018/08/09/5b6bff9e96b22.jpg';
        } else {
            $img = str_replace('http://lain.bgm.tv', 'https://lain.bgm.tv', $img);
        }

        $nowc = $num - $i;

        if ($nowc <= $m) {
            $f = '<div class="bgm-item" data-title="' . $bcon['items'][$i]['name'] . '" style="background-color:#D4C7DE;width:' . $mm . '%;" plan="black"><a href="javascript:newBgmS(\'' . $name_cn . '\');"><img src="' . $img . '" class=""><h3 style="background:linear-gradient(rgba(233,227,238,0),rgba(233,227,238,.6),rgba(233,227,238,.8));">' . $name_cn . '</h3><b>' . $bcon['items'][$i]['rating']['score'] . '</b></a></div>';
        } else {
            $f = '<div class="bgm-item" data-title="' . $bcon['items'][$i]['name'] . '" style="background-color:#D4C7DE" plan="black"><a href="javascript:newBgmS(\'' . $name_cn . '\');"><img src="' . $img . '" class=""><h3 style="background:linear-gradient(rgba(233,227,238,0),rgba(233,227,238,.6),rgba(233,227,238,.8));">' . $name_cn . '</h3><b>' . $bcon['items'][$i]['rating']['score'] . '</b></a></div>';
        }

        array_push($frst, $f);
        //<div class="bgm-item" data-title="'.$bcon['items'][$i]['name'].'" style="background-color:#D4C7DE" plan="black"><a href="#"><img src="'.$img.'" class=""><h3 style="background:linear-gradient(rgba(233,227,238,0),rgba(233,227,238,.6),rgba(233,227,238,.8));">'.$name_cn.'</h3><b>'.$bcon['items'][$i]['air_date'].'</b></a></div>
    }
    array_push($rst, $frst);

    return $rst;
}
