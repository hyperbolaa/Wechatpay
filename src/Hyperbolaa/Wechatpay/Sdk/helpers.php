<?php

namespace Hyperbolaa\Wechatpay\Sdk;

/**
 * 函数式数据转换
 * @param $arr
 * @param string $root
 * @return string
 */
function array2xml($arr, $root = 'xml')
{
	$xml = "<$root>";
	foreach ($arr as $key => $val) {
		if (is_numeric($val)) {
			$xml .= "<" . $key . ">" . $val . "</" . $key . ">";
		} else {
			$xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
		}
	}
	$xml .= "</xml>";

	return $xml;
}

/**
 * 函数式数据转换
 * @param $xml
 * @return mixed
 */
function xml2array($xml)
{
	libxml_disable_entity_loader(true);
	return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
}


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

