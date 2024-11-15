<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->index(['amount', 'sort', 'active']);
        });
        Schema::table('expenses', function (Blueprint $table) {
            $table->index(['active']);
        });
        Schema::table('vehicles', function (Blueprint $table) {
            $table->index(['active']);
        });
        Schema::table('hurdles', function (Blueprint $table) {
            $table->index(['active']);
        });
        Schema::table('models', function (Blueprint $table) {
            $table->index(['active']);
        });
        Schema::table('branches', function (Blueprint $table) {
            $table->index(['active']);
        });
        Schema::table('terms', function (Blueprint $table) {
            $table->index(['active']);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->foreign('hurdle_id')->references('id')->on('hurdles');
            $table->foreign('model_id')->references('id')->on('models');

            $table->index(['active', 'vehicle_id' , 'hurdle_id' , 'model_id']);
        });
        Schema::table('items', function (Blueprint $table) {
            $table->foreign('selling_id')->references('id')->on('sellings');
            $table->foreign('product_id')->references('id')->on('products');

            $table->index(['selling_id' , 'product_id']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
