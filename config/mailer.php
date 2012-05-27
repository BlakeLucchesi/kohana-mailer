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
 * Each driver may have additional configuration options that can 
 * be passed in via $config['driver_options'];
 */
$config['driver_options'] = array();

/**
 * Global from address, used when not specified in the mailer.
 */
$config['from'] = 'team@kohanaframework.org';

return $config;