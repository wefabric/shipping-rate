<?php
/**
 * Created by SlickLabs - Wefabric.
 * User: nathanjansen <nathan@wefabric.nl>
 * Date: 26-03-18
 * Time: 13:11
 */

namespace Wefabric\ShippingRate;

class ShippingRateManagerFactory
{
    /**
     * @var array[]
     */
    protected $items;

    public function __construct(array $config)
    {
        $this->items = $config;
    }

    /**
     * @param array $shippingRates
     * @return ShippingRateManager
     */
    public static function create(array $shippingRates): ShippingRateManager
    {
        return new ShippingRateManager($shippingRates);
    }
}