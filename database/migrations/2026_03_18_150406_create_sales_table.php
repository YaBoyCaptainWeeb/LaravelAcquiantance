<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id()->index();
            $table->string('g_number')->nullable();
            $table->date('date')->nullable();
            $table->date('last_change_date')->nullable();
            $table->string('supplier_article',100)->nullable();
            $table->string('tech_size',100)->nullable();
            $table->bigInteger('barcode')->nullable();
            $table->string('total_price',100)->nullable();
            $table->string('discount_percent', 3)->nullable();
            $table->boolean('is_supply')->nullable();
            $table->boolean('is_realization')->nullable();
            $table->integer('promo_code_discount')->nullable();
            $table->string('warehouse_name',100)->nullable();
            $table->string('country_name',100)->nullable();
            $table->string('oblast_okrug_name',100)->nullable();
            $table->string('region_name',100)->nullable();
            $table->bigInteger('income_id')->nullable();
            $table->string('sale_id')->nullable();
            $table->string('odid',100)->nullable();
            $table->string('spp',100)->nullable();
            $table->string('for_pay',100)->nullable();
            $table->string('finished_price',100)->nullable();
            $table->string('price_with_disc',100)->nullable();
            $table->bigInteger('nm_id')->nullable();
            $table->string('subject',100)->nullable();
            $table->string('category',100)->nullable();
            $table->string('brand',100)->nullable();
            $table->boolean('is_storno')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
