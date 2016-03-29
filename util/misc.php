<?php

function requestVars() {
    return array_merge($_GET, $_POST);
}

function httpMethod() {
    return $_SERVER['REQUEST_METHOD'];
}

function curl_get($url, $vars=NULL, $username=NULL, $password=NULL, $headers=NULL) {
    return do_curl($url, $vars, null, $username, $password, false, $headers);
}

function curl_post($url, $vars, $username=NULL, $password=NULL, $headers=NULL) {
    return do_curl($url, null, $vars, $username, $password, false, $headers);
}

function curl_put($url, $vars, $username=NULL, $password=NULL, $headers=NULL) {
    return do_curl($url, null, $vars, $username, $password, true, $headers);
}

function do_curl($url, $get_vars=null, $post_vars=NULL, $username=NULL, $password=NULL, $do_PUT_request=false, $headers) {
    $ch = curl_init();

    { # set the options
        # get
        if (is_array($get_vars)) {
            $query_string = http_build_query($get_vars);
            if ($query_string) {
                $url .= "?$query_string";
            }
        }

        # url
        curl_setopt($ch, CURLOPT_URL, $url);

        # post
        if (is_array($post_vars)) {
            if ($do_PUT_request) {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            }
            else {
                curl_setopt($ch, CURLOPT_POST, 1);
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_vars));
        }

        # auth
        if ($username) {
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password"); 
        }

        if ($headers) { # headers
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $fileSizeLimit = 10000000; #todo move this
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_INFILESIZE, $fileSizeLimit);
        }

        # return val
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    }

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


function dollars2cents($numDollars) {
    return $numDollars * 100;
}

function cents2dollars($numDollars) {
    return round($numDollars / 100, 2);
}


function json_error($msg) {
    die(
        json_encode(array(
            'error' => array(
                'message' => $msg
            )
        ))
    );
}

function log_msg($msg) {
    error_log($msg, 3, "log");
}

