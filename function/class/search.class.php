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

        if (isset($this->con)) {
            $this->dbcon = $this->con->prepare("UPDATE userbgm SET ctime = ?, data = ? WHERE userid = ?");
            $this->dbcon->bind_param('dss', $ctime, $data, $userid);

            $data = $this->__doDatabaseI();

            return $data;
        }
    }

    function __doInsert_Userbgm($userid, $ctime, $data)
    {
        $data = addslashes($data);

        if (isset($this->con)) {
            $this->dbcon = $this->con->prepare("INSERT INTO userbgm (userid,ctime,data) VALUES(?, ?, ?)");
            $this->dbcon->bind_param('sds', $userid, $ctime, $data);

            $data = $this->__doDatabaseI();

            return $data;
        }
    }

    function __doSearch_Userbgm($userid)
    {
        if (isset($this->con)) {
            $this->dbcon = $this->con->prepare("SELECT * FROM userbgm WHERE userid = ?");
            $this->dbcon->bind_param('s', $userid);

            $data = $this->__doDatabaseS();

            return $data;
        }
    }

    function __doSearchDate_Begin($begin)
    {
        $this->dbStart();
        if (isset($this->con)) {
            $this->dbcon = $this->con->prepare("SELECT * FROM bgm WHERE begin >= ?");
            $this->dbcon->bind_param('d', $begin);

            $data = $this->__doDatabaseS();
            $this->dbEnd();

            return $data;
        }
    }

    function __doSearchDate($begin, $end)
    {
        if (isset($this->con)) {
            $this->dbcon = $this->con->prepare("SELECT * FROM bgm WHERE begin between ? and ? OR end between ? and ?");
            $this->dbcon->bind_param('dddd', $begin, $end, $begin, $end);

            $data = $this->__doDatabaseS();

            return $data;
        }
    }

    function __doSearchAll()
    {
        if (isset($this->con)) {
            $this->dbcon = $this->con->prepare("SELECT * FROM bgm");

            $data = $this->__doDatabaseS();

            return $data;
        }
    }

    function __doDatabaseS()
    {
        if ($this->con && $this->dbcon) {
            $this->dbcon->execute();

            $rst = array();
            $dbrst = $this->dbcon->get_result();

            while ($arr = $dbrst->fetch_assoc()) {
                array_push($rst, $arr);
            }

            return $rst;
        }
    }

    function __doDatabaseI()
    {
        if ($this->con && $this->dbcon) {
            $this->dbcon->execute();
            if ($this->con->affected_rows) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    function dbStart()
    {
        if (!isset($this->con)) {
            $this->con = new mysqli($GLOBALS['db_server'], $GLOBALS['db_username'], $GLOBALS['db_password'], $GLOBALS['db_name']);
            if ($this->con) {
                $this->con->query('set names utf8');
            }
        }
    }

    function dbEnd()
    {
        if (isset($this->con)) {
            $this->dbcon->close();
            $this->con->close();
        }
    }
}
