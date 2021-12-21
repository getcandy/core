<?php

use GetCandy\Base\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartAddressesTable extends Migration
{
    public function up()
    {
        Schema::create($this->prefix.'cart_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('cart_id')->constrained($this->prefix.'carts');
            $table->foreignId('country_id')->nullable()->constrained($this->prefix.'countries');
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('line_one')->nullable();
            $table->string('line_two')->nullable();
            $table->string('line_three')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode')->nullable();
            $table->string('delivery_instructions')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('type')->default('shipping')->index();
            $table->string('shipping_option')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->prefix.'cart_addresses');
    }
}
