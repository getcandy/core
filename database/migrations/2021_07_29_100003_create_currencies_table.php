<?php

use GetCandy\Base\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->prefix.'currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->decimal('exchange_rate', 10, 4);
            $table->string('format');
            $table->string('decimal_point');
            $table->string('thousand_point');
            $table->integer('decimal_places')->default(2)->index();
            $table->boolean('enabled')->default(0)->index();
            $table->boolean('default')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->prefix.'currencies');
    }
}
