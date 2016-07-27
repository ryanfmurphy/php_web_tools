<?php
include('or_config.php');

# util files
foreach (glob('util/*.php') as $util_file) {
    require_once($util_file);
}


# classes
require_once('classes/Db.php');
require_once('classes/Model.php');
require_once('classes/Controller.php');
require_once('classes/DbUtil.php');

if (file_exists('util/custom_includes.php')) {
    require_once('util/custom_includes.php');
}


{ # controllers
    if (!isset($ControllerClass)) {
        $ControllerClass = null;
    }
    foreach (glob('controllers/*.php') as $controller_file) {
        if (!$ControllerClass) {
            $ControllerClass = basename($controller_file, '.php');
        }
        require_once($controller_file);
    }
    if (!$ControllerClass) {
        $ControllerClass = 'ExampleController';
    }
}


# models
foreach (glob('models/*.php') as $model_file) {
    require_once($model_file);
}


# postgres-specific
if (isset($search_path)) {
    DbUtil::setDbSearchPath($search_path);
}
