<?php

namespace GetCandy\Actions\Carts;

use GetCandy\Base\Addressable;
use GetCandy\DataTypes\Price;
use GetCandy\Managers\TaxManager;
use GetCandy\Models\CartLine;
use Illuminate\Support\Collection;

class CalculateLine
{
    public function __construct(
        protected TaxManager $taxManager
    ) {
        //
    }

    /**
     * Execute the action.
     *
     * @param CartLine                                 $cartLine
     * @param \Illuminate\Database\Eloquent\Collection $customerGroups
     *
     * @return void
     */
    public function execute(
        CartLine $cartLine,
        Collection $customerGroups,
        Addressable $shippingAddress = null,
        Addressable $billingAddress = null
    ) {
        $purchasable = $cartLine->purchasable;
        $cart = $cartLine->cart;
        $unitQuantity = $purchasable->getUnitQuantity();

        $price = new Price(
            $purchasable->getPrice($cartLine->quantity, $cart->currency, $customerGroups),
            $cart->currency,
            $purchasable->getUnitQuantity()
        );

        $unitPrice = (int) (round(
            $price->decimal / $purchasable->getUnitQuantity(),
            $cart->currency->decimal_places
        ) * $cart->currency->factor);

        $subTotal = $unitPrice * $cartLine->quantity;

        $taxBreakDown = $this->taxManager->setShippingAddress($shippingAddress)
            ->setBillingAddress($billingAddress)
            ->setCurrency($cart->currency)
            ->setPurchasable($purchasable)
            ->getBreakdown($subTotal);

        $taxTotal = $taxBreakDown->sum('total.value');

        $cartLine->taxBreakdown = $taxBreakDown;
        $cartLine->subTotal = new Price($subTotal, $cart->currency, $unitQuantity);
        $cartLine->taxAmount = new Price($taxTotal, $cart->currency, $unitQuantity);
        $cartLine->total = new Price($subTotal + $taxTotal, $cart->currency, $unitQuantity);
        $cartLine->unitPrice = new Price($unitPrice, $cart->currency, $unitQuantity);
        $cartLine->discountTotal = new Price(0, $cart->currency, $unitQuantity);

        return $cartLine;
    }
}
