<?php

namespace GetCandy\Base;

use Illuminate\Support\Collection;

class ShippingModifiers
{
    /**
     * The collection of modifiers to use.
     *
     * @var \Illuminate\Support\Collection
     */
    protected Collection $modifiers;

    /**
     * Initialise the class.
     */
    public function __construct()
    {
        $this->modifiers = collect();
    }

    /**
     * Return the shipping modifiers.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getModifiers()
    {
        return $this->modifiers;
    }

    /**
     * Add a shipping modifier.
     *
     * @param $modifier Class reference to the modifier.
     *
     * @return void
     */
    public function add($modifier)
    {
        $this->modifiers->push($modifier);
    }
}
