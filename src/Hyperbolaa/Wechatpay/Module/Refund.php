<?php

namespace Hyperbolaa\Wechatpay\Module;

/**
 * 申请退款
 * 1、交易时间超过一年的订单无法提交退款；
 * 2、微信支付退款支持单笔交易分多次退款， 多次退款需要提交原支付订单的商户订单号和设置不同的退款单号。
 * 一笔退款失败后重新提交，要采用原来的退款单号。 总退款金额不能超过用户实际支付金额。
 * Class Refund
 * @package Hyperbolaa\Wechatpay\Sdk
 * @link    https://pay.weixin.qq.com/wiki/doc/api/app.php?chapter=9_4&index=6
 */
class Refund extends BaseAbstract
{
	public function index()
	{
		//todo
	}
}