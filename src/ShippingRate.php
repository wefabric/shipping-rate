<?php
/**
 * Created by SlickLabs - Wefabric.
 * User: nathanjansen <nathan@wefabric.nl>
 * Date: 26-03-18
 * Time: 13:11
 */

namespace Wefabric\ShippingRate;

use Wefabric\ShippingRate\Meta\MetaCollection;
use Wefabric\ShippingRate\Meta\MetaItem;
use Wefabric\Countries\Countries\Country;
use Illuminate\Contracts\Support\Arrayable;
use Wefabric\Countries\CountryManager;

class ShippingRate implements ShippingRateInterface, Arrayable
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var float
     */
    protected float $rate = 0.00;


    /**
     * @var float|null
     */
    protected ?float $taxRate = null;


    /**
     * @var string
     */
    protected string $label = '';

    /**
     * @var string
     */
    protected string $description = '';

    /**
     * @var bool
     */
    protected bool $freeShipping = false;

    /**
     * @var float
     */
    protected float $freeShippingThreshold = 0.0;

    /**
     * @var string
     */
    protected string $countryId = '';

    /**
     * @var bool
     */
    protected bool $disabled = false;

    /**
     * @var string
     */
    protected string $disabledReason = '';

    /**
     * @var MetaCollection
     */
    protected ?MetaCollection $meta = null;


    public function __construct($id, $rate = '', $label = '', $countryId = '', $freeShippingThreshold = 0.0, $description = '', $disabled = false, $disabledReason = '', $meta = [])
    {
        if(is_array($id)) {
            $data = $id;
        } else {
            $data = [
                'id' => $id,
                'rate' => $rate,
                'label' => $label,
                'country_id' => $countryId,
                'free_shipping_threshold' => $freeShippingThreshold,
                'description' => $description,
                'disabled' => $disabled ?? false,
                'disabled_reason' => $disabledReason ?? '',
                'meta' => $meta
            ];

        }

        if(isset($data['title']) && !isset($data['label'])) {
            $data['label'] = $data['title'];
        }

        if(isset($data['free_threshold']) && !isset($data['free_shipping_threshold'])) {
            $data['free_shipping_threshold'] = $data['free_threshold'];
        }

        if($data['meta'] === null) {
            $data['meta'] = [];
        }

        $this->fromArray($data);

    }

    /**
     * Sets the instance from an array.
     *
     * @param $data array
     * @return void
     */
    public function fromArray(array $data): void
    {
        foreach ($data as $key => $value) {
            $setter = 'set' . str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $key)));
            if (method_exists($this, $setter)) {
                $this->{$setter}($value);
            }
        }
    }

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }

    /**
     * @param float $rate
     */
    public function setRate(float $rate)
    {
        $this->rate = $rate;
    }

    /**
     * @param float $price
     * @return bool
     */
    public function isFree(float $price)
    {
        if(!$this->hasFreeShippingThreshold()) {
            return false;
        }

        if($price >= $this->getFreeShippingThreshold()) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return null
     */
    public function getCountry(): Country
    {
        return \Countries::collection()->get($this->getCountryId());
    }

    /**
     * @return float
     */
    public function getTaxRate(): float
    {
        if(!$this->taxRate) {
            $this->taxRate = $this->getCountry()->getTaxRate();
        }
        return $this->taxRate;
    }

    /**
     * @param float $taxRate
     * @return float
     */
    public function setTaxRate(float $taxRate): float
    {
        return $this->taxRate = $taxRate;
    }

    /**
     * @return string
     */
    public function getCountryId(): string
    {
        if(!$this->countryId) {
            $this->setCountryId(app(CountryManager::class)->collection()->first()->getIso());
        }
        return $this->countryId;
    }

    /**
     * @param string $countryId
     */
    public function setCountryId(string $countryId): void
    {
        $this->countryId = $countryId;
    }

    public function freeShipping(): bool
    {
        return $this->freeShipping;
    }

    public function hasFreeShippingThreshold(): bool
    {
        return ($this->getFreeShippingThreshold() === 0.0) ? false : true;
    }

    public function getFreeShippingThreshold(): float
    {
        if (!$this->freeShippingThreshold) {
            $this->freeShippingThreshold = 0.0;
        }

        return $this->freeShippingThreshold;

    }

    public function setFreeShippingThreshold($freeShippingThreshold)
    {
        $this->freeShipping = false;
        if($freeShippingThreshold > 0.00) {
            $this->freeShipping = true;
        }

        $this->freeShippingThreshold = $freeShippingThreshold;

    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     */
    public function setDisabled(bool $disabled): void
    {
        $this->disabled = $disabled;
        if(false === $disabled) {
            $this->setDisabledReason('');
        }
    }

    /**
     * @return string
     */
    public function getDisabledReason(): string
    {
        return $this->disabledReason;
    }

    /**
     * @param string|null $disabledReason
     */
    public function setDisabledReason(string|null $disabledReason = ''): void
    {
        $this->disabledReason = (string)$disabledReason;
    }

    /**
     * @return MetaCollection
     */
    public function getMeta(): MetaCollection
    {
        if(!$this->meta) {
            $this->meta = new MetaCollection();
        }

        return $this->meta;
    }

    /**
     * @param MetaCollection $meta
     */
    public function setMeta($meta): void
    {
        if(is_array($meta)) {
            $metaCollection = new MetaCollection();
            foreach ($meta as $key => $value){
                $metaCollection->put($key, $value);
            }
            $meta = $metaCollection;
        }

        if(!$meta instanceof MetaCollection) {
            throw new \InvalidArgumentException('Meta must be either array or '.get_class(new MetaCollection()));
        }

        $this->meta = $meta;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            'description' => $this->getDescription(),
            'rate' => $this->getRate(),
            'freeShippingThreshold' => $this->getFreeShippingThreshold(),
            'country_id' => $this->getCountry()->getIso(),
            'tax_rate' => $this->getTaxRate(),
            'disabled' => $this->isDisabled(),
            'disabledReason' => $this->getDisabledReason(),
            'meta' => $this->getMeta()->toArray()
        ];
    }


}
