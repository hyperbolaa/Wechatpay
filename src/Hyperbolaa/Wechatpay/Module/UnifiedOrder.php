<?php

namespace Hyperbolaa\Wechatpay\Module;

use Hyperbolaa\Wechatpay\Lib\Collection;
use Hyperbolaa\Wechatpay\Lib\XML;

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
		$data         = array_filter($data);
		$request      = $this->httpClient->request('POST',self::API_PREPARE_ORDER,['body'=>XML::build($data)]);
		$response     = $request->getBody();
		$responseData = XML::parse($response);
		return new Collection((array)$responseData);
	}

}
