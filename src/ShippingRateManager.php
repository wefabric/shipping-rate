<?php
/**
 * Created by PhpStorm.
 * User: leoflapper
 * Date: 04/05/2018
 * Time: 10:12
 */

namespace Wefabric\ShippingRate;


use Wefabric\Countries\CountryManager;
use Wefabric\Countries\Exceptions\CountryNotFoundException;
use Mockery\Exception\InvalidArgumentException;

class ShippingRateManager
{

    protected $collection;


    /**
     * ShippingRateManager constructor.
     * @param array $countries
     * @throws CountryNotFoundException
     */
    public function __construct(array $countries)
    {
        $this->fromArray($countries);
    }

    public function addShippingRate($countryIso, ShippingRate $shippingRate)
    {
        $this->collection()->putCountry($countryIso, $shippingRate);
    }

    public function collection()
    {
        if(!$this->collection) {
            $this->collection = new CountryShippingRateCollection();
        }

        return $this->collection;
    }

    public function get(string $key = '')
    {
        if(!$key) {
            return $this->collection();
        }
        return $this->collection()->get($key);
    }

    /**
     * @param $countries
     * @throws CountryNotFoundException
     */
    public function fromArray($countries)
    {
        foreach($countries as $countryIso => $countryShippingRates) {

            foreach($countryShippingRates as $rate => $rateSettings) {

                $shippingRate = new ShippingRate($rate, $rateSettings['rate'], $rateSettings['label'], $countryIso);

                if(isset($rateSettings['free_shipping'])) {
                    $shippingRate->setFreeShippingThreshold($rateSettings['free_shipping']);
                }

                if(isset($rateSettings['description'])) {
                    $shippingRate->setDescription($rateSettings['description']);
                }

                if(isset($rateSettings['meta'])) {
                    $shippingRate->setMeta($rateSettings['meta']);
                }

                $this->addShippingRate($countryIso, $shippingRate);
            }

        }
    }
}
