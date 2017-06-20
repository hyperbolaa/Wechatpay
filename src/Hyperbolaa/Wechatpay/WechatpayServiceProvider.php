<?php
namespace Hyperbolaa\Wechatpay;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

class WechatpayServiceProvider extends ServiceProvider
{

    /**
     * boot process
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
	protected function setupConfig()
	{
		$source_config = realpath(__DIR__ . '/../../config/config.php');

		if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
			$this->publishes([
				$source_config => config_path('wechatpay.php'),
			]);
		} elseif ($this->app instanceof LumenApplication) {
			$this->app->configure('wechatpay');
		}

		$this->mergeConfigFrom($source_config, 'wechatpay');
	}

    /**
     * Register the service provider.
     *
     * @return void
     */
	public function register()
	{
		$this->app->bind('wechatpay.app', function ($app)//app支付
		{
			$wechatpay = new Sdk\Apppay();
			$wechatpay->setAppId($app->config->get('wechatpay.app.app_id'))
				->setMerchantId($app->config->get('wechatpay.app.merchant_id'))
				->setKey($app->config->get('wechatpay.app.key'))
				->setNotifyUrl($app->config->get('wechatpay.app.notify_url'));
			return $wechatpay;
		});
		$this->app->bind('wechatpay.jsapi', function ($app)//公众号支付
		{
			$wechatpay = new Sdk\Jsapipay();
			$wechatpay->setAppId($app->config->get('wechatpay.jsapi.app_id'))
				->setMerchantId($app->config->get('wechatpay.jsapi.merchant_id'))
				->setKey($app->config->get('wechatpay.jsapi.key'))
				->setNotifyUrl($app->config->get('wechatpay.jsapi.notify_url'));
			return $wechatpay;
		});
		$this->app->bind('wechatpay.xcx', function ($app)//小程序支付 [和公众号支付类似]
		{
			$wechatpay = new Sdk\Jsapipay();
			$wechatpay->setAppId($app->config->get('wechatpay.app.app_id'))
				->setMerchantId($app->config->get('wechatpay.app.merchant_id'))
				->setKey($app->config->get('wechatpay.app.key'))
				->setNotifyUrl($app->config->get('wechatpay.app.notify_url'));
			return $wechatpay;
		});
	}

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'wechatpay.app',
            'wechatpay.jsapi',
            'wechatpay.xcx',
            'wechatpay.micropay',
            'wechatpay.native',
        ];
    }
}
