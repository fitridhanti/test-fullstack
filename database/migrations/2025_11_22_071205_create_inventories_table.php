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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('name_barang', 100);
            $table->string('serial_number')->unique()->nullable(); 
            $table->text('spesification')->nullable();
            $table->string('status'); 
            $table->integer('quantity')->default(0);
            $table->string('assign')->nullable(); 
            $table->string('department');

            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete(); 

            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
