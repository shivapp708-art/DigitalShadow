<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exposure_findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scan_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('set null');
            $table->string('finding_type'); // breach, paste, social_profile, domain_vuln, etc.
            $table->string('source'); // HaveIBeenPwned, GitHub, Shodan, etc.
            $table->enum('severity', ['critical', 'high', 'medium', 'low', 'info'])->default('medium');
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('raw_data')->nullable(); // Encrypted or hashed sensitive fields
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'severity', 'is_resolved']);
            $table->index(['organization_id', 'finding_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exposure_findings');
    }
};
