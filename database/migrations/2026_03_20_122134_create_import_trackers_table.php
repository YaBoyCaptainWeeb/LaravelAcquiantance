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
        Schema::create('import_trackers', function (Blueprint $table) {
            $table->id();
            $table->uuid('tracker_id')->unique();
            $table->string('entity'); // например 'sales', 'incomes'
            $table->integer('total_pages');
            $table->integer('processed_pages')->default(0);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->json('failed_pages')->nullable(); // номера страниц, которые не удались (если нужно)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_trackers');
    }
};
