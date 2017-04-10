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
		$this->app->bind('wechatpay.jspai', function ($app)
		{
			$wechatpay = new Sdk\Jsapipay();
			$wechatpay->setAppId($app->config->get('wechatpay.jsapi.app_id'))
				->setMerchantId($app->config->get('wechatpay.jsapi.merchant_id'))
				->setKey($app->config->get('wechatpay.jsapi.key'))
				->setNotifyUrl($app->config->get('wechatpay.jsapi.notify_url'))
				->setDeviceInfo($app->config->get('wechatpay.jsapi.device_info'));
			return $wechatpay;
		});
		$this->app->bind('alisms.app', function ($app)
		{
			$wechatpay = new Sdk\Apppay();
			$wechatpay->setAppId($app->config->get('wechatpay.app.app_id'))
				->setMerchantId($app->config->get('wechatpay.app.merchant_id'))
				->setKey($app->config->get('wechatpay.app.key'))
				->setNotifyUrl($app->config->get('wechatpay.app.notify_url'))
				->setDeviceInfo($app->config->get('wechatpay.app.device_info'));
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
            'wechatpay.micropay',
            'wechatpay.native',
        ];
    }
}
