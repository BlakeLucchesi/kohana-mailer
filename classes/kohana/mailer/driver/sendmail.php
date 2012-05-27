<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Mailer_Driver_Sendmail implements Kohana_Mailer_Driver {
	
	public function deliver(Mailer $mailer, $options = array()) {
		Kohana::$log->add(Log::INFO, 'Sending email via sendmail driver. Email details: :mailer', array(':mailer' => print_r($mailer, TRUE)));
	}
	
}