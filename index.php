<?php
# Ryan Murphy Feb 2016
# kick off router for /api/* URIs

{ # setup
    # gives us $ControllerClass
    require_once('util/includes.php');
    if (isset($search_path)) {
        DbUtil::setDbSearchPath($search_path);
    }
}

{ # route the URL to the action
    $route = $ControllerClass::check_route();
    $ControllerClass::do_route($route);
}

