<?php

namespace Hyperbolaa\Wechatpay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 扫码支付
 * Class Native
 * @package Hyperbolaa\Wechatpay\Facades
 */
class Native extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'wechatpay.native';
	}
}