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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('image_name'); // e.g. "template1.jpg"
            $table->string('image_path'); // e.g. "uploads/template/template1.jpg"
            $table->string('html_name')->nullable(); // e.g. "template1.html"
            $table->string('html_path')->nullable(); // e.g. "uploads/template/template1.html"
            $table->dateTime('created_date')->nullable();
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index('image_name');
            $table->index('created_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
