<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('document_type'); // coa, qc_report, production_report, receiving_certificate, supplier_certificate, packaging_certificate, audit_document, other
            $table->string('title');
            $table->string('file_name');
            $table->string('file_path');
            $table->foreignId('production_batch_id')->nullable()->constrained('production_batches')->nullOnDelete();
            $table->foreignId('raw_material_lot_id')->nullable()->constrained('raw_material_lots')->nullOnDelete();
            $table->foreignId('receiving_record_id')->nullable()->constrained('receiving_records')->nullOnDelete();
            $table->foreignId('packaging_material_lot_id')->nullable()->constrained('packaging_material_lots')->nullOnDelete();
            $table->date('upload_date');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_email')->nullable();
            $table->string('action'); // CREATE, UPDATE, DELETE, RELEASE, HOLD, ISSUE
            $table->string('entity_type'); // ProductionBatch, RawMaterialLot, QualityInspection, etc.
            $table->string('entity_id')->nullable();
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('documents');
    }
};
