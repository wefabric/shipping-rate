<?php
/**
 * Created by PhpStorm.
 * User: leoflapper
 * Date: 27/09/2018
 * Time: 10:13
 */

namespace Wefabric\ShippingRate;


interface ShippingRateInterface
{
    public function getRate(): float;

    public function getId();

    public function getCountry();

    public function freeShipping(): bool;

    public function hasFreeShippingThreshold(): bool;

    public function getFreeShippingThreshold(): float;

    public function getTaxRate(): float;

    public function toArray(): array;
}
