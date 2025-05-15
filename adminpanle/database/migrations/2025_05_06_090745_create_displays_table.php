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
        Schema::create('displays', function (Blueprint $table) {
            $table->id();
            $table->string('tags')->nullable(); // Storing tags as JSON array
            $table->string('store_id');
            $table->string('account_id')->nullable();
            $table->enum('display_mode', ['landscape', 'portrait']);
            $table->boolean('status')->default(true);
            $table->string('display_id', 20)->unique();
            $table->string('name');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('time_zone');
             $table->string('address');
            $table->timestamps(); 
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('displays');
    }
};
