<?php
class MyController extends Controller {

    public static function action_get_companies() {
        return json_encode(
            Company::get( requestVars() )
        );
    }

    public static function action_create_company() {
        return json_encode(
            Company::create( requestVars() )
        );
    }

    public static function action_update_company() {
        return json_encode(
            Company::update( requestVars() )
        );
    }

}
