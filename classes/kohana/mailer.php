<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Mailer {
	
	/**
	 * The name of the mailer.
	 */
	public $name = '';
	
	/**
	 * Whether or not to auto render the defined template.
	 *
	 * If false, the mailer class must build $this->content['<format>'] in its own build() function.
	 */
	public $auto_render = TRUE;
	
	/**
	 * Which view template should be used to render the email content.
	 */
	public $template = '';
	
	/**
	 * When rendering using templates, which layout to wrap the template in.
	 */
	public $layout = 'blank';
	
	/**
	 * The formats to render.
	 */
	public $formats = array('text');
	
	/**
	 * A running log of all email transactions sent during a request.
	 *
	 * To keep memory consumption low, this should be disabled in production.
	 */
	public static $deliveries = array();
	
	/**
	 * Mailer configuration values from config/mailer.php.
	 */
	protected $config;
	
	/**
	 * The driver to send emails with.
	 */
	protected $driver;
	
	/**
	 * Whether or not to keep a copy of each mailer instance in memory for testing.
	 */
	protected $log_deliveries = FALSE;
	
	/**
	 * A keyed array of variables which get passed into the view template.
	 *
	 * Also used to store the parameters passed into factory() so that
	 * they are available when build() is called, and then also
	 * passed into the view templates using the same name as given
	 * to them in the mailer's build() function definition.
	 */
	protected $data = array();
	
	/**
	 * Initialize a mailer class.
	 */
	public function __construct() {
		$this->config = Kohana::$config->load('mailer');
		$this->log_deliveries = $this->config->log_deliveries;
		$driver_class = 'Mailer_Driver_'. ucwords($this->config->driver);
		$this->driver = new $driver_class;
	}
	
	/**
	 * Allow emails to be built and delivered in our app using a single line of code.
	 *
	 * We use method reflection to rebind the parameters defined in the mailers build()
	 * method so they can be used with the same names in the view templates.
	 *
	 * @param string $name The name of the mailer to initialize (minus the starting "Mailer_"). 
	 *                     Name adheres to Kohana class loader scheme.
	 *
	 * @return Mailer a loaded mailer object with which to interact.
	 */
	public static function factory($name) {
		$mailer_class = 'Mailer_'. ucwords($name);
		$mailer_class = new $mailer_class();
		$mailer_class->name = $name;
		$mailer_class->template = $name;
		
		$args = array_slice(func_get_args(), 1);
		$c = new ReflectionClass($mailer_class);
		foreach ($c->getMethods() as $m) {
			if ($m->name == 'build') {
				foreach ($m->getParameters() as $key => $param) {
					if (isset($args[$key])) {
						$value = $args[$key];
					}
					else if ($param->isDefaultValueAvailable()) {
						$value = $param->getDefaultValue();
					}
					else {
						$value = NULL;
					}
					$mailer_class->data[$param->name] = $value;
				}
			}
		}
		call_user_func_array(array($mailer_class, 'build'), $args);
		return $mailer_class;
	}
	
	/**
	 * Renders the email contents given the available views and template properties.
	 */
	public function render() {
		$template_path = 'mailer'. DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR . $this->template;
		$layout_path = 'mailer'. DIRECTORY_SEPARATOR .'layouts'. DIRECTORY_SEPARATOR . $this->layout;
		foreach ($this->formats as $format) {
			$layout = View::factory($layout_path .'.'. $format);
			$layout->content = View::factory($template_path .'.'. $format);
			foreach ($this->data as $key => $value) {
				$layout->content->$key = $value;
			}
			$this->content[$format] = $layout->render();
		}
	}
	
	/**
	 * Deliver the email using the specified driver.
	 */
	public function deliver() {
		if ($this->auto_render) {
			$this->render();
		}
		$this->driver->deliver($this, $this->config->driver_options);
		if ($this->log_deliveries) {
			Mailer::$deliveries[] = $this;
		}
	}
	
}