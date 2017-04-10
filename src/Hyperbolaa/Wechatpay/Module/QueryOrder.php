<?php

namespace Hyperbolaa\Wechatpay\Module;


/**
 * 查询订单
 * Class QueryOrder
 * @package Hyperbolaa\Wechatpay\Sdk
 * @link  https://pay.weixin.qq.com/wiki/doc/api/app.php?chapter=9_2&index=4
 */
class QueryOrder extends BaseAbstract
{

	public function getData()
	{
		$this->validate('app_id', 'mch_id');

		if (! $this->getTransactionId() && ! $this->getOutTradeNo()) {
			throw new InvalidRequestException("The 'transaction_id' or 'out_trade_no' parameter is required");

		}

		$data = array (
			'appid'          => $this->getAppId(),
			'mch_id'         => $this->getMchId(),
			'transaction_id' => $this->getTransactionId(),
			'out_trade_no'   => $this->getOutTradeNo(),
			'nonce_str'      => md5(uniqid()),
		);

		$data = array_filter($data);

		$data['sign'] = Helper::sign($data, $this->getApiKey());

		return $data;
	}


}
