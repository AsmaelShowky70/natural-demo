<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\ProductionBatch;
use App\Models\RawMaterialLot;
use App\Models\ReceivingRecord;
use App\Services\AuditService;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with(['uploader', 'batch', 'rawMaterialLot']);

        if ($request->filled('type')) {
            $query->where('document_type', $request->type);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('file_name', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        $documents = $query->latest('upload_date')->paginate(10)->withQueryString();

        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        $batches = ProductionBatch::latest()->take(20)->get();
        $lots = RawMaterialLot::latest()->take(20)->get();
        $receivings = ReceivingRecord::latest()->take(20)->get();

        return view('documents.create', compact('batches', 'lots', 'receivings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_type' => 'required|string',
            'title' => 'required|string|max:255',
            'production_batch_id' => 'nullable|exists:production_batches,id',
            'raw_material_lot_id' => 'nullable|exists:raw_material_lots,id',
            'receiving_record_id' => 'nullable|exists:receiving_records,id',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp,doc,docx|max:10240',
        ]);

        $filePath = 'documents/sample.pdf';
        $fileName = 'Document_' . time() . '.pdf';

        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $fileName = $uploadedFile->getClientOriginalName();
            $filePath = $uploadedFile->store('documents', 'public');
        }

        $doc = Document::create([
            'document_type' => $validated['document_type'],
            'title' => $validated['title'],
            'file_name' => $fileName,
            'file_path' => $filePath,
            'production_batch_id' => $validated['production_batch_id'] ?? null,
            'raw_material_lot_id' => $validated['raw_material_lot_id'] ?? null,
            'receiving_record_id' => $validated['receiving_record_id'] ?? null,
            'upload_date' => now()->toDateString(),
            'uploaded_by' => auth()->id(),
            'description' => $validated['description'] ?? null,
        ]);

        AuditService::log('UPLOAD_DOCUMENT', 'Document', (string)$doc->id, null, $doc->toArray());

        return redirect()->route('documents.index')
            ->with('success', 'تم حفظ ورفع المستند في الأرشيف الرقمي للمصنع.');
    }
}
