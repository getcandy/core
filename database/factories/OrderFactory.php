<?php

namespace GetCandy\Database\Factories;

use GetCandy\Models\Channel;
use GetCandy\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $total = $this->faker->numberBetween(200, 25000);
        $taxTotal = ($total - 100) * .2;

        return [
            'channel_id'     => Channel::factory(),
            'user_id'        => null,
            'status'         => 'pending_payment',
            'reference'      => $this->faker->unique()->regexify('[A-Z]{8}'),
            'sub_total'      => $total - $taxTotal,
            'discount_total' => 0,
            'shipping_total' => 0,
            'tax_breakdown'  => [
                [
                    'description'       => 'VAT',
                    'total'             => 200,
                    'percentage'        => 20,
                ],
            ],
            'tax_total'             => $taxTotal,
            'total'                 => $total,
            'notes'                 => null,
            'currency_code'         => 'GBP',
            'compare_currency_code' => 'GBP',
            'exchange_rate'         => 1,
            'meta'                  => ['foo' => 'bar'],
        ];
    }
}
