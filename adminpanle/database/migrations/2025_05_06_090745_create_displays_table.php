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
            $table->string('name'); 
            $table->string('displayMode'); 
            $table->string('timeZone');
            $table->json('tags'); 
            $table->boolean('status'); 
            $table->string('displayId'); 
            $table->foreignId('store_id')->constrained('stores'); 
            $table->foreignId('account_id')->constrained('accounts'); 
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
