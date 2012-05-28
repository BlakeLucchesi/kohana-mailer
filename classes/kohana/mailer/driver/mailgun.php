<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Mailer_Driver_Mailgun implements Mailer_Driver {
	
	public function deliver(Mailer $mailer, $options = array()) {
		Kohana::$log->add(Log::INFO, 'Sending email via mailgun email driver. Email details: :mailer', array(':mailer' => print_r($mailer, TRUE)));
		$url = $options['url'];
		$apikey = $options['apikey'];

		$fields = array(
			'from' => $mailer->from,
			'to' => $mailer->to,
			'subject' => $mailer->subject,
		);
		foreach ($mailer->formats as $format) {
			$fields[$format] = $mailer->content[$format];
		}

		// Build curl request.
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERPWD, $apikey);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		
		// Additional curl request options.
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		# Success!
		if ($http_code == 200) {
			$data = json_decode($response);
			Kohana::$log->add(Log::INFO, 'Successfully sent ":name" email to :to via Mailgun HTTP API.', array(':name' => $mailer->name, ':to' => $mailer->to));
		}
		else {
			Kohana::$log->add(Log::ERROR, 'Error sending email via mailgun. Details: :details', array(':details' => print_r($response, TRUE)));
		}
	}
}