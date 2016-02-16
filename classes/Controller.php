<?php
define('URI_PREFIX', '/api');
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
		$db = $class::connect_db();

		# find route
		$method = "action_$route";
		if (!method_exists($class, $method)) {
			$method = 'action_404';
		}

		# do route and give response
		$response = $class::$method();
		die($response);
	}

	# use WP config to connect to db #todo generalize
	public static function connect_db() {
		#todo make this general, maybe include some "config" file

		# get settings, e.g. db credentials, from WP config
		require_once('or-config.php');

		$db = $GLOBALS['db'] = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		return $db;
	}

	public static function action_404() {
		header("HTTP/1.0 404 Not Found");
		return '404 Not Found';
	}

}
