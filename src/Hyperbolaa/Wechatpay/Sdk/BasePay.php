<?php

namespace Hyperbolaa\Wechatpay\Sdk;

use Hyperbolaa\Wechatpay\Exception\FaultException;
use Hyperbolaa\Wechatpay\Lib\XML;
use Hyperbolaa\Wechatpay\Lib\Log;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;

/**
 * 支付的所有接口地址
 * Class BasePay
 * @package Hyperbolaa\Wechatpay\Sdk
 */
class BasePay extends Container
{
	protected $config; //参数
	protected $app_id;//微信支付分配的公众账号ID
	protected $merchant_id;//微信支付分配的商户号
	protected $key;//支付签名使用
	protected $notify_url;//通知地址
	protected $body;//商品描述
	protected $out_trade_no;//商户订单号
	protected $total_fee;//标价金额，单位：分
	protected $spbill_create_ip;//终端IP
	protected $attach;//附加数据
	protected $limit_pay;//指定支付方式
	protected $time_start;//交易开始时间
	protected $time_expire;//交易结束时间
	protected $goods_tag;//商品标记
	protected $detail;
	protected $openid;//用户标识
	protected $product_id;//产品ID
	protected $device_info;//设备号
	protected $trade_type;//交易类型  [JSAPI,APP,NATIVE]；这三个走统一下单  【MICROPAY】：单独流程
	protected $fee_type = 'CNY';//标价币种

	public function __construct($config)
	{
		parent::__construct();
		$this['config'] = function () use ($config) {
			return new Config($config);
		};
		if ($this['config']['debug']) {
			error_reporting(E_ALL);
		}

		$this->initializeLogger();
		$this->logConfiguration($config);
	}

	/**
	 * Log configuration.
	 *
	 * @param array $config
	 */
	public function logConfiguration($config)
	{
		$config = new Config($config);

		$keys = ['app_id', 'secret', 'open_platform.app_id', 'open_platform.secret', 'mini_program.app_id', 'mini_program.secret'];
		foreach ($keys as $key) {
			!$config->has($key) || $config[$key] = '***'.substr($config[$key], -5);
		}

		Log::debug('Current config:', $config->toArray());
	}

	/**
	 * Initialize logger.
	 */
	private function initializeLogger()
	{
		if (Log::hasLogger()) {
			return;
		}

		$logger = new Logger('wechatpay');

		if (!$this['config']['debug'] || defined('PHPUNIT_RUNNING')) {
			$logger->pushHandler(new NullHandler());
		}elseif ($logFile = $this['config']['log.file']) {
			$logger->pushHandler(new StreamHandler(
					$logFile,
					$this['config']->get('log.level', Logger::WARNING),
					true,
					$this['config']->get('log.permission', null))
			);
		}

		Log::setLogger($logger);
	}


	/**
	 * 支付回调
	 * @param callable $callback
	 * @return string
	 * @throws \Exception
	 */
	public function handleNotify(callable $callback)
	{
		$notify = $this->getNotify();

		if (!$notify->isValid($this->key)) {
			throw new FaultException('Invalid request payloads.', 400);
		}

		$notify = $notify->getNotify();
		$successful = $notify->get('result_code') === 'SUCCESS';

		$handleResult = call_user_func_array($callback, [$notify, $successful]);

		if (is_bool($handleResult) && $handleResult) {
			$response = [
				'return_code' => 'SUCCESS',
				'return_msg' => 'OK',
			];
		} else {
			$response = [
				'return_code' => 'FAIL',
				'return_msg' => $handleResult,
			];
		}

		return  XML::build($response);
	}


	/**
	 * 验签
	 */
	public function verify(){
		$notify = $this->getNotify();
		return $notify->isValid($this->key);
	}


	/**
	 * Return Notify instance.
	 */
	public function getNotify()
	{
		return new Notify();
	}

	public function getNonceStr(){
		return md5(uniqid());
	}


	public function setGoodsTag($value){
		$this->goods_tag = $value;
		return $this;
	}

	public function setDeviceInfo($value){
		$this->device_info = $value;
		return $this;
	}

	public function setTimeExpire($value){
		$this->time_expire = $value;
		return $this;
	}

	public function setTimeStart($value){
		$this->time_start = $value;
		return $this;
	}

	public function setOpenid($value){
		$this->openid = $value;
		return $this;
	}


	public function limitPay($value){
		$this->limit_pay = $value;
		return $this;
	}

	public function setAttach($value){
		$this->attach = $value;
		return $this;
	}


	public function setSpbillCreateIp($value){
		$this->spbill_create_ip = $value;
		return $this;
	}

	public function setTotalFee($value){
		$this->total_fee = $value;
		return $this;
	}

	public function setOutTradeNo($value){
		$this->out_trade_no = $value;
		return $this;
	}

	public function setBody($value){
		$this->body = $value;
		return $this;
	}

	public function setNotifyUrl($value){
		$this->notify_url = $value;
		return $this;
	}

	public function setKey($value){
		$this->key = $value;
		return $this;
	}

	public function setAppId($value){
		$this->app_id = $value;
		return $this;
	}

	public function setMerchantId($value){
		$this->merchant_id = $value;
		return $this;
	}

	public function setDetail($value){
		$this->detail = $value;
		return $this;
	}

	public function setProductId($value){
		$this->product_id = $value;
		return $this;
	}

}