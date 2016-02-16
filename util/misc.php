<?php

function requestVars() {
    return array_merge($_GET, $_POST);
}

function httpMethod() {
    return $_SERVER['REQUEST_METHOD'];
}

