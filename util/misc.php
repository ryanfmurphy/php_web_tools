<?php

function requestVars() {
    return array_merge($_GET, $_POST);
}

function httpMethod() {
    return $_SERVER['REQUEST_METHOD'];
}


class Db {

    public static function connectToDb() {
        $db = $GLOBALS['db'] = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        return $db;
    }

    # connection to db
    public static function conn() {
        $db = ( isset($GLOBALS['db'])
                   ? $GLOBALS['db']
                   : Db::connectToDb() );
        if (!$db) {
            trigger_error('problem connecting to database', E_USER_ERROR);
        }
        else {
            return $db;
        }
    }

    public static function error($msg, $sql) {
        $db = Db::conn();
        trigger_error($msg
                     ." SQL error = ".mysqli_error($db)
                     ." for query '$sql'"
        , E_USER_ERROR);
    }

}

