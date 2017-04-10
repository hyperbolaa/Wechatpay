<?php

namespace Hyperbolaa\Wechatpay\Lib;

/**
 * 辅助类
 */
class Helper
{
	/**
	 * @param $arr
	 * @param string $root
	 * @return string
	 */
	public static function array2xml($arr, $root = 'xml')
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
	 * @param $xml
	 * @return mixed
	 */
	public static function xml2array($xml)
	{
		return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
	}


	/**
	 * @param $data
	 * @param $key
	 * @return string
	 */
	public static function sign($data, $key)
	{
		unset($data['sign']);

		ksort($data);

		$query = urldecode(http_build_query($data));
		$query .= "&key={$key}";

		return strtoupper(md5($query));
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
}
