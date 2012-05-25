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
   * The loaded configuration values.
   */
  protected $config;
  
  /**
   * 
   */
  protected $data;
  
  /**
   * The driver to send emails with.
   */
  protected $driver;
  
  /**
   * Initialize a mailer class.
   */
  public function __construct() {
    $this->data = func_get_args();
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
    foreach (array_slice(func_get_args(), 1) as $key => $value) {
      $mailer_class->data[$key] = $value;
    }
    $mailer_class->build();
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
				$this->content[$format] = $layout->render();
			}
    }
  }
  
  /**
   * Deliver the email using the specified driver.
   */
  public function deliver() {
    $this->driver->deliver($this);
    // Mailer::$log[] = $this;
  }
  
}