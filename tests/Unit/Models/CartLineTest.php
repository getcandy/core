<?php

namespace GetCandy\Tests\Unit\Models;

use GetCandy\Exceptions\NonPurchasableItemException;
use GetCandy\Models\Cart;
use GetCandy\Models\CartLine;
use GetCandy\Models\Channel;
use GetCandy\Models\ProductVariant;
use GetCandy\Tests\Stubs\User;
use GetCandy\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group getcandy.cartlines
 */
class CartLineTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_make_a_cart_line()
    {
        $cart = Cart::factory()->create([
            'user_id' => User::factory(),
        ]);

        $data = [
            'cart_id'          => $cart->id,
            'quantity'         => 1,
            'purchasable_type' => ProductVariant::class,
            'purchasable_id'   => ProductVariant::factory()->create()->id,
        ];

        CartLine::create($data);

        $this->assertDatabaseHas((new CartLine())->getTable(), $data);
    }

    /** @test */
    public function only_purchasables_can_be_added_to_a_cart()
    {
        $cart = Cart::factory()->create([
            'user_id' => User::factory(),
        ]);

        $data = [
            'cart_id'          => $cart->id,
            'quantity'         => 1,
            'purchasable_type' => Channel::class,
            'purchasable_id'   => Channel::factory()->create()->id,
        ];

        $this->expectException(NonPurchasableItemException::class);

        CartLine::create($data);

        $this->assertDatabaseMissing((new CartLine())->getTable(), $data);
    }
}
