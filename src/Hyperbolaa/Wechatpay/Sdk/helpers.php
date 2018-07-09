<?php

namespace Hyperbolaa\Wechatpay\Sdk;


/**
 * @param $data
 * @param $key
 * @return string
 */
function generate_sign($attributes, $key, $encryptMethod = 'md5')
{
	$attributes  = array_filter($attributes);

	ksort($attributes);

	$attributes['key'] = $key;

	return strtoupper(call_user_func_array($encryptMethod, [urldecode(http_build_query($attributes))]));
}


/**
 * Get client ip.
 *
 * @return string
 */
function get_client_ip()
{
	if (!empty($_SERVER['REMOTE_ADDR'])) {
		$ip = $_SERVER['REMOTE_ADDR'];
	} else {
		// for php-cli(phpunit etc.)
		$ip = defined('PHPUNIT_RUNNING') ? '127.0.0.1' : gethostbyname(gethostname());
	}

	return filter_var($ip, FILTER_VALIDATE_IP) ?: '127.0.0.1';
}

/**
 * Get current server ip.
 *
 * @return string
 */
function get_server_ip()
{
	if (!empty($_SERVER['SERVER_ADDR'])) {
		$ip = $_SERVER['SERVER_ADDR'];
	} elseif (!empty($_SERVER['SERVER_NAME'])) {
		$ip = gethostbyname($_SERVER['SERVER_NAME']);
	} else {
		// for php-cli(phpunit etc.)
		$ip = defined('PHPUNIT_RUNNING') ? '127.0.0.1' : gethostbyname(gethostname());
	}

	return filter_var($ip, FILTER_VALIDATE_IP) ?: '127.0.0.1';
}

