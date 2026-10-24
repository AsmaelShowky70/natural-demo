<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packaging_materials', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->string('material_type')->nullable(); // Bag, Jar, Lid, Label, Carton
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->string('unit')->default('PCS');
            $table->text('specification')->nullable();
            $table->decimal('minimum_stock', 12, 3)->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('packaging_material_lots', function (Blueprint $table) {
            $table->id();
            $table->string('lot_number')->unique();
            $table->string('barcode')->nullable()->index();
            $table->foreignId('packaging_material_id')->constrained('packaging_materials')->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->string('supplier_lot_number')->nullable();
            $table->date('receiving_date');
            $table->decimal('quantity', 12, 3);
            $table->decimal('available_quantity', 12, 3);
            $table->decimal('in_production_quantity', 12, 3)->default(0);
            $table->string('unit')->default('PCS');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packaging_material_lots');
        Schema::dropIfExists('packaging_materials');
    }
};
