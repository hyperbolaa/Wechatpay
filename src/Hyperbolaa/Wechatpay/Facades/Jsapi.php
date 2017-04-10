<?php


namespace Hyperbolaa\Wechatpay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 公众号支付
 * Class Jsapi
 * @package Hyperbolaa\Wechatpay\Facades
 */
class Jsapi extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'wechatpay.jsapi';
	}
}