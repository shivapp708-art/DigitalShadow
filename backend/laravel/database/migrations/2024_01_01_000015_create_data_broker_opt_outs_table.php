<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_broker_opt_outs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('opt_out_url');
            $table->string('country', 5)->default('IN');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_broker_opt_outs');
    }
};
