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
        Schema::create('pushnotifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->text('content')->nullable();
            
            // Remove the default value for JSON columns
            $table->json('display_ids')->comment('Array of display IDs');
            $table->json('read_by')->comment('Array of user IDs who read this');
            
            $table->timestamps();
            
            $table->index('title');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pushnotifications');
    }
};
