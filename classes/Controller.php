<?php
class Controller {

	# look at url and determine route
	public static function check_route() {
		$overallUri = $_SERVER['REQUEST_URI'];
		if (preg_match("#".URI_PREFIX."/([A-Za-z0-9_]+)#", $overallUri, $matches)) {
			$route = $matches[1];
			return $route;
		}
		else {
			return false;
		}
	}

	# $route is the result of check_route()
	public static function do_route($route) {
		# init
		$class = get_called_class();

		# find route
		$method = "action_$route";
		if (!method_exists($class, $method)) {
			$method = 'action_404';
		}

		# do route and give response
		$response = $class::$method();
		die($response);
	}

	public static function action_404() {
		header("HTTP/1.0 404 Not Found");
		return '404 Not Found';
	}

    public static function log($msg) {
        log_msg($msg);
    }

}
