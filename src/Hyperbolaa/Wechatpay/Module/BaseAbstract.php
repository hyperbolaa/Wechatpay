<?php


namespace Hyperbolaa\Wechatpay\Module;

use Hyperbolaa\Wechatpay\Exception\FaultException;
use Hyperbolaa\Wechatpay\Lib\XML;
use Hyperbolaa\Wechatpay\Lib\Http;
use Hyperbolaa\Wechatpay\Lib\Collection;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * 接口请求的处理
 * Class BaseAbstract
 * @package Hyperbolaa\Wechatpay\Module
 */
class BaseAbstract
{
	/**
	 * The request client.
	 */
	protected $http;

	/**
	 * The HTTP request object.
	 */
	protected $httpRequest;


	//请求地址
	const API_PREPARE_ORDER = 'https://api.mch.weixin.qq.com/pay/unifiedorder';//统一下单
	const API_PAY_ORDER = 'https://api.mch.weixin.qq.com/pay/micropay';//刷卡支付
	const API_QUERY = 'https://api.mch.weixin.qq.com/pay/orderquery';//订单查询
	const API_CLOSE = 'https://api.mch.weixin.qq.com/pay/closeorder';//关闭订单
	const API_REVERSE = 'https://api.mch.weixin.qq.com/secapi/pay/reverse';//撤销订单
	const API_REFUND = 'https://api.mch.weixin.qq.com/secapi/pay/refund';//申请退款
	const API_QUERY_REFUND = 'https://api.mch.weixin.qq.com/pay/refundquery';//退款查询
	const API_DOWNLOAD_BILL = 'https://api.mch.weixin.qq.com/pay/downloadbill';//下载对账单
	const API_REPORT = 'https://api.mch.weixin.qq.com/payitil/report';//交易保障，测速上报
	const API_URL_SHORTEN = 'https://api.mch.weixin.qq.com/tools/shorturl';//转换短连接
	const API_AUTH_CODE_TO_OPENID = 'https://api.mch.weixin.qq.com/tools/authcodetoopenid';//授权码查询openID


	// order id types.
	const TRANSACTION_ID    = 'transaction_id';
	const OUT_TRADE_NO      = 'out_trade_no';//商户订单号
	const OUT_REFUND_NO     = 'out_refund_no';
	const REFUND_ID         = 'refund_id';

	// bill types.
	const BILL_TYPE_ALL     = 'ALL';//当日所有订单【默认值】
	const BILL_TYPE_SUCCESS = 'SUCCESS';//当日支付成功订单
	const BILL_TYPE_REFUND  = 'REFUND';//当日退款订单
	const BILL_TYPE_REVOKED = 'REVOKED';//当日已撤销的订单

	/**
	 * @return Http
	 */
	public function getHttp()
	{
		if (is_null($this->http)) {
			$this->http = new Http();
		}

		if (count($this->http->getMiddlewares()) === 0) {
			$this->registerHttpMiddlewares();
		}

		return $this->http;
	}

	/**
	 * Register Guzzle middlewares.
	 */
	protected function registerHttpMiddlewares()
	{
		$this->http->addMiddleware($this->logMiddleware());
	}


	/**
	 * Log the request.
	 *
	 * @return \Closure
	 */
	protected function logMiddleware()
	{
		return Middleware::tap(function (RequestInterface $request, $options) {
			Log::debug("Request: {$request->getMethod()} {$request->getUri()} ".json_encode($options));
			Log::debug('Request headers:'.json_encode($request->getHeaders()));
		});
	}


	/**
	 * @param Http $http
	 * @return $this
	 */
	public function setHttp(Http $http)
	{
		$this->http = $http;

		return $this;
	}

	/**
	 * @param $method
	 * @param array $args
	 * @return Collection
	 */
	public function parseJSON($method, array $args)
	{
		$http = $this->getHttp();

		$contents = $http->parseJSON(call_user_func_array([$http, $method], $args));

		$this->checkAndThrow($contents);

		return new Collection($contents);
	}

	/**
	 * Check the array data errors, and Throw exception when the contents contains error.
	 * @param array $contents
	 * @throws FaultException
	 */
	protected function checkAndThrow(array $contents)
	{
		if (isset($contents['errcode']) && 0 !== $contents['errcode']) {
			if (empty($contents['errmsg'])) {
				$contents['errmsg'] = 'Unknown';
			}

			throw new FaultException($contents['errmsg'], $contents['errcode']);
		}
	}


	/**
	 * @param $response
	 * @return Collection
	 */
	protected function parseResponse($response)
	{
		if ($response instanceof ResponseInterface) {
			$response = $response->getBody();
		}
		return new Collection((array) XML::parse($response));
	}

}