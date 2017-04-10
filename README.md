# Wechatpay
微信支付，laravel, laravel5

#### 安装
    composer require hyperbolaa/wechatpay dev-master

#### laravel 配置
     'providers' => [
         // ...
         Hyperbolaa\Wechatpay\WechatpayServiceProvider::class,
     ]
  
#### 生成配置文件
    运行 `php artisan vendor:publish` 命令，
    发布配置文件到你的项目中。
 
#### wap代码使用
    $wechatpay = app('wechatpay.jsapi');
    $wechatpay->setBody('我是测试商品');
    $wechatpay->setOutTradeNo(123456789);
    $wechatpay->setTotalFee(1);
    $wechatpay->setOpenid('ssssssss);
    
    $result = $wechatpay->prepare();
    if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS'){
        $prepayId = $result['prepay_id'];
        $wechatpay->configForPayment($prepayId);
        //WeixinJSBridge
        $json = $wechatpay->configForPayment($prepayId);
        $succ_url = 'xx';//支付成功回调地址
        $fail_url = 'xx';//支付失败回调地址
        $data = $wechatpay->handle($json,$succ_url,$fail_url);
        return new Response($data);
    }
    
#### 异步通知
    	public function wechatpayNotify()
    	{
    		// 判断通知类型。
            $response = app('wechatpay.jsapi')->handleNotify(function ($notify, $successful) {
                $out_trade_no   = $notify->out_trade_no;//商户订单号
                $transaction_id = $notify->transaction_id;//微信订单号
                //
                if($successful){
                    //todo 处理支付成功，，，
                }
                return true;
            });
            return $response;
    	}

    
     
## 货币单位
    分

## 支付类别
    JSAPI     公众号支付    已接通
    NATIVE    扫码支付
    APP       APP支付
    MICROPAY  刷卡支付

## 待优化
    数据严格要求
    添加记录日志
    请求信息统一处理
    回复信息统一处理
    数据格式的处理 json xml

