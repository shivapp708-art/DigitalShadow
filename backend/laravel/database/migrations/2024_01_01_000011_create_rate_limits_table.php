<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rate_limits', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->integer('hits')->default(0);
            $table->timestamp('reset_at');
            $table->timestamps();
            $table->index('reset_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_limits');
    }
};
