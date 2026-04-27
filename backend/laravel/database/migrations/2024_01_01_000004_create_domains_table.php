<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->string('domain');
            $table->enum('status', ['pending', 'verified', 'failed'])->default('pending');
            $table->timestamp('last_scanned_at')->nullable();
            $table->json('scan_summary')->nullable();
            $table->timestamps();
            $table->unique(['organization_id', 'domain']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
