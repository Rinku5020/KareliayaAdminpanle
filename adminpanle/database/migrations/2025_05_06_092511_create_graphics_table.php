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
        Schema::create('graphics', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['image', 'video']);
            $table->unsignedBigInteger('media_id');
            $table->unsignedBigInteger('account_id');
            $table->boolean('status')->default(true);
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign keys
            $table->foreign('media_id')->references('id')->on('media');
            $table->foreign('account_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graphics');
    }
};
