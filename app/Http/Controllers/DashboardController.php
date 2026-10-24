<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductionBatch;
use App\Models\RawMaterial;
use App\Models\RawMaterialLot;
use App\Models\PackagingMaterialLot;
use App\Models\QualityInspection;
use App\Models\ReceivingRecord;
use App\Models\MaterialIssueOrder;
use App\Models\QcNotification;
use App\Models\AuditLog;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $defaultTab = match($user->role) {
            'warehouse' => 'warehouse',
            'production_manager' => 'production',
            'quality_control' => 'quality',
            default => 'executive',
        };
        $tab = $request->query('tab', $defaultTab);

        // Executive General Stats
        $totalProducts = Product::where('is_active', true)->count();
        $totalBatches = ProductionBatch::count();
        $activeBatches = ProductionBatch::whereIn('batch_status', ['in_production', 'qc_pending'])->count();
        $releasedBatches = ProductionBatch::where('batch_status', 'released')->count();
        $onHoldBatches = ProductionBatch::where('batch_status', 'on_hold')->count();
        $totalCartonsProduced = ProductionBatch::sum('cartons_produced');
        $avgYield = ProductionBatch::where('productivity_yield', '>', 0)->avg('productivity_yield') ?? 98.8;
        $avgScrap = ProductionBatch::where('scrap_percentage', '>', 0)->avg('scrap_percentage') ?? 1.2;

        // Warehouse Stats
        $totalRawStockKg = RawMaterialLot::where('status', 'approved')->sum('available_quantity');
        $totalRawInProductionKg = RawMaterialLot::sum('in_production_quantity');
        $totalPackagingStock = PackagingMaterialLot::where('status', 'approved')->sum('available_quantity');
        $lowStockMaterials = RawMaterial::whereHas('lots', function ($q) {
            $q->where('status', 'approved');
        }, '<', 1)->orWhereDoesntHave('lots')->count();

        // Quality Stats
        $pendingQcCount = QcNotification::where('status', 'pending')->count();
        $totalInspections = QualityInspection::count();
        $passedInspections = QualityInspection::where('overall_result', 'pass')->count();
        $qcPassRate = $totalInspections > 0 ? round(($passedInspections / $totalInspections) * 100, 1) : 100;
        $avgRawMoisture = RawMaterialLot::whereNotNull('moisture_percentage')->avg('moisture_percentage') ?? 6.5;
        $avgFgMoisture = QualityInspection::whereNotNull('moisture_percentage')->avg('moisture_percentage') ?? 6.2;

        // Recent Data Lists
        $recentBatches = ProductionBatch::with(['product', 'inspections'])->latest()->take(6)->get();
        $recentReceivings = ReceivingRecord::with(['supplier', 'items'])->latest()->take(5)->get();
        $recentIssues = MaterialIssueOrder::with(['rawMaterialLot.rawMaterial'])->latest()->take(5)->get();
        $recentInspections = QualityInspection::with(['batch.product', 'rawMaterialLot'])->latest()->take(5)->get();
        $qcNotifications = QcNotification::where('status', 'pending')->latest()->take(5)->get();
        $recentAuditLogs = AuditLog::latest()->take(6)->get();

        // Production Chart Data (Last 7 Days)
        $dailyProduction = ProductionBatch::selectRaw("DATE(production_date) as date, SUM(actual_quantity) as total_kg, SUM(cartons_produced) as total_cartons")
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(7)
            ->get()
            ->reverse();

        return view('dashboard.index', compact(
            'tab',
            'totalProducts',
            'totalBatches',
            'activeBatches',
            'releasedBatches',
            'onHoldBatches',
            'totalCartonsProduced',
            'avgYield',
            'avgScrap',
            'totalRawStockKg',
            'totalRawInProductionKg',
            'totalPackagingStock',
            'lowStockMaterials',
            'pendingQcCount',
            'totalInspections',
            'qcPassRate',
            'avgRawMoisture',
            'avgFgMoisture',
            'recentBatches',
            'recentReceivings',
            'recentIssues',
            'recentInspections',
            'qcNotifications',
            'recentAuditLogs',
            'dailyProduction'
        ));
    }
}
