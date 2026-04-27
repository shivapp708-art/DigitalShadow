<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain')->unique()->nullable();
            $table->string('cin')->unique()->nullable();
            $table->string('gstin')->unique()->nullable();
            $table->enum('verification_method', ['dns_txt', 'http_file', 'cin_gstin'])->nullable();
            $table->boolean('domain_verified')->default(false);
            $table->timestamp('domain_verified_at')->nullable();
            $table->enum('plan', ['starter', 'professional', 'enterprise'])->default('starter');
            $table->integer('credits')->default(0);
            $table->string('stripe_customer_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('organization_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['owner', 'admin', 'analyst', 'viewer'])->default('viewer');
            $table->timestamps();
            $table->unique(['organization_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_users');
        Schema::dropIfExists('organizations');
    }
};
