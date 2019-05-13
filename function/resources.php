<?php
require_once 'class/search.class.php';
require_once 'class/output.class.php';
require_once 'functions.php';

if ($_POST["kt"]) {
    $keyTitle = RemoveXSS($_POST["kt"]);
} elseif ($_GET["kt"]) {
    $keyTitle = RemoveXSS($_GET["kt"]);
} else {
    die('error');
}

$search = new allSearch();
$output = new allOutput();
$oriData = $search->__doSearchDate_Begin('946656000'); //946656000 2000年往后

$rst = array();

for ($i = 0; $i < count($oriData); $i++) {
    $stitle = json_decode($oriData[$i]['stitle'], true);
    $zhtitle = json_decode($oriData[$i]['zhtitle'], true);

    for ($n = 0; $n < count($stitle); $n++) {
        $cos = howtextsimilar(strtoupper($stitle[$n]), strtoupper($keyTitle));
        if ($cos >= 0.5) {
            array_push($rst, $oriData[$i]);
            break;
        }
    }
}

if (count($rst) >= 10) {
    $rst = ifExistin($rst, $keyTitle);
}

//再次判断
if (count($rst) == 0) {
    $rst = array();

    for ($i = 0; $i < count($oriData); $i++) {
        $stitle = json_decode($oriData[$i]['stitle'], true);
        $zhtitle = json_decode($oriData[$i]['zhtitle'], true);

        for ($n = 0; $n < count($stitle); $n++) {
            similar_text(strtoupper($stitle[$n]), strtoupper($keyTitle), $percent);
            if ($percent >= 45) {
                array_push($rst, $oriData[$i]);
                break;
            }
        }
    }

    if (count($rst) >= 10) {
        $rst = ifExistin($rst, $keyTitle);
    }
}

//输出
if ($_POST["kt"]) {
    $output->__doOutputRes($rst);
} elseif ($_GET["kt"]) {
    $output->__doOutputOri($rst);
} else {
    echo 'error';
}
?>