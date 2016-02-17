<?php

function requestVars() {
    return array_merge($_GET, $_POST);
}

function httpMethod() {
    return $_SERVER['REQUEST_METHOD'];
}

function curl_post($url, $vars) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($vars));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    # collect the $result and close the curl handle
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function debug_r($arr) {
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

