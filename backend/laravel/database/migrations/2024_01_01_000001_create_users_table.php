<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('name')->nullable();
            $table->enum('trust_tier', ['guest', 'verified', 'kyc_lite', 'kyc3'])->default('guest');
            $table->enum('user_type', ['individual', 'org_member'])->default('individual');
            $table->integer('credits')->default(0);
            $table->string('stripe_customer_id')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
