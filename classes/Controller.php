<?php
class Controller {

    # part of the URI that remains unprocessed after routing
    # used by nested controllers that want to use the remainder
    public static $remainingUri = null;

	# look at url and determine route
	public static function check_route($uri = null, $uriPrefix = URI_PREFIX) {
        if ($uri === null) {
            $uri = $_SERVER['REQUEST_URI'];
        }

		if (preg_match(
                "#^$uriPrefix/([A-Za-z0-9_.]+)(.*)#",
                $uri, $matches)
        ) {
			$route = $matches[1];

            # save remainder for possible further processing
            # by nested sub-controllers
            $ControllerClass = get_called_class();
            $ControllerClass::$remainingUri = $matches[2];

			return $route;
		}
		elseif (preg_match("#$uriPrefix/?#", $uri, $matches)) {
			$route = "index";
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

    public static function process_route($uri=null, $uriPrefix = URI_PREFIX) {
        $ControllerClass = get_called_class();
        $route = $ControllerClass::check_route($uri, $uriPrefix);
        return $ControllerClass::do_route($route);
    }

    public static function process_nested_route() {
        $ControllerClass = get_called_class();
        return $ControllerClass::process_route(
            self::$remainingUri, ''
        );
    }

	public static function action_404() {
		header("HTTP/1.0 404 Not Found");
		return '404 Not Found';
	}

    public static function log($msg) {
        log_msg($msg);
    }

}
