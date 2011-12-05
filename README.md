# li3_rest: RESTful support for the Lithium framework

## Introduction
This plugin lets you define one or more `resources`, which map automatically to their appropriate 
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

If you want to add a `Posts` resource, add the following to `app/config/routes.php`:

	Router::resource('Posts');

This will generate a bunch of routes. If you want to list them, you can use the `li3 route` command:

	/posts(.{:type:\w+})*                               	{"controller":"posts","action":"index"}
	/posts/{:id:[0-9a-f]{24}|[0-9]+}(.{:type:\w+})*        	{"controller":"posts","action":"show"}
	/posts/add                          	                {"controller":"posts","action":"add"}
	/posts(.{:type:\w+})*                            	    {"controller":"posts","action":"create"}
	/posts/{:id:[0-9a-f]{24}|[0-9]+}/edit	                {"controller":"posts","action":"edit"}
	/posts/{:id:[0-9a-f]{24}|[0-9]+}(.{:type:\w+})*       	{"controller":"posts","action":"update"}
	/posts/{:id:[0-9a-f]{24}|[0-9]+}(.{:type:\w+})*       	{"controller":"posts","action":"delete"}
 
This routes look complex in the first place, but they try to be as flexible as possible. You can pass 
all default ids (both MongoDB and for relational databases) and always an optional type (like `json`).
With the default resource activated, you can use the following URIs.

	GET /posts or /posts.json => Show a list of available posts
	GET /posts/1234 or /posts/1234.json => Show the post with the ID 1234
	GET /posts/add => Add a new post (maybe a HTML form)
	PUT /posts or /posts.json => Add a new post (has the form data attached)
	GET /posts/1234/edit => Edit the post with the ID 1234 (maybe a HTML form)
	PUT /posts/1234 or /posts/1234.json => Edit the post with the ID 1234 (has the form data attached)
	DELETE /posts/1234 or /posts/1234.json => Deletes the post with the ID 1234

If you wonder why there is no POST http method included, here's the reason: in a classical 
RESTful design, POST is used to create a new sub-resource (and this plugin currently does not 
support sub-resources out of the box). If you use the helpers that come with this plugin, you 
should not notice any difference as they handle the http methods for you. Just keep this in mind 
when you test your web services with CURL.

Note: as this plugin is currently in the making, I'll add more documentation as soon as the api and generated 
routes have stableized.

## Contributing
Feel free to fork the plugin and send in pull requests. If you find any bugs or need a feature that is not 
(yet) implemented, open a ticket.