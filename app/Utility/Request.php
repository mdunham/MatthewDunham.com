<?php

/**
 * Request Class
 *
 * A class that helps wrap Request information and particulars about a single request.
 * Provides methods commonly used to introspect on the request headers and request body.
 *
 * @author Matthew Dunham <matt@matthewdunham.com>
 */
class Request implements ArrayAccess {

	/**
	 * Array of parameters parsed from the url.
	 *
	 * @var array
	 */
	public $params = array();

	/**
	 * Array of POST data.  Will contain form data as well as uploaded files.
	 *
	 * @var array
	 */
	public $data = array();

	/**
	 * If there was an extension added to the url it will show up here
	 * 
	 * @var string Boolean false if no extension is passed 
	 */
	public $extension = false;
	
	/**
	 * Array of querystring arguments
	 *
	 * @var array
	 */
	public $query = array();

	/**
	 * The url string used for the request.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * Base url path.
	 *
	 * @var string
	 */
	public $base = false;

	/**
	 * webroot path segment for the request.
	 *
	 * @var string
	 */
	public $webroot = '/';

	/**
	 * The full address to the current request
	 *
	 * @var string
	 */
	public $here = null;

	/**
	 * The built in detectors used with `is()` can be modified with `addDetector()`.
	 *
	 * There are several ways to specify a detector, see CakeRequest::addDetector() for the
	 * various formats and ways to define detectors.
	 *
	 * @var array
	 */
	protected $_detectors = array(
		'get' => array('env' => 'REQUEST_METHOD', 'value' => 'GET'),
		'post' => array('env' => 'REQUEST_METHOD', 'value' => 'POST'),
		'put' => array('env' => 'REQUEST_METHOD', 'value' => 'PUT'),
		'delete' => array('env' => 'REQUEST_METHOD', 'value' => 'DELETE'),
		'head' => array('env' => 'REQUEST_METHOD', 'value' => 'HEAD'),
		'options' => array('env' => 'REQUEST_METHOD', 'value' => 'OPTIONS'),
		'ssl' => array('env' => 'HTTPS', 'value' => 1),
		'ajax' => array('env' => 'HTTP_X_REQUESTED_WITH', 'value' => 'XMLHttpRequest'),
		'flash' => array('env' => 'HTTP_USER_AGENT', 'pattern' => '/^(Shockwave|Adobe) Flash/'),
		'mobile' => array('env' => 'HTTP_USER_AGENT', 'options' => array(
				'Android', 'AvantGo', 'BlackBerry', 'DoCoMo', 'Fennec', 'iPod', 'iPhone', 'iPad',
				'J2ME', 'MIDP', 'NetFront', 'Nokia', 'Opera Mini', 'Opera Mobi', 'PalmOS', 'PalmSource',
				'portalmmm', 'Plucker', 'ReqwirelessWeb', 'SonyEricsson', 'Symbian', 'UP\\.Browser',
				'webOS', 'Windows CE', 'Windows Phone OS', 'Xiino'
		)),
		'requested' => array('param' => 'requested', 'value' => 1)
	);

	/**
	 * Copy of php://input.  Since this stream can only be read once in most SAPI's
	 * keep a copy of it so users don't need to know about that detail.
	 *
	 * @var string
	 */
	protected $_input = '';

	/**
	 * Constructor
	 *
	 * @param string $url Trimmed url string to use.  Should not contain the application base path.
	 * @param boolean $parseEnvironment Set to false to not auto parse the environment. ie. GET, POST and FILES.
	 */
	public function __construct($url = null, $parseEnvironment = true) {
		$this->_base();
		
		if (empty($url)) {
			$url = $this->_url();
		}
		if ($url[0] == '/') {
			$url = substr($url, 1);
		}
		$this->url = $url;

		if ($parseEnvironment) {
			$this->_processPost();
			$this->_processGet();
			$this->_processFiles();
		}
		$this->here = $this->base . '/' . $this->url;
		$this->_params();
	}
	
	/**
	 * process all the params related to request such as action and arguments
	 * 
	 * access this via $this->params
	 * 
	 * @return void 
	 */
	protected function _params() {
		$this->params['extension'] = $this->extension;
		$this->params['args'] = array();
		
		if (empty($this->url) || $this->url == '/') {
			$this->params['action'] = 'index';
		} else if (strpos($this->url, '/') !== false) {
			$parts = explode('/', $this->url);
			$this->params['action'] = strtolower(array_shift($parts));
			$this->params['args'] = $parts;
		} else {
			$this->params['action'] = strtolower(trim($this->url));
		}
	}

	/**
	 * process the post data and set what is there into the object.
	 * processed data is available at `$this->data`
	 *
	 * Will merge POST vars prefixed with `data`, and ones without
	 * into a single array. Variables prefixed with `data` will overwrite those without.
	 *
	 * If you have mixed POST values be careful not to make any top level keys numeric
	 * containing arrays. Hash::merge() is used to merge data, and it has possibly
	 * unexpected behavior in this situation.
	 *
	 * @return void
	 */
	protected function _processPost() {
		if ($_POST) {
			$this->data = $_POST;
		} elseif ($this->is('put') || $this->is('delete')) {
			$this->data = $this->_readInput();
			if (env('CONTENT_TYPE') === 'application/x-www-form-urlencoded') {
				parse_str($this->data, $this->data);
			}
		}
		if (ini_get('magic_quotes_gpc') === '1') {
			$this->data = stripslashes_deep($this->data);
		}
		if (env('HTTP_X_HTTP_METHOD_OVERRIDE')) {
			$this->data['_method'] = env('HTTP_X_HTTP_METHOD_OVERRIDE');
		}
		$isArray = is_array($this->data);
		if ($isArray && isset($this->data['_method'])) {
			if ( ! empty($_SERVER)) {
				$_SERVER['REQUEST_METHOD'] = $this->data['_method'];
			} else {
				$_ENV['REQUEST_METHOD'] = $this->data['_method'];
			}
			unset($this->data['_method']);
		}
		if ($isArray) {
			$data = $this->data;
			if (count($this->data) <= 1) {
				$this->data = $data;
			} else {
				unset($this->data['data']);
				$this->data = array_merge_recursive($this->data, $data);
			}
		}
	}

	/**
	 * Process the GET parameters and move things into the object.
	 *
	 * @return void
	 */
	protected function _processGet() {
		if (ini_get('magic_quotes_gpc') === '1') {
			$query = stripslashes_deep($_GET);
		} else {
			$query = $_GET;
		}

		unset($query['/' . str_replace('.', '_', urldecode($this->url))]);
		if (strpos($this->url, '?') !== false) {
			list(, $querystr) = explode('?', $this->url);
			parse_str($querystr, $queryArgs);
			$query += $queryArgs;
		}
		if (isset($this->params['url'])) {
			$query = array_merge_recursive($this->params['url'], $query);
		}
		$this->query = $query;
	}

	/**
	 * Get the request uri.  Looks in PATH_INFO first, as this is the exact value we need prepared
	 * by PHP.  Following that, REQUEST_URI, PHP_SELF, HTTP_X_REWRITE_URL and argv are checked in that order.
	 * Each of these server variables have the base path, and query strings stripped off
	 *
	 * @return string URI The CakePHP request path that is being accessed.
	 */
	protected function _url() {
		if (isset($_SERVER['REQUEST_URI'])) {
			$uri = $_SERVER['REQUEST_URI'];
		} elseif (isset($_SERVER['PHP_SELF']) && isset($_SERVER['SCRIPT_NAME'])) {
			$uri = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['PHP_SELF']);
		} elseif (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
			$uri = $_SERVER['HTTP_X_REWRITE_URL'];
		} elseif ($var = env('argv')) {
			$uri = $var[0];
		}

		$base = $this->base;

		if (strlen($base) > 0 && strpos($uri, $base) === 0) {
			$uri = substr($uri, strlen($base));
		}
		if (strpos($uri, '?') !== false) {
			list($uri) = explode('?', $uri, 2);
		}
		if (empty($uri) || $uri == '/' || $uri == '//') {
			return '/';
		}
		
		$this->extension = pathinfo($uri, PATHINFO_EXTENSION);
		
		if (empty($this->extension)) {
			$this->extension = false;
		} else {
			$uri = rtrim($uri, '.' . $this->extension);
		}
		
		return $uri;
	}

	/**
	 * Returns a base URL and sets the proper webroot
	 *
	 * @return string Base URL
	 */
	protected function _base() {
		$base = explode('webroot', dirname(env('PHP_SELF')));
		
		$base = dirname($base[0]);
		
		if ($base === DS || $base === '.') {
			$base = '';
		}

		$this->webroot = $base . '/';
		
		return $this->base = $base;
	}

	/**
	 * Process $_FILES and move things into the object.
	 *
	 * @return void
	 */
	protected function _processFiles() {
		if (isset($_FILES) && is_array($_FILES)) {
			foreach ($_FILES as $name => $data) {
				if ($name != 'data') {
					$this->params['form'][$name] = $data;
				}
			}
		}
	}

	/**
	 * Read data from php://input, mocked in tests.
	 *
	 * @return string contents of php://input
	 */
	protected function _readInput() {
		if (empty($this->_input)) {
			$fh = fopen('php://input', 'r');
			$content = stream_get_contents($fh);
			fclose($fh);
			$this->_input = $content;
		}
		return $this->_input;
	}

	/**
	 * Check whether or not a Request is a certain type. Any detector can be called
	 * as `is($type)` or `is$Type()`.
	 *
	 * @param string $type The type of request you want to check.
	 * @return boolean Whether or not the request is the type you are checking.
	 */
	public function is($type) {
		$type = strtolower($type);
		if ( ! isset($this->_detectors[$type])) {
			return false;
		}
		$detect = $this->_detectors[$type];
		if (isset($detect['env'])) {
			if (isset($detect['value'])) {
				return env($detect['env']) == $detect['value'];
			}
			if (isset($detect['pattern'])) {
				return (bool) preg_match($detect['pattern'], env($detect['env']));
			}
			if (isset($detect['options'])) {
				$pattern = '/' . implode('|', $detect['options']) . '/i';
				return (bool) preg_match($pattern, env($detect['env']));
			}
		}
		if (isset($detect['param'])) {
			$key = $detect['param'];
			$value = $detect['value'];
			return isset($this->params[$key]) ? $this->params[$key] == $value : false;
		}
		if (isset($detect['callback']) && is_callable($detect['callback'])) {
			return call_user_func($detect['callback'], $this);
		}
		return false;
	}

	/**
	 * Get the IP the client is using, or says they are using.
	 *
	 * @param boolean $safe Use safe = false when you think the user might manipulate their HTTP_CLIENT_IP
	 *   header.  Setting $safe = false will will also look at HTTP_X_FORWARDED_FOR
	 * @return string The client IP.
	 */
	public function clientIp($safe = true) {
		if ( ! $safe && env('HTTP_X_FORWARDED_FOR') != null) {
			$ipaddr = preg_replace('/(?:,.*)/', '', env('HTTP_X_FORWARDED_FOR'));
		} else {
			if (env('HTTP_CLIENT_IP') != null) {
				$ipaddr = env('HTTP_CLIENT_IP');
			} else {
				$ipaddr = env('REMOTE_ADDR');
			}
		}

		if (env('HTTP_CLIENTADDRESS') != null) {
			$tmpipaddr = env('HTTP_CLIENTADDRESS');

			if ( ! empty($tmpipaddr)) {
				$ipaddr = preg_replace('/(?:,.*)/', '', $tmpipaddr);
			}
		}
		return trim($ipaddr);
	}

	/**
	 * offsetExists
	 * 
	 * For ArrayAccess, check if a key exists
	 * 
	 * @param string $offset
	 * @return boolean
	 */
	public function offsetExists($offset) {
		return isset($this->params[$offset]);
	}

	/**
	 * offsetGet
	 * 
	 * @param string $offset
	 * @return string 
	 */
	public function offsetGet($offset) {
		return $this->params[$offset];
	}
	
	/**
	 * offsetSet
	 * 
	 * @param string $offset
	 * @param string $value 
	 */
	public function offsetSet($offset, $value) {
		$this->params[$offset] = $value;
	}

	/**
	 * offsetUnset
	 * 
	 * @param string $offset 
	 */
	public function offsetUnset($offset) {
		unset($this->params[$offset]);
	}

}