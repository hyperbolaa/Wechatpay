<?php

namespace Hyperbolaa\Wechatpay\Module;

use Hyperbolaa\Wechatpay\Lib\Helper;

/**
 * 统一下单接口
 * Class UnifiedOrder
 * @package Hyperbolaa\Wechatpay\Sdk
 * @link    https://pay.weixin.qq.com/wiki/doc/api/app.php?chapter=9_1
 */
class UnifiedOrder extends BaseAbstract
{
	/**
	 * 统一下单
	 */
	public function create($data){
		$request      = $this->httpClient->request('POST',self::API_PREPARE_ORDER,['body'=>Helper::array2xml($data)]);
		$response     = $request->getBody();
		$responseData = Helper::xml2array($response);
		return $responseData;
	}

}
