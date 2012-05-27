<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Mailer_Driver_Sendmail implements Kohana_Mailer_Driver {
	
	public function deliver($mailer) {
		var_dump("sending email via sendmail driver");
		// mail($mailer->to, $mailer->from, $mailer->subject, $mailer->content);
	}
	
}