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
        Schema::create('playlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('theme_id');
            $table->string('playlist_name');
            $table->string('display_mode'); 
            $table->string('display_size');
            $table->boolean('display_status')->default(true);
            $table->string('static_address')->nullable();
            $table->string('schedule_type'); 
            $table->boolean('status')->default(true);
            $table->integer('unique_code')->unique();
            $table->unsignedBigInteger('logo_id')->nullable();
            
            // Recurring schedule as JSON
            $table->json('recurring')->nullable()->comment('Weekdays array');
            
            // Relationships as JSON arrays
            $table->json('stores')->nullable()->comment('Array of store IDs');
            $table->json('selected_displays')->nullable()->comment('Array of display IDs');
            
            // Zones as JSON
            $table->json('zone1')->nullable();
            $table->json('zone2')->nullable();
            $table->json('zone3')->nullable();
            $table->json('zone4')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('account_id');
            $table->index('theme_id');
            $table->index('logo_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlists');
    }
};
