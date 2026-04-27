<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('scan_type', [
                'breach_email', 'breach_phone', 'username_enum',
                'name_scan', 'attack_surface', 'employee_exposure'
            ]);
            $table->enum('status', ['pending', 'running', 'completed', 'failed'])->default('pending');
            $table->string('target');
            $table->json('results')->nullable();
            $table->integer('credits_used')->default(1);
            $table->integer('risk_score')->nullable();
            $table->text('ai_summary')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'scan_type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scans');
    }
};
