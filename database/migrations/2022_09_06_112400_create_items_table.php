<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('selling_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->string('product_code');
            $table->integer('product_price');
            $table->text('vin');
            $table->integer('qty');
            $table->enum('discount_sort', ['نسبة' , 'مبلغ'])->nullable();
            $table->integer('discount');
            $table->decimal('sub_total', 9 , 3);
            $table->decimal('vat', 9 , 3);
            $table->decimal('total', 9 , 3);
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
        Schema::dropIfExists('items');
    }
}
