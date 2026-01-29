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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('office_location_id')->constrained()->cascadeOnDelete();
            $table->foreignId('qr_code_id')->constrained()->cascadeOnDelete();

            $table->date('attendance_date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();

            $table->decimal('scan_latitude', 10, 7);
            $table->decimal('scan_longitude', 10, 7);
            $table->integer('distance'); // meter

            $table->enum('status', ['IN_RADIUS', 'OUT_RADIUS']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
