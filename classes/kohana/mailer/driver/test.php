<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Mailer_Driver_Test implements Kohana_Mailer_Driver {
	
	public function deliver(Mailer $mailer) {
		var_dump('sending email via test driver');
		// var_dump($mailer->content);
	}
	
}