<?php

$ControllerClass = null;

foreach (glob('util/*.php') as $util_file) {
    require_once($util_file);
}

foreach (glob('classes/*.php') as $class_file) {
    require_once($class_file);
}

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

foreach (glob('models/*.php') as $model_file) {
    require_once($model_file);
}

