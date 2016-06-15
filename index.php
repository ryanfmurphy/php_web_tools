<?php
# Ryan Murphy Feb 2016
# kick off router for /api/* URIs

{ #todo make self-sufficient, don't depend on DbViewer
    # we need setDbSearchPath
    #require_once('/Users/murftown/webroot/db_viewer/init.php');
}

{ # setup
    # gives us $ControllerClass
    require_once('util/includes.php');
    #DbViewer::setDbSearchPath($search_path);
}

{ # route the URL to the action
    $route = $ControllerClass::check_route();
    if (!$route) {
        $route = $ControllerClass::action_404();
    }

    $ControllerClass::do_route($route);
}

