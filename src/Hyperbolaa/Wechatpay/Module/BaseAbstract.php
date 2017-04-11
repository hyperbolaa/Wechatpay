<?php


namespace Hyperbolaa\Wechatpay\Module;

use GuzzleHttp\Client as HttpClient;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class BaseAbstract
{
	/**
	 * The request client.
	 */
	protected $httpClient;

	/**
	 * The HTTP request object.
	 */
	protected $httpRequest;

	/**
	 * @var
	 */
	protected $responseData;


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
	 * 初始化
	 * BaseAbstract constructor.
	 */
	public function __construct()
	{
		$this->httpClient  = $this->getDefaultHttpClient();
		$this->httpRequest = $this->getDefaultHttpRequest();
	}

	/**
	 * Get the global default HTTP client.
	 *
	 * @return HttpClient
	 */
	protected function getDefaultHttpClient()
	{
		return new HttpClient(
			['curl.options' => array(CURLOPT_CONNECTTIMEOUT => 60)]
		);
	}

	/**
	 * Get the global default HTTP request.
	 *
	 * @return HttpRequest
	 */
	protected function getDefaultHttpRequest()
	{
		return HttpRequest::createFromGlobals();
	}


}