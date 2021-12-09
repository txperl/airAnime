<?php
require_once 'class/search.class.php';
if (@$_COOKIE["user_id"] && @$_COOKIE["user_ctime"]) {
    $userid = $_COOKIE["user_id"];
    $ctime = $_COOKIE["user_ctime"];
    $ntime = time();
    $type = 'normal';

    if (@$_POST["type"]) {
        if ($_POST["type"] == 'refresh' || $_POST["type"] == 'normal') {
            $type = $_POST["type"];
        } elseif ($_POST["type"] == 'none') {
            $type = 'normal';
        } else {
            die("error");
        }
    }

    if (($ntime - $ctime) >= 86400) {
        $type = 'refresh';
    }

    $search = new allSearch();
    $search->dbStart();

    if ($type == 'refresh') {
        $data = file_get_contents('https://api.tls.moe/?app=bangumi&key=watching&name=' . $userid);
        setcookie("user_id", $userid, time() + 24 * 3600 * 7, "/");
        setcookie("user_ctime", $ntime, time() + 24 * 3600 * 7, "/");
        $oriData = $search->__doExit_Userbgm($userid, $ntime, $data);
        $oriData = $search->__doSearch_Userbgm($userid);
        $oriData[0]['data'] = json_decode(stripcslashes($oriData[0]['data']), true);
        $oriData = json_encode($oriData, JSON_UNESCAPED_UNICODE);
        $oriData = str_replace('http:\/\/', 'https:\/\/', $oriData);
        print_r($oriData);
    }

    if ($type == 'normal') {
        $oriData = $search->__doSearch_Userbgm($userid);
        if (count($oriData) == 0 || !$oriData[0]['data']) {
            $data = file_get_contents('https://api.tls.moe/?app=bangumi&key=watching&name=' . $userid);
            setcookie("user_id", $userid, time() + 24 * 3600 * 7, "/");
            setcookie("user_ctime", $ntime, time() + 24 * 3600 * 7, "/");
            $oriData = $search->__doExit_Userbgm($userid, $ntime, $data);
            $oriData = $search->__doSearch_Userbgm($userid);
        }
        $oriData[0]['data'] = json_decode(stripcslashes($oriData[0]['data']), true);
        $oriData = json_encode($oriData, JSON_UNESCAPED_UNICODE);
        $oriData = str_replace('http:\/\/', 'https:\/\/', $oriData);
        print_r($oriData);
    }
    $search->dbEnd();
} else {
    die("error");
}
