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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('g_number')->nullable();
            $table->date('date')->nullable();
            $table->date('last_change_date')->nullable();
            $table->string('supplier_article',100)->nullable();
            $table->string('tech_size',100)->nullable();
            $table->bigInteger('barcode')->nullable();
            $table->string('total_price',100)->nullable();
            $table->integer('discount_percent')->nullable();
            $table->string('warehouse_name',100)->nullable();
            $table->string('oblast',100)->nullable();
            $table->bigInteger('income_id')->nullable();
            $table->string('odid',100)->nullable();
            $table->bigInteger('nm_id')->nullable();
            $table->string('subject',100)->nullable();
            $table->string('category',100)->nullable();
            $table->string('brand',100)->nullable();
            $table->boolean('is_cancel')->nullable();
            $table->date('cancel_dt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
