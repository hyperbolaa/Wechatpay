<?php

namespace Hyperbolaa\Wechatpay\Module;


/**
 * 关闭订单
 * 注意：订单生成后不能马上调用关单接口，最短调用时间间隔为5分钟。
 * Class CloseOrder
 * @package Hyperbolaa\Wechatpay\Sdk
 * @link    https://pay.weixin.qq.com/wiki/doc/api/app.php?chapter=9_3&index=5
 */
class CloseOrder extends BaseAbstract
{
	protected $endpoint = 'https://api.mch.weixin.qq.com/pay/closeorder';


	/**
	 * @return array
	 */
	public function index()
	{

		$this->validate('app_id', 'mch_id', 'out_trade_no');

		$data = array (
			'appid'        => $this->getAppId(),
			'mch_id'       => $this->getMchId(),
			'out_trade_no' => $this->getOutTradeNo(),
			'nonce_str'    => md5(uniqid()),
		);

		$data = array_filter($data);

		$data['sign'] = Helper::sign($data, $this->getApiKey());

		return $data;
	}





	/**
	 * Send the request with specified data
	 *
	 * @param  mixed $data The data to send
	 *
	 * @return ResponseInterface
	 */
	public function sendData($data)
	{
		$request      = $this->httpClient->post($this->endpoint)->setBody(Helper::array2xml($data));
		$response     = $request->send()->getBody();
		$responseData = Helper::xml2array($response);

		return $this->response = new CloseOrderResponse($this, $responseData);
	}

}