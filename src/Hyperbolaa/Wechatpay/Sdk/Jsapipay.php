<?php

namespace Hyperbolaa\Wechatpay\Sdk;

use Hyperbolaa\Wechatpay\Module\UnifiedOrder;

class Jsapipay extends BasePay
{

	protected $trade_type  = 'JSAPI';//交易类型

	/**
	 * 签名
	 */
	public function sign(){

		$data = [
			'appid'            => $this->app_id,//--
			'mch_id'           => $this->merchant_id,//--
			'device_info'      => $this->device_info ?: 'WEB',
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
			'nonce_str'        => $this->getNonceStr()
		];

		$data['sign'] = Helper::sign($data, $this->key);
		return $data;
	}

	/**
	 * 统一下单
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
	public static function bridgeHandle($config, $succ_url, $fail_url)
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
	public function configForJssdkPayment($prepayId)
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


}