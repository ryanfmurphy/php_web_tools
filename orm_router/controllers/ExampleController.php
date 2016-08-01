<?php
class ExampleController extends Controller {

    public static function action_index() {
        $getVars = $_GET;
        require_once('views/example/index.html.php');
    }

    public static function action_other_view() {
        require_once('views/example/other_view.html.php');
    }



    # example CRUD endpoints
    # (assuming you had a table called `company`
    # and a Class extending Model called Company):

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



    # example CRUD endpoints
    # (assuming you had a table called `inventory`
    # and a Class extending Model called Inventory):

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





    # an example of a nested route
    public static function action_nested() {
        # give the remaining uri (after /nested)
        # to another controller, in this case "ExampleNestedController"
        return ExampleNestedController::process_nested_route();
    }




    # the following functions are for the MetaController
    # an optional but useful mode that enables the controller
    # automatically provide CRUD endpoints for all your database tables
    # without having to manually implement them or copy/paste them

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
            _(?<action>get|get1|view|create|update)
            _(?<table>\w+)
        $/x", $method, $matches);

        if ($matchesActionPattern) {
            $table = $matches['table'];

            #$table = $table_specifier;

            #$tables = DbViewer::sqlTables(); #todo don't depend on DbViewer
            #$table = (isset($tables[$table_specifier])
            #            ? $table_specifier
            #            : DbViewer::depluralize($table_specifier));

            #$tablePlural = DbViewer::pluralize($table); #todo #indepedence

            $ClassName = Model::ClassName($table);
            $vars = requestVars();

            switch ($method) {
                case "action_get1_$table":
                    return json_encode(
                        Model::get1($vars, $ClassName)
                    );
                    break;

                case "action_get_$table":
                    return json_encode(
                        Model::get($vars, $ClassName)
                    );
                    break;

                case "action_view_$table":
                    return json_encode(
                        #Model::view($vars, $ClassName)
                        Db::viewTable($table, $vars)
                    );
                    break;

                case "action_create_$table":
                    return json_encode(
                        Db::insertRow($table, $vars)
                    );
                    break;

                case "action_update_$table":
                    return json_encode(
                        #Model::update($vars, $ClassName)
                        Db::updateRow($table, $vars)
                    );

                #todo
                #case "action_delete_$table":
                #    return json_encode(
                #        Model::delete($vars, $ClassName)
                #    );

                default:
                    return self::action_404();
            }

        }
        else {
            return self::action_404();
        }
    }
}
