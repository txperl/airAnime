<?php

function extraNex_acfun($c)
{
    for ($i = 1; $i < count($c[0]); $i++) {
        $c[1][$i] = 'https://www.acfun.cn/bangumi/aa' . $c[1][$i];
    }

    return $c;
}

function extraNex_bilibili($c)
{
    for ($i = 1; $i < count($c[0]); $i++) {
        $c[0][$i] = str_replace('<em class="keyword">', '', $c[0][$i]);
        $c[0][$i] = str_replace('</em>', '', $c[0][$i]);
        $c[1][$i] = 'https://www.bilibili.com/bangumi/media/md' . $c[1][$i];
    }

    return $c;
}

function extraNex_nicotv($c)
{
    for ($i = 1; $i < count($c[0]); $i++) {
        $c[1][$i] = 'http://www.nicotv.me' . $c[1][$i];
    }

    return $c;
}

function extraNex_qqtv($c)
{
    for ($i = 1; $i < count($c[0]); $i++) {
        $c[1][$i] = 'https://v.qq.com/detail/m/' . $c[1][$i] . '.html';
    }

    return $c;
}

function extraNex_qqmh($c)
{
    for ($i = 1; $i < count($c[0]); $i++) {
        $c[1][$i] = 'https://ac.qq.com/Comic/comicInfo/id/' . $c[1][$i];
    }

    return $c;
}

function extraNex_manhuagui($c)
{
    for ($i = 1; $i < count($c[0]); $i++) {
        $c[1][$i] = 'https://www.manhuagui.com' . $c[1][$i];
    }

    return $c;
}

function extraNex_mangabz($c)
{
    for ($i = 1; $i < count($c[0]); $i++) {
        $c[1][$i] = 'http://www.mangabz.com/' . $c[1][$i];
    }

    return $c;
}

function extraNex_bilibilimh($c)
{
    for ($i = 1; $i < count($c[0]); $i++) {
        $c[1][$i] = 'https://manga.bilibili.com/detail/mc' . $c[1][$i];
    }

    return $c;
}

function extraNex_dmzjmh($c)
{
    for ($i = 1; $i < count($c[0]); $i++) {
        $c[1][$i] = 'https:' . $c[1][$i];
    }

    return $c;
}

function extraNex_mgjh($c)
{
    for ($i = 1; $i < count($c[0]); $i++) {
        $c[1][$i] = 'https://mikanani.me/Home/Bangumi/' . $c[1][$i];
    }

    return $c;
}

function extraNex_qinmei($c)
{
    for ($i = 1; $i < count($c[0]); $i++) {
        $c[1][$i] = 'https://qinmei.video/animate/slug/' . $c[1][$i];
    }

    return $c;
}