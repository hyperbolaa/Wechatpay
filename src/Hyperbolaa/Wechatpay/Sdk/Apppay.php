<?php

namespace Hyperbolaa\Wechatpay\Sdk;

use Hyperbolaa\Wechatpay\Lib\Helper;

class Apppay
{

	/**
	 * 短连接
	 */
	public function index(){
		//
	}


	/**
	 * Generate app payment parameters.
	 *
	 * @param string $prepayId
	 *
	 * @return array
	 */
	public function configForAppPayment($prepayId)
	{
		$params = [
			'appid'         => $this->app_id,
			'partnerid'     => $this->merchant_id,
			'prepayid'      => $prepayId,
			'noncestr'      => uniqid(),
			'timestamp'     => time(),
			'package'       => 'Sign=WXPay',
		];

		$params['sign'] = Helper::sign($params, $this->key);

		return $params;
	}
}