<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_audit_records', function (Blueprint $table) {
            $table->id();
            $table->string('audit_number')->unique();
            $table->date('audit_date');
            $table->string('auditor_name');
            $table->string('warehouse_type')->default('raw_materials'); // raw_materials, packaging, finished_goods
            $table->string('status')->default('draft'); // draft, completed
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('stock_audit_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_audit_record_id')->constrained('stock_audit_records')->cascadeOnDelete();
            $table->string('lot_number');
            $table->string('item_code');
            $table->string('item_name');
            $table->decimal('system_quantity', 12, 3);
            $table->decimal('physical_quantity', 12, 3);
            $table->decimal('variance', 12, 3)->default(0);
            $table->string('unit')->default('KG');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_audit_items');
        Schema::dropIfExists('stock_audit_records');
    }
};
