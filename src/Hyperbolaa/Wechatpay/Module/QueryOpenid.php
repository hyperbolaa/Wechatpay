<?php


namespace Hyperbolaa\Wechatpay\Module;

/**
 * 授权码查询OPENID接口
 * Class QueryOpenid
 * @package Hyperbolaa\Wechatpay\Sdk
 * @link https://pay.weixin.qq.com/wiki/doc/api/micropay.php?chapter=9_13&index=9
 */
class QueryOpenid extends BaseAbstract
{
	public function getData()
	{

		$this->validate('app_id', 'mch_id', 'auth_code');

		$data = array (
			'appid'     => $this->getAppId(),
			'mch_id'    => $this->getMchId(),
			'auth_code' => $this->getAuthCode(),
			'nonce_str' => md5(uniqid()),
		);

		$data = array_filter($data);

		$data['sign'] = Helper::sign($data, $this->getApiKey());

		return $data;
	}
}