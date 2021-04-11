<?php

namespace App\Dto;

abstract class BaseDto
{
    public function __construct(array $items = [])
    {
        if ($items) {
            $this->load($items);
        }
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function load(array $items)
    {
        foreach ($items as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
        return $this;
    }
}
