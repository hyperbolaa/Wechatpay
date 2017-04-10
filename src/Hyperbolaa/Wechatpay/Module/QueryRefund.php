<?php


namespace Hyperbolaa\Wechatpay\Module;

/**
 * 查询退款
 * Class QueryRefund
 * @package Hyperbolaa\Wechatpay\Sdk
 * @link    https://pay.weixin.qq.com/wiki/doc/api/app.php?chapter=9_5&index=7
 */
class QueryRefund extends BaseAbstract
{
	public function getData()
	{
		$this->validate('app_id', 'mch_id');

		$queryIdEmpty = ! $this->getTransactionId() && ! $this->getOutTradeNo();
		$queryIdEmpty = ($queryIdEmpty && ! $this->getOutRefundNo() && ! $this->getRefundId());

		if ($queryIdEmpty) {
			$message = "The 'transaction_id' or 'out_trade_no' or 'out_refund_no' or 'refund_id' parameter is required";
			throw new InvalidRequestException($message);
		}

		$data = array (
			'appid'          => $this->getAppId(),
			'mch_id'         => $this->getMchId(),
			'device_info'    => $this->getDeviceInfo(),
			'transaction_id' => $this->getTransactionId(),
			'out_trade_no'   => $this->getOutTradeNo(),
			'out_refund_no'  => $this->getOutRefundNo(),
			'refund_id'      => $this->getRefundId(),
			'nonce_str'      => md5(uniqid()),
		);

		$data = array_filter($data);

		$data['sign'] = Helper::sign($data, $this->getApiKey());

		return $data;
	}
}