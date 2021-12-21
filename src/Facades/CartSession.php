<?php

namespace GetCandy\Facades;

use GetCandy\Base\CartSessionInterface;
use Illuminate\Support\Facades\Facade;

class CartSession extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return CartSessionInterface::class;
    }
}
