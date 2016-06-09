<?php
class MyController extends Controller {

    /*
    public static function action_get_companies() {
        return json_encode(
            Company::get( requestVars() )
        );
    }

    public static function action_get_company() {
        return json_encode(
            Company::get1( requestVars() )
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


    public static function action_get_inventories() {
        return json_encode(
            Inventory::get( requestVars() )
        );
    }

    public static function action_get_inventory() {
        return json_encode(
            Inventory::get1( requestVars() )
        );
    }

    public static function action_create_inventory() {
        return json_encode(
            Inventory::create( requestVars() )
        );
    }

    public static function action_update_inventory() {
        return json_encode(
            Inventory::update( requestVars() )
        );
    }
    */

	# $route is the result of check_route()
	public static function do_route($route) {
		# init
		$class = get_called_class();

		# find route
		$method = "action_$route";

        # don't 404 since it's generic
        # #todo do 404 on wrong table etc
		#if (!method_exists($class, $method)) {
		#	$method = 'action_404';
		#}

		# do route and give response
		$response = $class::$method();
		die($response);
	}

    # generic router
    public static function __callStatic($method, $args) {
        $matchesActionPattern = preg_match("/^
            action
            _(?<action>get|create|update)
            _(?<table_specifier>\w+)
        $/x", $method, $matches);


        if ($matchesActionPattern) {
            $table_specifier = $matches['table_specifier'];
            $table = $table_specifier;

            $tables = DbViewer::sqlTables(); #todo don't depend on DbViewer
            $table = (isset($tables[$table_specifier])
                        ? $table_specifier
                        : DbViewer::depluralize($table_specifier));
            # (s$|$)          #todo do an unpluralize() fn

            $tablePlural = DbViewer::pluralize($table); #todo #indepedence
            $ClassName = Model::ClassName($table);
            $vars = requestVars();

            switch ($method) {
                case "action_get_$table":
                    return json_encode(
                        Model::get1($vars, $ClassName)
                    );
                    break;

                case "action_get_$tablePlural":
                    return json_encode(
                        Model::get($vars, $ClassName)
                    );
                    break;

                case "action_create_$table":
                    return json_encode(
                        Model::create($vars, $ClassName)
                    );
                    break;

                case "action_update_$table":
                    return json_encode(
                        Model::update($vars, $ClassName)
                    );

                default:
                    return self::action_404();
            }

        }
        else {
            return self::action_404();
        }
    }
}
