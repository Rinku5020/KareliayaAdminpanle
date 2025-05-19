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
        Schema::create('verifycodes', function (Blueprint $table) {
            $table->id();
            $table->string('unique_code');
            $table->string('ip_address')->nullable();
            $table->string('ipv4_address')->nullable();
            $table->string('device_token')->nullable();
            $table->string('device_brand')->nullable();

            // Display as JSON or separate columns
            $table->json('display')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifycodes');
    }
};
