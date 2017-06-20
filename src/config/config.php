<?php
return [
	'app'=>[
		'app_id'        => '',//微信支付移动APPID
		'merchant_id'   => '',//微信支付分配的商户号
		'key'           => '',//支付签名使用
		'notify_url'    => '',//异步接收微信支付结果通知的回调地址
		'device_info'   => 'APP',//设备号
	],
	'jsapi'=>[
		'app_id'        => '',//微信支付分配的公众账号APPID
		'merchant_id'   => '',//微信支付分配的商户号
		'key'           => '',//支付签名使用
		'notify_url'    => '',//异步接收微信支付结果通知的回调地址
		'device_info'   => 'WEB',//设备号
	],
	'xcx'=>[
		'app_id'        => '',//小程序APPID
		'merchant_id'   => '',//微信支付分配的商户号
		'key'           => '',//支付签名使用
		'notify_url'    => '',//异步接收微信支付结果通知的回调地址
		'device_info'   => 'WEB',//设备号
	]
];