<?php


namespace Hyperbolaa\Wechatpay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 小程序支付
 * Class Xcx
 * @package Hyperbolaa\Wechatpay\Facades
 */
class Xcx extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'wechatpay.xcx';
	}
}