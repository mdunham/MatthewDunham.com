<?php

	/**
	 * MatthewDunham.com Entry Point
	 * 
	 * This is the first file called when the browser loads the site. This file will begin
	 * the process of dispatching the request.
	 * 
	 * @author Matthew Dunham <matt@matthewdunham.com>
	 * @copyright 2012 all rights reserved. 
	 */

	 // Define some basic constants
	define('DS', DIRECTORY_SEPARATOR);
	define('PS', PATH_SEPARATOR);
	
	define('WWW_ROOT', dirname(__FILE__) . DS);
	define('APP', dirname(WWW_ROOT) . DS);

	// Include the basics
	include APP . 'Config/bootstrap.php';
	include APP . 'Utility/Configure.php';
	include APP . 'Utility/Request.php';
	
	// Include the configs
	include APP . 'Config/core.php';
	
	// Include the controller
	include APP . 'Controller/Controller.php';
	
	$controller = new Controller(new Request());
	echo $controller->render();

 