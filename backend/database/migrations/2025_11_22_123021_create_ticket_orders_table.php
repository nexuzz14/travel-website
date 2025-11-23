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
        Schema::create('ticket_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('travel_schedule_id')->constrained()->onDelete('cascade');
            $table->string('passenger_name');
            $table->string('passenger_phone', 20);
            $table->string('passenger_email');
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_orders');
    }
};
