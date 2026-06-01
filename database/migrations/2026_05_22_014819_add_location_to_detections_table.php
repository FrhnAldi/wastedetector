<?php
// database/migrations/xxxx_add_location_to_detections_table.php
// Jalankan: php artisan make:migration add_location_to_detections_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detections', function (Blueprint $table) {
            $table->decimal('latitude',  10, 7)->nullable()->after('image_path');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->float('gps_accuracy')->nullable()->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('detections', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'gps_accuracy']);
        });
    }
};