<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receiving_records', function (Blueprint $table) {
            $table->id();
            $table->string('receiving_number')->unique();
            $table->string('receiving_type')->default('raw_material'); // raw_material, packaging_material
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->date('receiving_date');
            $table->string('vehicle_number')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('received_by')->nullable();
            $table->string('status')->default('completed'); // draft, completed, rejected, partial
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('receiving_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receiving_record_id')->constrained('receiving_records')->cascadeOnDelete();
            $table->foreignId('raw_material_id')->nullable()->constrained('raw_materials')->nullOnDelete();
            $table->foreignId('packaging_material_id')->nullable()->constrained('packaging_materials')->nullOnDelete();
            $table->string('lot_number');
            $table->string('supplier_lot_number')->nullable();
            $table->decimal('quantity', 12, 3);
            $table->string('unit')->default('KG');
            $table->date('production_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('qc_status')->default('pending'); // pending, approved, rejected, hold
            $table->decimal('accepted_quantity', 12, 3)->nullable();
            $table->decimal('rejected_quantity', 12, 3)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receiving_items');
        Schema::dropIfExists('receiving_records');
    }
};
