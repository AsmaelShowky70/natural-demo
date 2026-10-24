<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionBatch;
use App\Models\RawMaterialLot;
use App\Models\PackagingMaterialLot;
use App\Services\BarcodeService;

class BarcodeController extends Controller
{
    public function index(Request $request)
    {
        $batches = ProductionBatch::with('product')->latest()->take(15)->get();
        $rawLots = RawMaterialLot::with('rawMaterial')->latest()->take(15)->get();
        $pkgLots = PackagingMaterialLot::with('packagingMaterial')->latest()->take(15)->get();

        return view('barcodes.index', compact('batches', 'rawLots', 'pkgLots'));
    }

    public function printLabel(Request $request)
    {
        $type = $request->query('type', 'batch');
        $code = $request->query('code', 'RK-M-260925-A-001');

        $barcodeSvg = BarcodeService::generateBarcodeSvg($code, 60);
        $publicTraceUrl = route('trace.public', $code);
        $qrSvg = BarcodeService::generateQrSvg($publicTraceUrl, 140);

        return view('barcodes.label', compact('type', 'code', 'barcodeSvg', 'qrSvg', 'publicTraceUrl'));
    }
}
