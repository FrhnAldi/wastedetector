<?php
// database/migrations/xxxx_create_detections_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detections', function (Blueprint $table) {
            $table->id();
            $table->string('user_uuid')->nullable()->index(); // ✅ ditambah
            $table->string('label');
            $table->enum('category', ['B3', 'Non-B3']);
            $table->decimal('confidence', 5, 4);
            $table->json('bbox')->nullable();
            $table->string('image_path')->nullable();
            $table->decimal('latitude',  10, 7)->nullable(); // ✅ ditambah
            $table->decimal('longitude', 10, 7)->nullable(); // ✅ ditambah
            $table->timestamps();

            $table->index('category');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detections');
    }
};