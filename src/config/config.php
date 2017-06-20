<?php
return [
	/*
	 * Debug 模式，bool 值：true/false
	 * 当值为 false 时，所有的日志都不会记录
	 */
	'debug' =>  true,
	/*
	 * level: 日志级别，可选为：debug/info/notice/warning/error/critical/alert/emergency
	 * file：日志文件位置(绝对路径!!!)，要求可写权限
	 */
	'log'=>[
		'level' => 'debug',
		'file'  => storage_path('logs/wechatpay.log'),
	],
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