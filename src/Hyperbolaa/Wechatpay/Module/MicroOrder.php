<?php
/**
 * 提交刷卡支付
 */

namespace Hyperbolaa\Wechatpay\Module;


class MicroOrder extends BaseAbstract
{
	public function getData()
	{
		$this->validate('app_id', 'mch_id', 'body', 'out_trade_no', 'total_fee', 'auth_code');

		$data = array (
			'appid'            => $this->getAppId(),//*
			'mch_id'           => $this->getMchId(),
			'device_info'      => $this->getDeviceInfo(),//*
			'body'             => $this->getBody(),//*
			'detail'           => $this->getDetail(),
			'attach'           => $this->getAttach(),
			'out_trade_no'     => $this->getOutTradeNo(),//*
			'fee_type'         => $this->getFeeType(),
			'total_fee'        => $this->getTotalFee(),//*
			'spbill_create_ip' => $this->getSpbillCreateIp(),//*
			'goods_tag'        => $this->getGoodsTag(),
			'limit_pay'        => $this->getLimitPay(),
			'auth_code'        => $this->getAuthCode(),//*
			'nonce_str'        => md5(uniqid()),//*
		);

		$data = array_filter($data);

		$data['sign'] = Helper::sign($data, $this->getApiKey());

		return $data;
	}
}