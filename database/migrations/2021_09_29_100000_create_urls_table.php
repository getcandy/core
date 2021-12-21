<?php

use GetCandy\Base\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlsTable extends Migration
{
    public function up()
    {
        Schema::create($this->prefix.'urls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('language_id')->constrained($this->prefix.'languages');
            $table->morphs('element');
            $table->string('slug')->index();
            $table->boolean('default')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->prefix.'urls');
    }
}
