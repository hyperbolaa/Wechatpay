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
    $wechatpay->setOpenid('ssssssss');
    
    $result = $wechatpay->prepare();
    if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS'){
        $prepayId = $result['prepay_id'];
        //WeixinJSBridge
        $json = $wechatpay->configForPayment($prepayId);
        $succ_url = 'xx';//支付成功回调地址
        $fail_url = 'xx';//支付失败回调地址
        $data = $wechatpay->bridgeHandle($json,$succ_url,$fail_url);
        return new Response($data);
    }else{
        //faile
        return $result['return_msg'];
    }

#### app 代码使用
    $wechatpay = app('wechatpay.app');
    $wechatpay->setBody('我是测试商品');
    $wechatpay->setOutTradeNo(123456789);
    $wechatpay->setTotalFee(1);
    
    $result = $wechatpay->prepare();
    if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS'){
        $prepayId = $result['prepay_id'];
        return $wechatpay->configForPayment($prepayId);
    }else{
        //fail 
        return $result['return_msg'];
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
        
        return new Response($response);
    }

    
     
## 货币单位
    分

## 支付类别
    JSAPI     公众号支付    已接通
    APP       APP支付      已接通
    NATIVE    扫码支付      
    MICROPAY  刷卡支付

## 已处理
    回复数据格式化处理，支持json,arr 

## 待优化
    添加日志记录
    数据严格要求
    参数的做统一处理

## 联系&打赏 ##

如果真心觉得项目帮助到你，为你节省了成本，欢迎鼓励一下。

如果有什么问题，可通过以下方式联系我。提供有偿技术服务。

也希望更多朋友可用提供代码支持。欢迎交流与打赏。

**邮箱**：yuchong321@126.com

**不错，我要鼓励一下**

![微信](http://onzbviqx3.bkt.clouddn.com/hyperbolaa_wechat.JPG?imageView2/2/w/200/h/300)
![支付宝](http://onzbviqx3.bkt.clouddn.com/hyperbolaa_alipay.JPG?imageView2/2/w/220/h/260)
 
 ## Related
 
 - [Ylpay](https://github.com/hyperbolaa/Ylpay)   基于laravel5的POS通支付
 - [Alipay](https://github.com/hyperbolaa/Alipay)  基于laravel5的支付宝支付
 - [Unionpay](https://github.com/hyperbolaa/Unionpay)  基于laravel5的银联支付
 - [Alisms](https://github.com/hyperbolaa/Alisms)  基于laravel5的阿里云短信