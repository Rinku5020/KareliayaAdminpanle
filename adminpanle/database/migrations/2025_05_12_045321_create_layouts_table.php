<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('layouts', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->unique();
            $table->string('account_id');
            $table->string('layoutName');
            $table->string('store_id');
            $table->string('displayMode');
            $table->string('playlistName');
            $table->string('address');
            $table->string('logo');
            $table->string('selectedDisplays');
            $table->json('zone1')->nullable();
            $table->json('zone2')->nullable();
            $table->json('zone3')->nullable();
            $table->json('zone4')->nullable();
            $table->string('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layouts');
    }
};
