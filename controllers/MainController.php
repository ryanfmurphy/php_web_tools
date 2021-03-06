<?php
class MainController extends Controller {

    # /php_web_tools/db_viewer/*
    public static function action_db_viewer() {
        return DbViewerController::process_nested_route();
    }

    public static function action_index() {
        $getVars = $_GET;
        include('views/example/index.html.php');
    }

    public static function action_other_view() {
        include('views/example/other_view.html.php');
    }



    /*
    # an example of a nested route
    public static function action_nested() {
        # give the remaining uri (after /nested)
        # to another controller, in this case "ExampleNestedController"
        return ExampleNestedController::process_nested_route();
    }
    */




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
