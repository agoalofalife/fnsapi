<?php
declare(strict_types=1);

namespace Fns;

use Carbon\Carbon;

class Receipt
{
    private $data;
    private $products;

    public function __construct(string $json)
    {
        $this->data = json_decode($json);
        $this->products = new ProductCollection($this->data->content->items);
    }

    public function getProducts():ProductCollection
    {
        return $this->products;
    }

    public function getAddress():?string
    {
        return $this->data->content->retailPlaceAddress ?? $this->data->address ?? null;
    }

    public function getShopName():?string
    {
        return $this->data->user ?? null;
    }

    public function getShopInn() :int
    {
        return $this->data->content->userInn;
    }

    public function getCashierName() :?string
    {
        return $this->data->content->operator ?? null;
    }

    public function getTotalSum():int
    {
        return $this->data->content->totalSum;
    }

    public function getKktRegId() :string
    {
        return $this->data->content->kktRegId;
    }

    public function getDataTimeString(): string
    {
        return Carbon::createFromTimestamp($this->data->content->dateTime, 'UTC')
                ->toDateTimeString();
    }
}
