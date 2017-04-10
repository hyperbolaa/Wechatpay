<?php

namespace Hyperbolaa\Wechatpay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 刷卡支付
 * Class Micropay
 * @package Hyperbolaa\Wechatpay\Facades
 */
class Micropay extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'wechatpay.micropay';
	}
}