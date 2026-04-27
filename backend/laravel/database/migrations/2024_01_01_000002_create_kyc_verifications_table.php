<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kyc_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('method'); // aadhaar_xml, digilocker, pan_manual
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'expired']);
            $table->json('metadata')->nullable(); // non-PII metadata
            $table->float('face_match_score')->nullable();
            $table->boolean('signature_valid')->nullable();
            $table->string('xml_generated_date')->nullable();
            $table->string('failure_reason')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kyc_verifications');
    }
};
