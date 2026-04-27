<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('restrict');
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('verification_status', ['pending', 'dns_pending', 'file_pending', 'verified', 'rejected'])->default('pending');
            $table->string('verification_method')->nullable(); // dns_txt, http_file, cin, gstin
            $table->string('verification_token', 64)->nullable();
            $table->string('cin', 21)->nullable();
            $table->string('gstin', 15)->nullable();
            $table->string('primary_domain')->nullable();
            $table->json('additional_domains')->nullable();
            $table->enum('subscription_tier', ['free', 'starter', 'growth', 'enterprise'])->default('free');
            $table->integer('credits_balance')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
