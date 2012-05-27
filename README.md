Kohana Mailer Module
====================

This module provides an interface for creating emails similar to the way ActionMailer does for Ruby on Rails.  Its main purpose is to make emails more object oriented and allow the application developer to send emails with a single line of code.  Each mailer is comprised of the following pieces:

* A mailer class found in the classes/mailer/ folder.
* The mailer class should implement a build() function that handles setting up the mailer properties.
* Corresponding views found in views/mailer/<name>/<name>.(text|html).php.

Below is an example showing how to send the mailer in your application and the supporting mailer code.

		# Sending email from your controller or model.
		Mailer::factory('<name>', param1, param2);

		# application/classes/mailer/name.php

		Class Mailer_Name extends Mailer {
		
				public function build($param1, $param2) {
					$this->to = $param1;
					$this->subject = 'Email Subject';
					$this->formats = array('html');
				}
		
		}

		# applications/view/mailer/name.php

		Some HTML content for the mailer gets built here.
		
		<?= $data->param1 ?>


Available Properties
====================

**$to** The address to which the email should be sent

**$from** The from address

**$formats** An array of supported mail formats, typically this would include _text_ and/or _html_.