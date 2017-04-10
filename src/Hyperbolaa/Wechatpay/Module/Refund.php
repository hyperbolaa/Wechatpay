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
	public function getData()
	{
		$this->validate('app_id', 'mch_id', 'out_trade_no', 'cert_path', 'key_path');

		$data = array (
			'appid'           => $this->getAppId(),
			'mch_id'          => $this->getMchId(),
			'device_info'     => $this->getDeviceInfo(),
			'transaction_id'  => $this->getTransactionId(),
			'out_trade_no'    => $this->getOutTradeNo(),
			'out_refund_no'   => $this->getOutRefundNo(),
			'total_fee'       => $this->getTotalFee(),
			'refund_fee'      => $this->getRefundFee(),
			'refund_fee_type' => $this->getRefundFee(),
			'op_user_id'      => $this->getOpUserId() ?: $this->getMchId(),
			'nonce_str'       => md5(uniqid()),
		);

		$data = array_filter($data);

		$data['sign'] = Helper::sign($data, $this->getApiKey());

		return $data;
	}
}