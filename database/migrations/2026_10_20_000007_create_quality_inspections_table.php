<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quality_inspections', function (Blueprint $table) {
            $table->id();
            $table->string('inspection_number')->unique();
            $table->string('inspection_target')->default('finished_goods'); // finished_goods, raw_material, packaging
            $table->foreignId('production_batch_id')->nullable()->constrained('production_batches')->cascadeOnDelete();
            $table->foreignId('raw_material_lot_id')->nullable()->constrained('raw_material_lots')->nullOnDelete();
            $table->foreignId('packaging_material_lot_id')->nullable()->constrained('packaging_material_lots')->nullOnDelete();
            $table->date('inspection_date');
            $table->string('inspector')->nullable();
            $table->string('inspection_type')->default('Final Release'); // Incoming, In-Process, Final Release, Microbiological, Sensory
            $table->string('overall_result')->default('hold'); // pass, fail, hold
            $table->string('approval_status')->default('pending'); // pending, approved, rejected, on_hold
            $table->decimal('moisture_percentage', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('quality_inspection_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quality_inspection_id')->constrained('quality_inspections')->cascadeOnDelete();
            $table->string('parameter'); // Moisture, Weight, Appearance, Texture, Packaging, Salmonella, etc.
            $table->string('specification')->nullable();
            $table->string('actual_result')->nullable();
            $table->string('unit')->nullable();
            $table->string('result')->default('pass'); // pass, fail, na
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('qc_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // incoming_raw, incoming_packaging, finished_goods
            $table->string('title');
            $table->string('title_ar');
            $table->text('message');
            $table->text('message_ar');
            $table->string('target_id'); // lot_id or batch_id
            $table->string('batch_or_lot_number');
            $table->string('status')->default('pending'); // pending, inspected, dismissed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qc_notifications');
        Schema::dropIfExists('quality_inspection_results');
        Schema::dropIfExists('quality_inspections');
    }
};
