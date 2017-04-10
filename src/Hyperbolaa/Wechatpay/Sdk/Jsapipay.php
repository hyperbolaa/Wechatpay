<?php

namespace Hyperbolaa\Wechatpay\Sdk;

use Hyperbolaa\Wechatpay\Lib\Helper;
use Hyperbolaa\Wechatpay\Module\UnifiedOrder;

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
	private $device_info;//设备号
	private $goods_tag;//商品标记
	private $trade_type = 'JSAPI';//交易类型  JSAPI
	private $fee_type   = 'CNY';//标价币种


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

	public function setMerhantId($value){
		$this->merchant_id = $value;
		return $this;
	}




	/**
	 * 短连接
	 */
	public function sign(){

		$data = [
			'appid'            => $this->app_id,
			'mch_id'           => $this->merchant_id,
			'device_info'      => $this->device_info,
			'body'             => $this->body,
			'attach'           => $this->attach,
			'out_trade_no'     => $this->out_trade_no,
			'fee_type'         => $this->fee_type,
			'total_fee'        => $this->total_fee,
			'spbill_create_ip' => $this->spbill_create_ip,
			'time_start'       => $this->time_start,
			'time_expire'      => $this->time_expire,
			'goods_tag'        => $this->goods_tag,
			'notify_url'       => $this->notify_url,
			'trade_type'       => $this->trade_type,
			'limit_pay'        => $this->limit_pay,
			'openid'           => $this->openid,
			'nonce_str'        => md5(uniqid()),
		];

		$data['sign'] = Helper::sign($data, $this->key);
		return $data;
	}

	/**
	 * 测试支付
	 */
	public function pay(){
		$data = $this->sign();
		$unifiedOrder = new UnifiedOrder();
		return  $unifiedOrder->create($data);
	}



	/**
	 * 执行微信支付
	 * @param $config
	 * @param $succ_url
	 * @param $fail_url
	 * @return string
	 */
	public static function wxpayHandle($config, $succ_url, $fail_url)
	{
		return '
            <script type="text/javascript">
                function jsApiCall()
                {
                    WeixinJSBridge.invoke("getBrandWCPayRequest",' . $config . ', function(res){
                            //WeixinJSBridge.log(res.err_msg);
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




}