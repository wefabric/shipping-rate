<?php
/**
 * Created by SlickLabs - Wefabric.
 * User: nathanjansen <nathan@wefabric.nl>
 * Date: 27-03-18
 * Time: 11:19
 */

namespace Wefabric\ShippingRate;

use Illuminate\Support\Facades\Facade;

class ShippingRateManagerFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'shipping-rate-manager';
    }
}