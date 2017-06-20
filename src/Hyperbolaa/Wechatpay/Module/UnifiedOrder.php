<?php

namespace Hyperbolaa\Wechatpay\Module;

use Hyperbolaa\Wechatpay\Lib\XML;
use Hyperbolaa\Wechatpay\Lib\Collection;

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
	 * @param $params
	 * @param array $options
	 * @return \Hyperbolaa\Wechatpay\Lib\Collection
	 */
	public function create($params,$options = []){
		$params  = array_filter($params);
		$options = array_merge([
			'body' => XML::build($params),
		], $options);

		$response  = $this->getHttp()->request(self::API_PREPARE_ORDER,'POST',$options);
		return $this->parseResponse($response);
	}

}
