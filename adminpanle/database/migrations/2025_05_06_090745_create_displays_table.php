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
            $table->string('displayId'); 
            $table->string('displayName'); 
            $table->string('tags');
            $table->string('store'); 
            $table->boolean('status')->default(true);
            $table->string('timeZone'); 
            $table->string('display'); 
            $table->string('country'); 
            $table->string('state'); 
            $table->string('city'); 
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
