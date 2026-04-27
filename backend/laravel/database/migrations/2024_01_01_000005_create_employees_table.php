<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->string('email');
            $table->string('name')->nullable();
            $table->string('department')->nullable();
            $table->string('role')->nullable();
            $table->boolean('monitoring_enabled')->default(true);
            $table->timestamp('last_scan_at')->nullable();
            $table->json('exposure_summary')->nullable();
            $table->timestamps();
            $table->unique(['organization_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
