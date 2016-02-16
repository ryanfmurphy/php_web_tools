<?php

function requestVars() {
    return array_merge($_GET, $_POST);
}

function httpMethod() {
    return $_SERVER['REQUEST_METHOD'];
}


# connection to db
function db() {
    $db = $GLOBALS['db'];
    if (!$db) {
        trigger_error('problem connecting to database', E_USER_ERROR);
    }
    else {
        return $db;
    }
}

