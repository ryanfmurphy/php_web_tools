<?php
include('or-config.php');

# util
foreach (glob('util/*.php') as $util_file) {
    require_once($util_file);
}


# classes
require_once('classes/Db.php');
require_once('classes/Model.php');
require_once('classes/Controller.php');
if (file_exists('util/custom_includes.php')) {
    require_once('util/custom_includes.php');
}


# controllers
$ControllerClass = null;
foreach (glob('controllers/*.php') as $controller_file) {
    if (!$ControllerClass) {
        #$controller_name = basename($controller_file, '.php');
        #$ControllerClass = snake_case2CapCase($controller_name);
        $ControllerClass = basename($controller_file, '.php');
    }
    require_once($controller_file);
}
if (!$ControllerClass) {
    $ControllerClass = 'Controller';
}


# models
foreach (glob('models/*.php') as $model_file) {
    require_once($model_file);
}

