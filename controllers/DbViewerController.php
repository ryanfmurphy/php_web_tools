<?php
class DbViewerController extends Controller {

    public static function action_index() {
        require_once('db_viewer/index.php');
    }

    public static function action_query_id_in() {
        require_once('views/example/query_id_in.php');
    }

    public static function action_rows_with_field_vals() {
        require_once('views/example/rows_with_field_vals.php');
    }

    public static function action_style_DOT_css() {
        require_once('db_viewer/style.css.php');
    }

    /*
    public static function process_route($uri=null, $uriPrefix = URI_PREFIX) {
        $ControllerClass = get_called_class();
        $route = $ControllerClass::check_route($uri, $uriPrefix);
        return $ControllerClass::do_route($route);
    }

	public static function do_route($route) {
		# init
		$class = get_called_class();

        # handle dots/extensions in controller action function names this way
        $route = str_replace('.', '_DOT_', $route);

		# find route
		$method = "action_$route";
		if (!method_exists($class, $method)) {
			$method = 'action_404';
		}

		# do route and give response
		$response = $class::$method();
		die($response);
	}
    */

}
