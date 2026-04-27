<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['purchase', 'usage', 'refund', 'bonus']);
            $table->integer('amount'); // positive = credit, negative = debit
            $table->integer('balance_after');
            $table->string('description')->nullable();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->foreignId('scan_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_transactions');
    }
};
