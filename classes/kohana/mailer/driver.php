<?php defined('SYSPATH') or die('No direct script access.');

interface Kohana_Mailer_Driver {

	function deliver(Mailer $mailer, $options = array());

}