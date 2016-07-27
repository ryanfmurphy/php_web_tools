<?php
# Ryan Murphy Feb 2016
# kick off router for /api/* URIs

require_once('init.php');

# route the URL to the appropriate action
$ControllerClass::process_route();

