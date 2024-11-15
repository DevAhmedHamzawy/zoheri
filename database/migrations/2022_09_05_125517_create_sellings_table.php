<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellings', function (Blueprint $table) {
            $table->id();
            $table->integer('branch_id');
            $table->string('client_name');
            $table->string('client_tax_no')->nullable();
            $table->string('client_telephone')->nullable();
            $table->decimal('subtotal', 9 , 3);
            $table->decimal('vat', 9 , 3);
            $table->decimal('total', 9 , 3);
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
