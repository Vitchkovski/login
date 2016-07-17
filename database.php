<?php

define('MYSQL_SERVER', 'localhost');
define('MYSQL_USER', 'root');
define('MYSQL_PASSWORD', 'vbnqp98F');
define('MYSQL_DB', 'project');


class DBfunctions
{
    private $connectDB = false;
    private $sqlDataReturn = array();

    public function __construct()
    {
        $this->connectDB = new mysqli(MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
        $this->connectDB->set_charset("utf8");
        $this->connectDB->connect_error;
    }


    public function qrySelect($sql)
    {
        $qry = $this->connectDB->query($sql);

        if ($qry->num_rows > 0) {
            while ($row = $qry->fetch_object()) {
                $this->sqlDataReturn[] = $row;
            }
        } else {
            $this->sqlDataReturn[] = null;
        }
        return $this->sqlDataReturn;
        $this->connectDB->close();
    }

    public function qryFire($sql)
    {
        $this->connectDB->query($sql);
        $this->connectDB->close();
    }

    public function escapeString($string)
    {
        $escapedString = $this->connectDB->real_escape_string($string);
        return $escapedString;
    }

}

?>