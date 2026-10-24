<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialIssueOrder;
use App\Models\RawMaterialLot;
use App\Services\InventoryService;

class MaterialIssueController extends Controller
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request)
    {
        $query = MaterialIssueOrder::with(['rawMaterialLot.rawMaterial', 'rawMaterialLot.supplier']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('issue_number', 'like', "%{$s}%")
                  ->orWhere('target_line', 'like', "%{$s}%")
                  ->orWhere('issued_by', 'like', "%{$s}%");
        }

        $orders = $query->latest()->paginate(10)->withQueryString();
        $approvedLots = RawMaterialLot::with(['rawMaterial', 'supplier'])
            ->where('status', 'approved')
            ->where('available_quantity', '>', 0)
            ->get();

        return view('material_issues.index', compact('orders', 'approvedLots'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'raw_material_lot_id' => 'required|exists:raw_material_lots,id',
            'quantity' => 'required|numeric|min:0.01',
            'target_line' => 'required|string',
            'issued_by' => 'required|string',
            'received_by' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $order = $this->inventoryService->issueRawMaterial(
            (int)$validated['raw_material_lot_id'],
            (float)$validated['quantity'],
            $validated['target_line'],
            $validated['issued_by'],
            $validated['received_by'],
            $validated['notes'] ?? null
        );

        return redirect()->route('material-issues.index')
            ->with('success', "تم صرف الكمية بنجاح بموجب إذن الصرف {$order->issue_number} وخصم الرصيد تلقائياً من المستودع.");
    }
}
