<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
                $table->id();
                $table->foreignId('account_id')->constrained('user')->nullable();
                $table->string('originalname');
                $table->string('encoding');
                $table->string('mimetype');
                $table->integer('size');
                $table->string('path');
                $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
