<?php

namespace Hyperbolaa\Wechatpay\Sdk;

use Hyperbolaa\Wechatpay\Module\ShortUrl;

class NativePay
{
	/**
	 * 短连接
	 */
	public function index(){
		$shortUrlObj = new ShortUrl();
		$data = $shortUrlObj->index();
		return $data;
	}
}