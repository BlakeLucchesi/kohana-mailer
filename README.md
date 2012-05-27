Kohana Mailer Module
====================

This module provides an interface for creating emails similar to the way ActionMailer does for Ruby on Rails.  Its main purpose is to make emails more object oriented and allow the application developer to send emails with a single line of code.  Each mailer is comprised of the following pieces:

* A mailer class found in the classes/mailer/ folder.
* The mailer class should implement a build() function that handles setting up the mailer properties.
* Corresponding views found in views/mailer/__name__/__name__.(text|html).php.

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

**$to** - The address to which the email should be sent

**$from** - The from address

**$auto_render** - Whether or not to render the template automatically just before deliver() is executed on the mail driver.  If this is FALSE, you should ensure to set the contents of the email inside of $content[$format] where $format is each of the formats you wish to send the email in.

**$formats** - An array of supported mail formats, typically this would include _text_ and/or _html_.

**$template** - The name of the template to render for each format. Defaults to $name/$name, which maps to views/mailer/$name/$name.$format.php.  Make sure not to include the $format suffix as part of the template name.

**$layout** - The layout template that should be used as a shell for rendering the template.  By default its set to the 'blank' layout as included in this module.

Issues
======

Please report any issues, pull requests or feedback into this issue queue.

License (MIT License)
=====================

Copyright (C) 2012 Blake Lucchesi

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.