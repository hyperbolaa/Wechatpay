<?php

namespace Hyperbolaa\Wechatpay\Sdk;

use Hyperbolaa\Wechatpay\Lib\Helper;
use Hyperbolaa\Wechatpay\Lib\XML;
use Hyperbolaa\Wechatpay\Module\UnifiedOrder;
use Symfony\Component\HttpFoundation\Response;

class Jsapipay
{
	private $app_id;//微信支付分配的公众账号ID
	private $merchant_id;//微信支付分配的商户号
	private $key;//支付签名使用
	private $notify_url;//通知地址
	private $body;//商品描述
	private $out_trade_no;//商户订单号
	private $total_fee;//标价金额，单位：分
	private $spbill_create_ip;//终端IP
	private $openid;//用户标识
	private $attach;//附加数据
	private $limit_pay;//指定支付方式
	private $time_start;//交易开始时间
	private $time_expire;//交易结束时间
	private $goods_tag;//商品标记
	private $device_info ='WEB';//设备号
	private $trade_type  = 'JSAPI';//交易类型  JSAPI
	private $fee_type    = 'CNY';//标价币种


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


	/**
	 * 短连接
	 */
	public function sign(){

		$data = [
			'appid'            => $this->app_id,//--
			'mch_id'           => $this->merchant_id,//--
			'device_info'      => $this->device_info,
			'body'             => $this->body,//todo
			'attach'           => $this->attach,
			'out_trade_no'     => $this->out_trade_no,//todo
			'fee_type'         => $this->fee_type,
			'total_fee'        => $this->total_fee,//todo
			'spbill_create_ip' => $this->spbill_create_ip ?: Helper::get_client_ip(),
			'time_start'       => $this->time_start,
			'time_expire'      => $this->time_expire,
			'goods_tag'        => $this->goods_tag,
			'notify_url'       => $this->notify_url,//--
			'trade_type'       => $this->trade_type,
			'limit_pay'        => $this->limit_pay,
			'openid'           => $this->openid,//todo
			'nonce_str'        => md5(uniqid()),
		];

		$data['sign'] = Helper::sign($data, $this->key);
		return $data;
	}

	/**
	 * 准备支付
	 */
	public function prepare(){
		$data = $this->sign();
		$unifiedOrder = new UnifiedOrder();
		return  $unifiedOrder->create($data);
	}


	/**
	 * [WeixinJSBridge] Generate js config for payment.
	 *
	 * <pre>
	 * WeixinJSBridge.invoke(
	 *  'getBrandWCPayRequest',
	 *  ...
	 * );
	 * </pre>
	 *
	 * @param string $prepayId
	 * @param bool   $json
	 *
	 * @return string|array
	 */
	public function configForPayment($prepayId, $json = true)
	{
		$params = [
			'appId'     => $this->app_id,
			'timeStamp' => strval(time()),
			'nonceStr'  => uniqid(),
			'package'   => "prepay_id=$prepayId",
			'signType'  => 'MD5',
		];

		$params['paySign'] = Helper::sign($params, $this->key);

		return $json ? json_encode($params) : $params;
	}




	/**
	 * 执行微信支付
	 * @param $config
	 * @param $succ_url
	 * @param $fail_url
	 * @return string
	 */
	public static function handle($config, $succ_url, $fail_url)
	{
		return '
            <script type="text/javascript">
                function jsApiCall()
                {
                    WeixinJSBridge.invoke("getBrandWCPayRequest",' . $config . ', function(res){
                            WeixinJSBridge.log(res.err_msg);
                            //alert(res.err_code+res.err_desc+res.err_msg);
                            if(res.err_msg == "get_brand_wcpay_request:ok"){
                                   window.location.href="' . $succ_url . '";
                            }else{
                                alert(支付失败);
                                window.location.href="' . $fail_url . '";
                            }
                        }
                    );
                }
                function callpay()
                {
                    if (typeof WeixinJSBridge == "undefined"){
                        if( document.addEventListener ){
                            document.addEventListener("WeixinJSBridgeReady", jsApiCall, false);
                        }else if (document.attachEvent){
                            document.attachEvent("WeixinJSBridgeReady", jsApiCall);
                            document.attachEvent("onWeixinJSBridgeReady", jsApiCall);
                        }
                    }else{
                        jsApiCall();
                    }
                }
                window.onload = function(){
                    callpay();
                };
            </script>';
	}


	/**
	 * [JSSDK] Generate js config for payment.
	 *
	 * <pre>
	 * wx.chooseWXPay({...});
	 * </pre>
	 *
	 * @param string $prepayId
	 *
	 * @return array|string
	 */
	public function configForJSSDKPayment($prepayId)
	{
		$config = $this->configForPayment($prepayId, false);

		$config['timestamp'] = $config['timeStamp'];
		unset($config['timeStamp']);

		return $config;
	}


	/**
	 *  jssdk支付方式
	 */
	public function jssdkHandle($config,$succ_url){
		return '
			<script type="text/javascript">
				function callpay() {
				    wx.chooseWXPay({
					    timestamp: "'.$config['timestamp'].'",
					    nonceStr: "'.$config['nonceStr'].'",
					    package: "'.$config['package'].'",
					    signType: "'.$config['signType'].'",
					    paySign: "'.$config['paySign'].'", // 支付签名
					    success: function (res) {
					        // 支付成功后的回调函数
					        window.location.href="' . $succ_url . '";
					    }
					});
				}
				window.onload = function(){
	                callpay();
	            };
			</script>';
	}


	/**
	 * 支付回调
	 * @param callable $callback
	 * @return Response
	 * @throws \Exception
	 */
	public function handleNotify(callable $callback)
	{
		$notify = $this->getNotify();

		if (!$notify->isValid($this->key)) {
			throw new \Exception('Invalid request payloads.', 400);
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
		return new Response(XML::build($response));
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

}