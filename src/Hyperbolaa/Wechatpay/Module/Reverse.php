<?php

namespace Hyperbolaa\Wechatpay\Module;

/**
 * 撤销订单API---目前只有 刷卡支付 有此功能。
 * 调用支付接口后请勿立即调用撤销订单API，建议支付后至少15s后再调用撤销订单接口。
 * Class Reverse
 * @package Hyperbolaa\Wechatpay\Sdk
 * @link https://pay.weixin.qq.com/wiki/doc/api/micropay.php?chapter=9_11&index=3
 */
class Reverse extends BaseAbstract
{
	public function index()
	{
		//todo
	}
}