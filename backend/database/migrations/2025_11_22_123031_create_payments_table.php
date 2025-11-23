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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_order_id')->constrained()->onDelete('cascade');
            $table->string('payment_method', 100);
            $table->decimal('amount', 10, 2);
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->timestamp('payment_date')->nullable();
            $table->string('invoice_path')->nullable();
            $table->timestamps();

            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
