<?php

namespace GetCandy\Tests\Unit\Actions\Carts;

use GetCandy\DataTypes\Price as PriceDataType;
use GetCandy\DataTypes\ShippingOption;
use GetCandy\Exceptions\Carts\BillingAddressIncompleteException;
use GetCandy\Exceptions\Carts\BillingAddressMissingException;
use GetCandy\Facades\ShippingManifest;
use GetCandy\Models\Cart;
use GetCandy\Models\CartAddress;
use GetCandy\Models\Country;
use GetCandy\Models\Currency;
use GetCandy\Models\Order;
use GetCandy\Models\OrderAddress;
use GetCandy\Models\OrderLine;
use GetCandy\Models\Price;
use GetCandy\Models\ProductVariant;
use GetCandy\Models\TaxClass;
use GetCandy\Models\TaxRateAmount;
use GetCandy\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group getcandy.actions
 * @group getcandy.actions.carts
 */
class CreateOrderTest extends TestCase
{
    use RefreshDatabase;

    private $cart;

    public function setUp(): void
    {
        parent::setUp();

        $currency = Currency::factory()->create([
            'decimal_places' => 2,
        ]);

        $this->cart = Cart::factory()->create([
            'currency_id' => $currency->id,
        ]);

        $taxClass = TaxClass::factory()->create([
            'name' => 'Foobar',
        ]);

        $taxClass->taxRateAmounts()->create(
            TaxRateAmount::factory()->make([
                'percentage'   => 20,
                'tax_class_id' => $taxClass->id,
            ])->toArray()
        );

        $purchasable = ProductVariant::factory()->create([
            'tax_class_id'  => $taxClass->id,
            'unit_quantity' => 1,
        ]);

        Price::factory()->create([
            'price'          => 100,
            'tier'           => 1,
            'currency_id'    => $currency->id,
            'priceable_type' => get_class($purchasable),
            'priceable_id'   => $purchasable->id,
        ]);

        $this->cart->lines()->create([
            'purchasable_type' => get_class($purchasable),
            'purchasable_id'   => $purchasable->id,
            'quantity'         => 1,
        ]);
    }

    /** @test  */
    public function can_create_order()
    {
        $billing = CartAddress::factory()->make([
            'type'       => 'billing',
            'country_id' => Country::factory(),
            'first_name' => 'Santa',
            'line_one'   => '123 Elf Road',
            'city'       => 'Lapland',
            'postcode'   => 'BILL',
        ]);

        $shipping = CartAddress::factory()->make([
            'type'       => 'shipping',
            'country_id' => Country::factory(),
            'first_name' => 'Santa',
            'line_one'   => '123 Elf Road',
            'city'       => 'Lapland',
            'postcode'   => 'SHIPP',
        ]);

        $taxClass = TaxClass::factory()->create();

        $this->cart->addresses()->createMany([
            $billing->toArray(),
            $shipping->toArray(),
        ]);

        $shippingOption = new ShippingOption(
            description: 'Basic Delivery',
            identifier: 'BASDEL',
            price: new PriceDataType(500, $this->cart->currency, 1),
            taxClass: $taxClass
        );

        ShippingManifest::addOption($shippingOption);

        $this->cart->shippingAddress->update([
            'shipping_option' => $shippingOption->getIdentifier(),
        ]);

        $this->cart->shippingAddress->shippingOption = $shippingOption;

        $order = $this->cart->getManager()->createOrder();

        $breakdown = $this->cart->taxBreakdown->map(function ($tax) {
            return [
                'description'       => $tax['description'],
                'identifier'        => $tax['identifier'],
                'percentage'        => $tax['amounts']->sum('percentage'),
                'total'             => $tax['total']->value,
            ];
        })->values();

        $datacheck = [
            'user_id'            => $this->cart->user_id,
            'channel_id'         => $this->cart->channel_id,
            'status'             => config('getcandy.orders.draft_status'),
            'customer_reference' => null,
            'sub_total'          => $this->cart->subTotal->value,
            'total'              => $this->cart->total->value,
            'discount_total'     => $this->cart->discountTotal?->value,
            'shipping_total'     => $this->cart->shippingTotal?->value ?: 0,
            'tax_breakdown'      => json_encode($breakdown),
        ];

        $cart = $this->cart->refresh();

        $this->assertInstanceOf(Order::class, $cart->order);
        $this->assertEquals($order->id, $cart->order_id);
        $this->assertCount(1, $this->cart->lines);
        $this->assertCount(2, $order->lines);
        $this->assertCount(2, $cart->addresses);
        $this->assertCount(2, $order->addresses);
        $this->assertInstanceOf(OrderAddress::class, $order->shippingAddress);
        $this->assertInstanceOf(OrderAddress::class, $order->billingAddress);

        $this->assertDatabaseHas((new Order())->getTable(), $datacheck);
        $this->assertDatabaseHas((new OrderLine())->getTable(), [
            'identifier' => $shippingOption->getIdentifier(),
        ]);
    }

    /** @test */
    public function cannot_create_order_without_billing_address()
    {
        $this->expectException(BillingAddressMissingException::class);

        $this->cart->getManager()->createOrder();

        $this->assertNull($this->cart->refresh()->order_id);
        $this->assertInstanceOf(Order::class, $this->cart->refresh()->order);
    }

    /** @test */
    public function cannot_create_order_with_incomplete_billing_address()
    {
        $this->cart->addresses()->create([
            'type'     => 'billing',
            'postcode' => 'H0H 0H0',
        ]);

        $this->expectException(BillingAddressIncompleteException::class);

        $this->cart->getManager()->createOrder();

        $this->assertNull($this->cart->refresh()->order_id);
        $this->assertInstanceOf(Order::class, $this->cart->refresh()->order);
    }
}
