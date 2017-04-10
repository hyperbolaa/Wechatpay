<?php

namespace Hyperbolaa\Wechatpay\Sdk;

class Micropay
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