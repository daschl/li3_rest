<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_rest\net\http;

use lithium\util\Inflector;
use lithium\util\String;

/**
 * The `Resource` class enables RESTful routing in Lithium.
 * 
 * The `Resource` class acts as a more high-level interface to the `Route` class 
 * and takes care of instantiating the appropriate routes for a given resource.
 * 
 * TODO:
 *  - make :id: better configurable by default (mongo, mysql..)
 * 
 * In your `routes.php` file you can connect a resource in its simplest form like this:
 * 
 * {{{
 * Router::resource('Posts');
 * }}}
 * 
 * This will automatically generate this CRUD routes for you (output similar to `li3 route`):
 * 
 * /posts               	{"controller":"posts","action":"index"}
 * /posts/{:id:\d+}     	{"controller":"posts","action":"show"}
 * /posts/add           	{"controller":"posts","action":"add"}
 * /posts               	{"controller":"posts","action":"create"}
 * /posts/{:id:\d+}/edit	{"controller":"posts","action":"edit"}
 * /posts/{:id:\d+}     	{"controller":"posts","action":"update"}
 * /posts/{:id:\d+}     	{"controller":"posts","action":"delete"}
 * 
 */
class Resource extends \lithium\core\Object {

	/**
	 * Classes used by `Resource`.
	 *
	 * @var array
	 */
	protected static $_classes = array(
		'route' => 'lithium\net\http\Route',
	);

	/**
	 * Default resource types to connect.
	 *
	 * @var array
	 */
	protected static $_types = array(
		'index' => array(
			'template' => '/{:resource}',
			'params' => array(
				'http:method' => 'GET'
			)
		),
		'show' => array(
			'template' => '/{:resource}/{:id:\d+}',
			'params' => array(
				'http:method' => 'GET'
			)
		),
		'add' => array(
			'template' => '/{:resource}/add',
			'params' => array(
				'http:method' => 'GET'
			)
		),
		'create' => array(
			'template' => '/{:resource}',
			'params' => array(
				'http:method' => 'POST'
			)
		),
		'edit' => array(
			'template' => '/{:resource}/{:id:\d+}/edit',
			'params' => array(
				'http:method' => 'GET'
			)
		),
		'update' => array(
			'template' => '/{:resource}/{:id:\d+}',
			'params' => array(
				'http:method' => 'PUT'
			)
		),
		'delete' => array(
			'template' => '/{:resource}/{:id:\d+}',
			'params' => array(
				'http:method' => 'DELETE'
			)
		)
	);

	/**
	 * Configure the class params like classes or types.
	 */
	public static function config($config = array()) {
		if (!$config) {
			return array('classes' => static::$_classes, 'types' => static::$_types);
		}
		if (isset($config['classes'])) {
			static::$_classes = $config['classes'] + static::$_classes;
		}
		if (isset($config['types'])) {
			static::$_types = $config['types'] + static::$_types;
		}	
	}

	/**
	 * Connect a resource to the `Router`.
	 */
	public static function connect($resource, $options = array()) {
		$resource = Inflector::tableize($resource);
		$class = static::$_classes['route'];

		$types = static::$_types;
		if(isset($options['types'])) {
			$types = $options['types'] + $types;
		}

		$routes = array();
		foreach(static::$_types as $action => $params) {
			$config = array(
				'template' => String::insert($params['template'], array('resource' => $resource)),
				'params' => $params['params'] + array('controller' => $resource, 'action' => $action),
			);
			$routes[] = new $class($config);
		}

		return $routes;
	}

}

?>