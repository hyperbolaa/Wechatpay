<?php

namespace Hyperbolaa\Wechatpay\Module;

/**
 * 生成短连接
 * Class ShortUrl
 * @package Hyperbolaa\Wechatpay\Sdk
 * @link https://pay.weixin.qq.com/wiki/doc/api/micropay.php?chapter=9_9&index=8
 */
class ShortUrl extends BaseAbstract
{
	public function index()
	{

		$this->validate('app_id', 'mch_id', 'long_url');

		$data = array (
			'appid'     => $this->getAppId(),
			'mch_id'    => $this->getMchId(),
			'long_url'  => $this->getLongUrl(),
			'nonce_str' => md5(uniqid()),
		);

		$data = array_filter($data);

		$data['sign'] = Helper::sign($data, $this->getApiKey());

		return $data;
	}
}