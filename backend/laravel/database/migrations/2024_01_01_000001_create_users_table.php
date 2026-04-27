<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 15)->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('name')->nullable();
            $table->enum('trust_tier', ['guest', 'verified', 'kyc_lite', 'kyc3'])->default('guest');
            $table->enum('account_type', ['individual', 'organization_admin', 'organization_member'])->default('individual');
            $table->string('subscription_tier')->default('free');
            $table->integer('credits_balance')->default(0);
            // KYC fields - NEVER store raw Aadhaar number
            $table->string('kyc_reference_id', 8)->nullable(); // last 4 digits of Aadhaar ref
            $table->string('kyc_name_hash', 64)->nullable(); // HMAC of verified name
            $table->string('kyc_dob_hash', 64)->nullable();
            $table->string('kyc_gender')->nullable();
            $table->string('kyc_state')->nullable();
            $table->boolean('kyc_face_verified')->default(false);
            $table->timestamp('kyc_verified_at')->nullable();
            $table->string('kyc_method')->nullable(); // aadhaar_xml, digilocker, pan_manual
            $table->string('pan_hash', 64)->nullable(); // HMAC of PAN, never raw
            $table->boolean('pan_aadhaar_linked')->nullable();
            // Auth
            $table->string('otp_hash')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->integer('otp_attempts')->default(0);
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['phone', 'trust_tier']);
            $table->index(['email', 'trust_tier']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
