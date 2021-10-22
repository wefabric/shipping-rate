<?php
/**
 * Created by SlickLabs - Wefabric.
 * User: nathanjansen <nathan@wefabric.nl>
 * Date: 26-03-18
 * Time: 11:54
 */

namespace Wefabric\ShippingRate;

use Illuminate\Support\ServiceProvider;

class ShippingRateServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/shipping_rates.php' => config_path('shipping_rates.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/shipping_rates.php', 'shipping_rates');

        $this->app->bind(ShippingRateManager::class, function () {
            $shippingRates = config('shipping_rates');
            return ShippingRateManagerFactory::create($shippingRates);
        });

        $this->app->alias(ShippingRateManager::class, 'shipping-rate-manager');
    }

}
