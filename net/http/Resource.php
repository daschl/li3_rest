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
 * 
 * In your `routes.php` file you can connect a resource in its simplest form like this:
 * 
 * {{{
 * Router::resource('Posts');
 * }}}
 * 
 * This will automatically generate this CRUD routes for you (output similar to `li3 route`):
 * 
 * {{{
 * /posts(.{:type:\w+})*                               	    {"controller":"posts","action":"index"}
 * /posts/{:id:[0-9a-f]{24}|[0-9]+}(.{:type:\w+})*        	{"controller":"posts","action":"show"}
 * /posts/add                          	                    {"controller":"posts","action":"add"}
 * /posts(.{:type:\w+})*                            	    {"controller":"posts","action":"create"}
 * /posts/{:id:[0-9a-f]{24}|[0-9]+}/edit	                {"controller":"posts","action":"edit"}
 * /posts/{:id:[0-9a-f]{24}|[0-9]+}(.{:type:\w+})*       	{"controller":"posts","action":"update"}
 * /posts/{:id:[0-9a-f]{24}|[0-9]+}(.{:type:\w+})*       	{"controller":"posts","action":"delete"}
 * }}}
 * 
 * This routes look complex in the first place, but they try to be as flexible as possible. You can pass 
 * all default ids (both MongoDB and for relational databases) and always an optional type (like `json`).
 * With the default resource activated, you can use the following URIs.
 * 
 * {{{
 * GET /posts or /posts.json => Show a list of available posts
 * GET /posts/1234 or /posts/1234.json => Show the post with the ID 1234
 * GET /posts/add => Add a new post (maybe a HTML form)
 * PUT /posts or /posts.json => Add a new post (has the form data attached)
 * GET /posts/1234/edit => Edit the post with the ID 1234 (maybe a HTML form)
 * PUT /posts/1234 or /posts/1234.json => Edit the post with the ID 1234 (has the form data attached)
 * DELETE /posts/1234 or /posts/1234.json => Deletes the post with the ID 1234
 * }}}
 * 
 * If you wonder why there is no POST http method included, here's the reason: in a classical 
 * RESTful design, POST is used to create a new sub-resource (and this plugin currently does not 
 * support sub-resources out of the box). If you use the helpers that come with this plugin, you 
 * should not notice any difference as they handle the http methods for you. Just keep this in mind 
 * when you test your web services with CURL.
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
			'template' => '/{:resource}(.{:type:\w+})*',
			'params' => array('http:method' => 'GET')
		),
		'show' => array(
			'template' => '/{:resource}/{:id:[0-9a-f]{24}|[0-9]+}(.{:type:\w+})*',
			'params' => array('http:method' => 'GET')
		),
		'add' => array(
			'template' => '/{:resource}/add',
			'params' => array('http:method' => 'GET')
		),
		'create' => array(
			'template' => '/{:resource}(.{:type:\w+})*',
			'params' => array('http:method' => 'PUT')
		),
		'edit' => array(
			'template' => '/{:resource}/{:id:[0-9a-f]{24}|[0-9]+}/edit',
			'params' => array('http:method' => 'GET')
		),
		'update' => array(
			'template' => '/{:resource}/{:id:[0-9a-f]{24}|[0-9]+}(.{:type:\w+})*',
			'params' => array('http:method' => 'PUT')
		),
		'delete' => array(
			'template' => '/{:resource}/{:id:[0-9a-f]{24}|[0-9]+}(.{:type:\w+})*',
			'params' => array('http:method' => 'DELETE')
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