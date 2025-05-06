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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // replaces Mongo _id
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('logo_id')->nullable();
            $table->string('name');
            $table->boolean('status')->default(true);
            $table->timestamps();

            // Foreign key constraints (optional)
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('logo_id')->references('id')->on('logos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
