<?php
return [
	'jsapi'=>[
		'app_id'        => '',//微信支付分配的公众账号ID
		'merchant_id'   => '',//微信支付分配的商户号
		'key'           => '',//支付签名使用
		'notify_url'    => '',//异步接收微信支付结果通知的回调地址
		'device_info'   => 'WEB',//设备号
	],
	'app'=>[
		'app_id'        => '',
		'merchant_id'   => '',
		'key'           => '',
		'notify_url'    => '',
		'device_info'   => 'APP',
	],
];