<?php

class PoprController extends Controller {

    public static function action_popr_DOT_js() {
        require_once('db_viewer/popr/popr.js');
    }

    public static function action_popr_DOT_css() {
        header('Content-Type: text/css');
        require_once('db_viewer/popr/popr.css');
    }

}

?>
