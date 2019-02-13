<?php
$type = @$_POST['type'];
$kt = @$_POST['keytitle'];

if ($type && $kt) {
    $api_search = 'http://api.tls.moe/?app=bangumi&key=search&kt=' . $kt . '&type=' . $type;
    $data_s = file_get_contents($api_search);
    $data_s = json_decode($data_s, true);

    $sid = $data_s['list'][0]['id'];

    if ($sid) {
        $api_info = 'http://api.tls.moe/?app=bangumi&key=info&sid=' . $sid . '&type=' . $type;
        $data_i = file_get_contents($api_info);
        $data_i = str_replace('http:\/\/lain.bgm.tv', 'https:\/\/lain.bgm.tv', $data_i);
        print_r($data_i);
    } else {
        print_r('');
    }
}
?>