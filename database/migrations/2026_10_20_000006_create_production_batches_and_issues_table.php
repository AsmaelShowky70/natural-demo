<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_issue_orders', function (Blueprint $table) {
            $table->id();
            $table->string('issue_number')->unique();
            $table->date('issue_date');
            $table->foreignId('raw_material_lot_id')->constrained('raw_material_lots')->cascadeOnDelete();
            $table->decimal('quantity', 12, 3);
            $table->string('unit')->default('KG');
            $table->string('target_line')->default('Line 1');
            $table->string('issued_by')->nullable();
            $table->string('received_by')->nullable();
            $table->string('status')->default('completed'); // completed, cancelled
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('production_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id')->unique();
            $table->string('finished_goods_barcode')->nullable()->index();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('product_bom_id')->nullable()->constrained('product_boms')->nullOnDelete();
            $table->date('production_date');
            $table->dateTime('production_start_time')->nullable();
            $table->dateTime('production_end_time')->nullable();
            $table->string('shift')->default('A'); // A, B, C, Night
            $table->string('production_line')->default('Line 1');
            $table->string('production_supervisor')->nullable();
            $table->decimal('planned_quantity', 12, 3)->default(0);
            $table->decimal('actual_quantity', 12, 3)->default(0);
            $table->integer('cartons_produced')->default(0);
            $table->integer('units_per_carton')->default(24);
            $table->decimal('accepted_quantity', 12, 3)->default(0);
            $table->decimal('rejected_quantity', 12, 3)->default(0);
            $table->decimal('waste_quantity', 12, 3)->default(0); // الهالك
            $table->string('waste_unit')->default('KG');
            $table->decimal('productivity_yield', 5, 2)->default(100.00); // %
            $table->decimal('scrap_percentage', 5, 2)->default(0.00); // %
            $table->boolean('transferred_to_warehouse')->default(false);
            $table->string('batch_status')->default('draft'); // draft, in_production, completed, qc_pending, released, on_hold, rejected, cancelled
            $table->string('qc_status')->default('pending'); // pending, in_progress, passed, failed, on_hold
            $table->string('final_release_status')->default('pending'); // pending, released, rejected, on_hold
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('production_batch_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_batch_id')->constrained('production_batches')->cascadeOnDelete();
            $table->foreignId('raw_material_lot_id')->constrained('raw_material_lots')->cascadeOnDelete();
            $table->decimal('quantity_used', 12, 3);
            $table->string('unit')->default('KG');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('production_batch_packagings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_batch_id')->constrained('production_batches')->cascadeOnDelete();
            $table->foreignId('packaging_material_lot_id')->constrained('packaging_material_lots')->cascadeOnDelete();
            $table->decimal('quantity_used', 12, 3);
            $table->string('unit')->default('PCS');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_batch_packagings');
        Schema::dropIfExists('production_batch_materials');
        Schema::dropIfExists('production_batches');
        Schema::dropIfExists('material_issue_orders');
    }
};
