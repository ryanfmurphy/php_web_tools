<?php
class MyController extends Controller {

    public static function action_get_companies() {
        $companies = Company::get( requestVars() );
        return json_encode($companies);
    }

    public static function action_create_company() {
        return Company::create( requestVars() );
    }

    public static function action_update_company() {
        return Company::update( requestVars() );
    }

}
