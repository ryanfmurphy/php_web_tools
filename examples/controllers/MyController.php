<?php
class MyController extends Controller {

    public static function action_company() {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            return Company::create($_POST);
        }
        else {
            $companies = Company::get($_GET);
            return json_encode($companies);
        }
    }

    public static function action_get() {
        return 'this 1 too';
    }

}
