<?php
# Ryan Murphy Feb 2016
# kick off router for /api/* URIs

# gives us $ControllerClass
require_once('util/includes.php');

$route = $ControllerClass::check_route();
if (!$route) $route = $ControllerClass::action_404();

$ControllerClass::do_route($route);

