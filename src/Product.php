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

    public function price():int
    {
        return $this->price;
    }

    public function quantity() : int
    {
        return $this->quantity;
    }

    public function cost() : int
    {
        return $this->sum;
    }
}
