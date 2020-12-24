<?php
declare(strict_types=1);

namespace Fns;

class ProductCollection implements \Countable, \Iterator, \ArrayAccess
{
    private $products;
    private $position;

    public function __construct(array $productCandidates = [])
    {
        foreach ($productCandidates as $candidate) {
            $this->offsetSet('', new Product(json_decode(json_encode($candidate), true)));
        }
    }

    public function count()
    {
        return count($this->products);
    }

    public function current()
    {
        return $this->products[$this->position];
    }

    public function next()
    {
        $this->position++;
    }

    public function key()
    {
        return $this->position;
    }

    public function rewind() : void
    {
        $this->position = 0;
    }

    public function valid()
    {
        return isset($this->products[$this->position]);
    }

    public function offsetExists($offset) : bool
    {
        return isset($this->products[$offset]);
    }

    public function offsetGet($offset) : mixed
    {
        return $this->products[$offset];
    }

    public function offsetSet($offset, $product)
    {
        if (empty($offset)) {
            $this->products[] = $product;
        } else {
            $this->products[$offset] = $product;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->products[$offset]);
    }
}
