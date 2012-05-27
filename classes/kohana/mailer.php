<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Mailer {
	
	public $layout = 'blank';
	
	public $template = '';

	public $formats = array('text');
	
	/**
	 * A running log of all email transactions sent during a request.
	 */
	public static $log = array();
	
	/**
	 * Mailer configuration values from config/mailer.php.
	 */
	protected $config;
	
	/**
	 * Used to store the parameters passed into factory() so that
	 * they are available when build() is called, and then also
	 * passed into the view templates using the same name as given
	 * to them in the mailer's build() function definition.
	 */
	protected $params = array();
	
	/**
	 * The driver to send emails with.
	 */
	protected $driver;
	
	/**
	 * Initialize a mailer class.
	 */
	public function __construct() {
		$this->config = Kohana::$config->load('mailer');
		$driver_class = 'Mailer_Driver_'. ucwords($this->config->driver);
		$this->driver = new $driver_class;
	}
	
	/**
	 * Allow emails to be built and delivered in our app using a single line of code.
	 *
	 * @param string $name The name of the mailer to initialize (minus the starting "Mailer_"). Name adheres to Kohana class loader scheme.
	 *
	 * @return Mailer a loaded mailer object with which to interact.
	 */
	public static function factory($name) {
		$mailer_class = 'Mailer_'. ucwords($name);
		$mailer_class = new $mailer_class();
		$mailer_class->folder = 'mailer/'. $name;
		$mailer_class->template = $name;
		
		// Reflection to call build() with the additional parameters passed into this method.
		$args = array_slice(func_get_args(), 1);
		$c = new ReflectionClass($mailer_class);
		foreach ($c->getMethods() as $m) {
			if ($m->name == 'build') {
				foreach ($m->getParameters() as $key => $param) {
					$mailer_class->params[$param->name] = $args[$key];
				}
			}
		}
		call_user_func_array(array($mailer_class, 'build'), $args);
		$mailer_class->render();
		return $mailer_class;
	}
	
	/**
	 * Renders the email contents given the available views and template properties.
	 */
	public function render() {
		if ($this->template) {
			$filepath = sprintf('%s%s%s', $this->folder, DIRECTORY_SEPARATOR, $this->template);
			foreach ($this->formats as $format) {
				$layout = View::factory('mailer'. DIRECTORY_SEPARATOR .'layouts'. DIRECTORY_SEPARATOR . $this->layout .'.'. $format);
				$layout->content = View::factory($filepath .'.'. $format);
				foreach ($this->params as $key => $value) {
					$layout->content->$key = $value;
				}
				$this->content[$format] = $layout->render();
			}
		}
	}
	
	/**
	 * Deliver the email using the specified driver.
	 */
	public function deliver() {
		$this->driver->deliver($this, $this->config->driver_options);
		// Mailer::$log[] = $this;
	}
	
}