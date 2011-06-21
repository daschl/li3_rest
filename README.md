# li3_rest: RESTful support for the Lithium framework

## Introduction
This plugin lets you define on ore more `resources`, which map automatically to their appropriate 
controller actions. The plugin provides a common set of default settings, which should work for 
the common cases (nonetheless it is possible to customize every part of the plugin easily). Read on 
for a hands-on guide on how to install and use the plugin.

## Installation
To install and activate the plugin, you have to perform three easy steps.

1: Download or clone the plugin into your `libraries` directory.

	cd app/libraries
	git clone git://github.com/daschl/li3_rest.git
	

2: Enable the plugin at the bottom of your bootstrap file (`app/config/bootstrap/libraries.php`).

	/**
	 * Add some plugins:
	 */
	Libraries::add('li3_rest');

3: Use the extended `Router` class instead of the default one (at the top of `app/config/routes.php`).

	// use lithium\net\http\Router;
	use li3_rest\net\http\Router;

## Basic Usage
If you want to add a `resource`, you have to call the `Router::resource()` method with one or more params. 
The first param is the name of the `resource` (which has great impact on the routes generated), the second 
one is an array of options that will optionally override the default settings.

If you want to add a `Posts` resource, add the followin to `app/config/routes.php`:

	Router::resource('Posts');

This will generate a bunch of routes. If you want to list them, you can use the `li3 route` command:

	/posts               	{"controller":"posts","action":"index"}
	/posts/{:id:\d+}     	{"controller":"posts","action":"show"}
	/posts/add           	{"controller":"posts","action":"add"}
	/posts               	{"controller":"posts","action":"create"}
	/posts/{:id:\d+}/edit	{"controller":"posts","action":"edit"}
	/posts/{:id:\d+}     	{"controller":"posts","action":"update"}
	/posts/{:id:\d+}     	{"controller":"posts","action":"delete"}

Note: as this plugin is currently in the making, I'll add more documentation as soon as the api and generated 
routes have stableized.

## Contributing
Feel free to fork the plugin and send in pull requests. If you find any bugs or need a feature that is not 
(yet) implemented, open a ticket.