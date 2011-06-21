<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_rest\net\http;

class Router extends \lithium\net\http\Router {

	public static function resource($resource, $options = array()) {
		$class = static::$_classes['resource'];

		$routes = $class::connect($resource, $options);
		foreach($routes as $route) {
			static::$_configurations[] = $route;
		}
	}

}

?>