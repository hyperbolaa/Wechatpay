<?php

namespace Hyperbolaa\Wechatpay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * APP支付
 * Class App
 * @package Hyperbolaa\Wechatpay\Facades
 */
class App extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'wechatpay.app';
    }
}
