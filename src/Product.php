<?php
declare(strict_types=1);

namespace Fns;

class Product
{
    public function __construct(array $item)
    {
        foreach ($item as $property => $value) {
            $this->{$property} = $value;
        }
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getPrice():int
    {
        return $this->price;
    }

    public function getQuantity() : int
    {
        return $this->quantity;
    }

    public function getCost() : int
    {
        return $this->sum;
    }
}
