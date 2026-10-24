<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionBatch;
use App\Models\RawMaterialLot;
use App\Services\TraceabilityService;
use App\Services\BarcodeService;

class TraceabilityController extends Controller
{
    protected TraceabilityService $traceService;

    public function __construct(TraceabilityService $traceService)
    {
        $this->traceService = $traceService;
    }

    /**
     * Backward Traceability Dashboard
     */
    public function backward(Request $request)
    {
        $batchCode = $request->input('batch_code', 'RK-M-260925-A-001');
        $traceData = null;
        $error = null;
        $barcodeSvg = null;
        $qrSvg = null;

        if ($batchCode) {
            try {
                $traceData = $this->traceService->getBackwardTraceability($batchCode);
                $barcodeSvg = BarcodeService::generateBarcodeSvg($traceData['batch']->batch_id, 50);
                $qrSvg = BarcodeService::generateQrSvg(route('trace.public', $traceData['batch']->batch_id), 140);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        $allBatches = ProductionBatch::select('batch_id')->latest()->take(20)->get();

        return view('traceability.backward', compact('batchCode', 'traceData', 'error', 'allBatches', 'barcodeSvg', 'qrSvg'));
    }

    /**
     * Forward Traceability Dashboard
     */
    public function forward(Request $request)
    {
        $lotNumber = $request->input('lot_number', 'RICE-260920-005');
        $forwardData = null;
        $error = null;

        if ($lotNumber) {
            try {
                $forwardData = $this->traceService->getForwardTraceability($lotNumber);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        $allLots = RawMaterialLot::select('lot_number')->latest()->take(20)->get();

        return view('traceability.forward', compact('lotNumber', 'forwardData', 'error', 'allLots'));
    }

    /**
     * Crisis Food Safety Recall Simulator
     */
    public function recall(Request $request)
    {
        $lotNumber = $request->input('lot_number', 'RICE-260920-005');
        $recallData = null;
        $error = null;

        if ($lotNumber) {
            try {
                $recallData = $this->traceService->simulateRecall($lotNumber);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        $allLots = RawMaterialLot::select('lot_number')->latest()->take(20)->get();

        return view('traceability.recall', compact('lotNumber', 'recallData', 'error', 'allLots'));
    }

    /**
     * Public Consumer / Auditor Verification Page (No login required)
     */
    public function publicTrace(string $batchId)
    {
        try {
            $trace = $this->traceService->getBackwardTraceability($batchId);
            $barcodeSvg = BarcodeService::generateBarcodeSvg($trace['batch']->batch_id, 45);
            $qrSvg = BarcodeService::generateQrSvg(route('trace.public', $trace['batch']->batch_id), 120);

            return view('traceability.public', compact('trace', 'barcodeSvg', 'qrSvg'));
        } catch (\Exception $e) {
            return view('traceability.public_not_found', ['batchId' => $batchId]);
        }
    }
}
