<?php


namespace Hyperbolaa\Wechatpay\Module;

/**
 * 下载对账单
 * Class DownBIll
 * @package Hyperbolaa\Wechatpay\Sdk
 * @link https://pay.weixin.qq.com/wiki/doc/api/app.php?chapter=9_6
 */
class DownBill extends BaseAbstract
{
	public function getData()
	{
		$this->validate('app_id', 'mch_id', 'bill_date');

		$data = array (
			'appid'       => $this->getAppId(),
			'mch_id'      => $this->getMchId(),
			'device_info' => $this->getDeviceInfo(),
			'bill_date'   => $this->getBillDate(),
			'bill_type'   => $this->getBillType(),//<>
			'nonce_str'   => md5(uniqid()),
		);

		$data = array_filter($data);

		$data['sign'] = Helper::sign($data, $this->getApiKey());

		return $data;
	}
}