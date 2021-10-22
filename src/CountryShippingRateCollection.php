<?php
/**
 * Created by SlickLabs - Wefabric.
 * User: nathanjansen <nathan@wefabric.nl>
 * Date: 26-03-18
 * Time: 13:11
 */

namespace Wefabric\ShippingRate;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class CountryShippingRateCollection extends Collection implements Arrayable
{
    public function putCountry($countryIso, ShippingRate $shippingRate)
    {
        if(!$shippingRateCollection = $this->get($countryIso)) {
            $shippingRateCollection = new ShippingRateCollection();
        }

        $shippingRateCollection->put($shippingRate->getId(), $shippingRate);
        return $this->put($countryIso, $shippingRateCollection);
    }
}
