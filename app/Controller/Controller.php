<?php

/**
 * Controller Class
 * 
 * This is the main controller for the entire application.
 * 
 * @author Matthew Dunham <matt@matthewdunham.com> 
 */

class Controller {
	/**
	 * The request object
	 * 
	 * @var Request 
	 */
	protected $request;
	
	/**
	 * Which view to render
	 * 
	 * @var string 
	 */
	protected $viewFile = 'default';

	/**
	 * List of variabls to be passed to the view
	 * 
	 * @var array 
	 */
	protected $viewVars = array();
	
	/**
	 * construct
	 * 
	 * @param Request $request 
	 */
	public function __construct($request) {
		$this->request = $request;
		
		if ( ! isset($request->params['action']) || empty($request['action']) || ! method_exists($this, $request['action'])) {
			throw new HttpException('Page not found', 404);
		}
		
		call_user_func_array(array($this, $request['action']), $request['args']);
	}
	
	/**
	 * Home Page
	 * 
	 * @return void 
	 */
	public function index() {
		$this->set('title', 'Matthew Dunham, Coffeyville, Kansas - Web Applications Developer');
	}
	
	/**
	 * Return rendered HTML
	 * 
	 * @return string 
	 */
	public function render() {
		$viewFile = APP . 'View' . DS . $this->viewFile . '.php';
		
		if ( ! file_exists($viewFile)) {
			throw new HttpException('Page not found', 404);
		}
			
		extract($this->viewVars);
		ob_start();
		include $viewFile;
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}

	/**
	 * Send data to the view
	 * 
	 * @param mixed $key Can be an associated array from compact() function
	 * @param mixed $value 
	 */
	protected function set($key, $value = null) {
		if (is_array($key)) {
			foreach ($key as $name => $value) {
				$this->viewVars[$name] = $value;
			}
		} else {
			$this->viewVars[$key] = $value;
		}
	}
	
	/**
	 * Return the proper url for a relative resource
	 * 
	 * @param string $url
	 * @return string 
	 */
	public function url($url = null) {
		if ($url == null) {
			return $this->request->webroot;
		}
		
		return $this->request->webroot . ltrim($url, '/');
	}
}