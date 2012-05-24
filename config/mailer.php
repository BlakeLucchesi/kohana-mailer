<?php defined('SYSPATH') or die('No direct script access.');

$config = array();

/**
 * Define which email driver to send emails with.
 *
 * Options include: 'test', 'sendmail', 'swiftmailer', etc.
 *
 */
$config['driver'] = 'test';

/**
 * Global from address, used when not specified in the mailer.
 */
$config['from'] = 'team@kohanaframework.org';

return $config;