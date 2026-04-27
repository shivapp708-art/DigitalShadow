<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scan_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('set null');
            $table->string('scan_type'); // breach, username, name, attack_surface, employee
            $table->string('target'); // email/username/domain scanned
            $table->enum('status', ['queued', 'running', 'completed', 'failed'])->default('queued');
            $table->integer('credits_used')->default(0);
            $table->integer('findings_count')->default(0);
            $table->json('scan_config')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'scan_type', 'status']);
            $table->index(['organization_id', 'scan_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scan_sessions');
    }
};
