<?php
require_once '../config.php';
/**
 * 
 */
class allSearch
{
    function __doExit_Userbgm($userid, $ctime, $data)
    {
        $data = addslashes($data);
        $sql_index = "update userbgm SET ctime = $ctime, data = '$data' WHERE userid = '$userid'";
        $data = $this->__doDatabaseI($sql_index);

        return $data;
    }

    function __doInsert_Userbgm($userid, $ctime, $data)
    {
        $data = addslashes($data);
        $sql_index = "insert into userbgm (userid,ctime,data) values('$userid',$ctime,'$data')";
        $data = $this->__doDatabaseI($sql_index);

        return $data;
    }

    function __doSearch_Userbgm($userid)
    {
        $sql_index = "select * from userbgm WHERE userid = '$userid'";
        $data = $this->__doDatabaseS($sql_index);

        return $data;
    }

    function __doSearchDate_Begin($begin)
    {
        $sql_index = "select * from bgm WHERE begin >= $begin";
        $data = $this->__doDatabaseS($sql_index);

        return $data;
    }

    function __doSearchDate($begin, $end)
    {
        $sql_index = "select * from bgm WHERE begin between $begin and $end OR end between $begin and $end";
        $data = $this->__doDatabaseS($sql_index);

        return $data;
    }

    function __doSearchAll()
    {
        $sql_index = "select * from bgm";
        $data = $this->__doDatabaseS($sql_index);

        return $data;
    }

    function __doDatabaseS($sql)
    {
        $con = new mysqli($GLOBALS['db_server'], $GLOBALS['db_username'], $GLOBALS['db_password'], $GLOBALS['db_name']); // !!!数据库信息
        if (!$con) {
            die('error');
        } else {
            $con->query("set names utf8");
            $sql_index = $sql;
            $sql_index = $con->query($sql_index);

            $rst = array();
            while ($arr = $sql_index->fetch_assoc()) {
                array_push($rst, $arr);
            }
            $con->close();
        }
        return $rst;
    }

    function __doDatabaseI($sql)
    {
        $con = new mysqli($GLOBALS['db_server'], $GLOBALS['db_username'], $GLOBALS['db_password'], $GLOBALS['db_name']); // !!!数据库信息
        if (!$con) {
            die('error');
        } else {
            $con->query("set names utf8");
            $sql_index = $sql;
            $sql_index = $con->query($sql_index);
            $con->close();
        }
        return $sql_index;
    }
}
?>