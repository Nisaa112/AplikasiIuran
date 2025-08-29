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
        Schema::create('tipe_iuran', function (Blueprint $table){
            $table->id();
            $table->string('name')->unique();
            $table->text('deskripsi')->nullable();
            $table->decimal('nominal', 12, 2)->nullable();
            $table->enum('period', ['bulanan', 'tahunan', 'sekali', 'lainnya'])->default('bulanan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipe_iuran');
    }
};
