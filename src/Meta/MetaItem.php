<?php


namespace Wefabric\ShippingRate\Meta;


use Illuminate\Contracts\Support\Arrayable;

class MetaItem implements Arrayable
{

    public string $key;

    public $value;

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }


    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($value) {
            return $value instanceof Arrayable ? $value->toArray() : $value;
        }, get_object_vars($this));
    }
}
