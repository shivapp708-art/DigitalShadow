<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kyc_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('method', ['aadhaar_xml', 'digilocker', 'pan_manual'])->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('name_encrypted')->nullable();
            $table->text('dob_encrypted')->nullable();
            $table->text('gender_encrypted')->nullable();
            $table->string('aadhaar_ref_id_hash')->nullable();
            $table->string('pan_hash')->nullable();
            $table->string('email_hash')->nullable();
            $table->string('phone_hash')->nullable();
            $table->decimal('face_match_score', 4, 3)->nullable();
            $table->boolean('liveness_passed')->default(false);
            $table->timestamp('xml_generated_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kyc_profiles');
    }
};
