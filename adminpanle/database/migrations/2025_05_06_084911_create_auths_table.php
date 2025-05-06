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
        Schema::create('auths', function (Blueprint $table) {
            $table->id('auth_id'); // Primary Key
            $table->unsignedBigInteger('user_id');
            $table->string('token', 512);
            $table->ipAddress('ip_address')->nullable();
            $table->string('device_type')->nullable();
            $table->string('location')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auths');
    }
};
