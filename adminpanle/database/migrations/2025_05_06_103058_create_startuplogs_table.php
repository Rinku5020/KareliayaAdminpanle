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
        Schema::create('startuplogs', function (Blueprint $table) {
            $table->id();
            $table->string('instance_id')->unique(); // _id from JSON
            $table->string('hostname');
            $table->timestamp('start_time')->nullable();
            $table->string('start_time_local')->nullable();

            // cmdLine info
            $table->string('config')->nullable();
            $table->string('bind_ip')->nullable();
            $table->integer('port')->nullable();
            $table->boolean('service')->default(false);
            $table->string('db_path')->nullable();
            $table->string('log_path')->nullable();
            $table->boolean('log_append')->default(false);

            $table->unsignedBigInteger('pid')->nullable();

            // buildinfo
            $table->string('version')->nullable();
            $table->string('git_version')->nullable();
            $table->string('target_min_os')->nullable();
            $table->string('allocator')->nullable();
            $table->string('javascript_engine')->nullable();
            $table->json('version_array')->nullable();
            $table->string('openssl_running')->nullable();
            $table->json('modules')->nullable();
            $table->json('storage_engines')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('startuplogs');
    }
};
