<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remediation_templates', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('title');
            $table->enum('severity', ['critical', 'high', 'medium', 'low']);
            $table->json('steps');
            $table->integer('estimated_time_minutes')->default(15);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remediation_templates');
    }
};
