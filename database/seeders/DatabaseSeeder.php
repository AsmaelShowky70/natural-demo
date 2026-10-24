<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\RawMaterial;
use App\Models\RawMaterialLot;
use App\Models\PackagingMaterial;
use App\Models\PackagingMaterialLot;
use App\Models\ReceivingRecord;
use App\Models\ReceivingItem;
use App\Models\ProductBom;
use App\Models\ProductBomItem;
use App\Models\ProductionBatch;
use App\Models\ProductionBatchMaterial;
use App\Models\ProductionBatchPackaging;
use App\Models\QualityInspection;
use App\Models\QualityInspectionResult;
use App\Models\StockAuditRecord;
use App\Models\StockAuditItem;
use App\Models\QcNotification;
use App\Models\Document;
use App\Models\AuditLog;
use App\Services\BarcodeService;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        $admin = User::create([
            'name' => 'Top Management Admin',
            'name_ar' => 'الإدارة العليا للنظام',
            'email' => 'admin@naturalsnacks.com',
            'password' => Hash::make('Admin@123456'),
            'role' => 'admin',
            'department' => 'management',
            'is_active' => true,
        ]);

        $production = User::create([
            'name' => 'Eng. Production Manager',
            'name_ar' => 'م. مدير الإنتاج والتصنيع',
            'email' => 'production@naturalsnacks.com',
            'password' => Hash::make('Production@123456'),
            'role' => 'production_manager',
            'department' => 'production',
            'is_active' => true,
        ]);

        $qc = User::create([
            'name' => 'Dr. Quality Assurance Lead',
            'name_ar' => 'د. رئيس توكيد ومراقبة الجودة',
            'email' => 'qc@naturalsnacks.com',
            'password' => Hash::make('QC@123456'),
            'role' => 'quality_control',
            'department' => 'quality',
            'is_active' => true,
        ]);

        $warehouse = User::create([
            'name' => 'Warehouse Supervisor',
            'name_ar' => 'أمين ومشرف المستودعات',
            'email' => 'warehouse@naturalsnacks.com',
            'password' => Hash::make('Warehouse@123456'),
            'role' => 'warehouse',
            'department' => 'warehouse',
            'is_active' => true,
        ]);

        $auditor = User::create([
            'name' => 'External & Internal Auditor',
            'name_ar' => 'المدقق والمراجع المعتمد',
            'email' => 'auditor@naturalsnacks.com',
            'password' => Hash::make('Auditor@123456'),
            'role' => 'viewer',
            'department' => 'audit',
            'is_active' => true,
        ]);

        // 2. Product Categories
        $catRice = ProductCategory::create(['code' => 'CAT-01', 'name' => 'Rice Cake', 'name_ar' => 'كعكة الأرز', 'description' => 'كعكات أرز صحية منتفخة']);
        $catGranola = ProductCategory::create(['code' => 'CAT-02', 'name' => 'Granola Bar', 'name_ar' => 'بار الجرانولا', 'description' => 'قوالب الجرانولا المغذية']);
        $catNuts = ProductCategory::create(['code' => 'CAT-03', 'name' => 'Mixed Nuts', 'name_ar' => 'مكسرات مشكلة', 'description' => 'خلطات المكسرات الطبيعية المحمصة']);
        $catFruits = ProductCategory::create(['code' => 'CAT-04', 'name' => 'Dried Fruit', 'name_ar' => 'فاكهة مجففة', 'description' => 'فواكه مجففة طبيعية بدون سكر مضاف']);
        $catSeeds = ProductCategory::create(['code' => 'CAT-05', 'name' => 'Seed Mix', 'name_ar' => 'خلطة بذور', 'description' => 'خلطات بذور صحية غنية بأوميغا']);

        // 3. Products
        $pMini = Product::create([
            'product_code' => 'RC-001',
            'sku' => 'RC-MINI',
            'name' => 'Rice Cake Mini',
            'name_ar' => 'كعكة الأرز صغيرة',
            'product_category_id' => $catRice->id,
            'product_type' => 'Snack',
            'size' => '30g',
            'unit' => 'PCS',
            'description' => 'Mini crispy rice cakes - 100% natural and healthy snacks',
            'description_ar' => 'كعكات أرز صغيرة مقرمشة - طبيعية 100% وخالية من المواد الحافظة',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        $pLarge = Product::create([
            'product_code' => 'RC-002',
            'sku' => 'RC-LARGE',
            'name' => 'Rice Cake Large',
            'name_ar' => 'كعكة الأرز كبيرة',
            'product_category_id' => $catRice->id,
            'product_type' => 'Snack',
            'size' => '100g',
            'unit' => 'PCS',
            'description' => 'Large natural rice cakes',
            'description_ar' => 'كعكات أرز طبيعية كبيرة الحجم للعائلات',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        $pHoney = Product::create([
            'product_code' => 'GB-001',
            'sku' => 'GB-HONEY',
            'name' => 'Granola Bar Honey',
            'name_ar' => 'بار الجرانولا بالعسل',
            'product_category_id' => $catGranola->id,
            'product_type' => 'Bar',
            'size' => '40g',
            'unit' => 'PCS',
            'description' => 'Honey glazed oats granola bar',
            'description_ar' => 'بار الجرانولا بالشوفان والعسل الطبيعي',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        // 4. Suppliers
        $supAbc = Supplier::create([
            'code' => 'SUP-001',
            'name' => 'ABC Agricultural Trading',
            'name_ar' => 'شركة ABC للتجارة الزراعية',
            'contact_person' => 'Ahmed Hassan',
            'email' => 'ahmed@abc-agri.com',
            'phone' => '+966501234567',
            'address' => 'Al-Kharj Agricultural City',
            'country' => 'Saudi Arabia',
            'is_active' => true,
            'created_by' => $warehouse->id,
        ]);

        $supGlobal = Supplier::create([
            'code' => 'SUP-002',
            'name' => 'Global Grain Imports',
            'name_ar' => 'استيراد الحبوب العالمية',
            'contact_person' => 'Mohammed Ali',
            'email' => 'mali@globalgrains.com',
            'phone' => '+971501234567',
            'country' => 'UAE',
            'is_active' => true,
            'created_by' => $warehouse->id,
        ]);

        $supSeeds = Supplier::create([
            'code' => 'SUP-003',
            'name' => 'Natural Seeds Co.',
            'name_ar' => 'شركة البذور الطبيعية',
            'contact_person' => 'Sara Khalid',
            'email' => 'sara@naturalseeds.com',
            'phone' => '+966551234567',
            'country' => 'Saudi Arabia',
            'is_active' => true,
            'created_by' => $warehouse->id,
        ]);

        $supPkg = Supplier::create([
            'code' => 'SUP-004',
            'name' => 'Premium Packaging Solutions',
            'name_ar' => 'حلول التغليف المتميزة للصناعات الغذائية',
            'contact_person' => 'John Smith',
            'email' => 'jsmith@ppkg.com',
            'phone' => '+201001234567',
            'country' => 'Egypt',
            'is_active' => true,
            'created_by' => $warehouse->id,
        ]);

        // 5. Raw Materials
        $rmRice = RawMaterial::create([
            'code' => 'RM-RICE-001',
            'name' => 'Rice',
            'name_ar' => 'أرز أبيض غذائي ممتاز',
            'material_type' => 'Grain',
            'supplier_id' => $supAbc->id,
            'unit' => 'KG',
            'specification' => 'Food grade grain, moisture < 14%, pure whole kernel',
            'minimum_stock' => 100,
            'is_active' => true,
        ]);

        $rmSalt = RawMaterial::create([
            'code' => 'RM-SALT-001',
            'name' => 'Salt',
            'name_ar' => 'ملح طعام يودي مكرر',
            'material_type' => 'Additive',
            'supplier_id' => $supGlobal->id,
            'unit' => 'KG',
            'specification' => 'Food grade iodized pure sea salt',
            'minimum_stock' => 50,
            'is_active' => true,
        ]);

        $rmBlackSeed = RawMaterial::create([
            'code' => 'RM-BS-001',
            'name' => 'Black Seed',
            'name_ar' => 'حبة البركة العضوية',
            'material_type' => 'Seed',
            'supplier_id' => $supSeeds->id,
            'unit' => 'KG',
            'specification' => 'Nigella sativa, natural food grade seed',
            'minimum_stock' => 20,
            'is_active' => true,
        ]);

        // 6. Packaging Materials
        $pmBag = PackagingMaterial::create([
            'code' => 'PM-BAG-001',
            'name' => 'Mini Product Bag 30g',
            'name_ar' => 'أكياس تعبئة رقائق الأرز 30 جرام',
            'material_type' => 'Bag',
            'supplier_id' => $supPkg->id,
            'unit' => 'PCS',
            'specification' => 'Food grade triplex foil with nitrogen barrier',
            'minimum_stock' => 2000,
        ]);

        $pmCtn = PackagingMaterial::create([
            'code' => 'PM-CTN-001',
            'name' => 'Master Carton Box 24x',
            'name_ar' => 'كرتون شحن رئيسي سعة 24 عبوة',
            'material_type' => 'Carton',
            'supplier_id' => $supPkg->id,
            'unit' => 'PCS',
            'specification' => 'Corrugated 5-layer heavy duty food carton',
            'minimum_stock' => 100,
        ]);

        // 7. Raw Material Lots
        $lotRice = RawMaterialLot::create([
            'lot_number' => 'RICE-260920-005',
            'barcode' => 'BAR-RM-RICE-260920-005',
            'raw_material_id' => $rmRice->id,
            'supplier_id' => $supAbc->id,
            'supplier_lot_number' => 'ABC-RICE-2026-09-20',
            'receiving_date' => '2026-09-20',
            'production_date' => '2026-08-01',
            'expiry_date' => '2027-08-01',
            'quantity' => 500,
            'available_quantity' => 400,
            'in_production_quantity' => 100,
            'unit' => 'KG',
            'status' => 'approved',
            'moisture_percentage' => 11.2,
            'notes' => 'شحنة ممتازة مطابقة للمواصفات القياسية لنقاء الأرز',
        ]);

        $lotSalt = RawMaterialLot::create([
            'lot_number' => 'SALT-260910-003',
            'barcode' => 'BAR-RM-SALT-260910-003',
            'raw_material_id' => $rmSalt->id,
            'supplier_id' => $supGlobal->id,
            'supplier_lot_number' => 'GG-SALT-090-2026',
            'receiving_date' => '2026-09-10',
            'expiry_date' => '2028-09-10',
            'quantity' => 200,
            'available_quantity' => 198,
            'unit' => 'KG',
            'status' => 'approved',
            'moisture_percentage' => 0.5,
        ]);

        $lotBS = RawMaterialLot::create([
            'lot_number' => 'BS-260915-002',
            'barcode' => 'BAR-RM-BS-260915-002',
            'raw_material_id' => $rmBlackSeed->id,
            'supplier_id' => $supSeeds->id,
            'supplier_lot_number' => 'NS-BS-2026-09',
            'receiving_date' => '2026-09-15',
            'production_date' => '2026-08-15',
            'expiry_date' => '2027-08-15',
            'quantity' => 50,
            'available_quantity' => 49,
            'unit' => 'KG',
            'status' => 'approved',
            'moisture_percentage' => 4.8,
        ]);

        // 8. Packaging Lots
        $lotBags = PackagingMaterialLot::create([
            'lot_number' => 'BAG-260920-001',
            'barcode' => 'BAR-PM-BAG-260920-001',
            'packaging_material_id' => $pmBag->id,
            'supplier_id' => $supPkg->id,
            'receiving_date' => '2026-09-20',
            'quantity' => 10000,
            'available_quantity' => 9500,
            'unit' => 'PCS',
            'status' => 'approved',
        ]);

        $lotCtns = PackagingMaterialLot::create([
            'lot_number' => 'CTN-260920-001',
            'barcode' => 'BAR-PM-CTN-260920-001',
            'packaging_material_id' => $pmCtn->id,
            'supplier_id' => $supPkg->id,
            'receiving_date' => '2026-09-20',
            'quantity' => 500,
            'available_quantity' => 480,
            'unit' => 'PCS',
            'status' => 'approved',
        ]);

        // 9. Goods Receiving Record (GRN)
        $grn = ReceivingRecord::create([
            'receiving_number' => 'GRN-00052',
            'receiving_type' => 'raw_material',
            'supplier_id' => $supAbc->id,
            'receiving_date' => '2026-09-20',
            'vehicle_number' => 'SAU-12345',
            'driver_name' => 'Khaled Ibrahim',
            'received_by' => 'فريق استلام مستودع أ',
            'status' => 'completed',
            'notes' => 'تم الفحص الظاهري وسلامة الشاحنة وقياس درجات الحرارة قبل التنزيل.',
            'created_by' => $warehouse->id,
        ]);

        ReceivingItem::create([
            'receiving_record_id' => $grn->id,
            'raw_material_id' => $rmRice->id,
            'lot_number' => $lotRice->lot_number,
            'supplier_lot_number' => $lotRice->supplier_lot_number,
            'quantity' => 500,
            'unit' => 'KG',
            'production_date' => '2026-08-01',
            'expiry_date' => '2027-08-01',
            'qc_status' => 'approved',
            'accepted_quantity' => 500,
            'rejected_quantity' => 0,
        ]);

        // 10. Product BOM
        $bom = ProductBom::create([
            'product_id' => $pMini->id,
            'version' => 1,
            'name' => 'Rice Cake Mini - Black Seed Formula',
            'name_ar' => 'خلطة كعكة الأرز بحبة البركة والملح الطبيعي',
            'effective_date' => '2026-01-01',
            'is_active' => true,
            'notes' => 'الوصفة الرسمية المعتمدة لخط الإنتاج 1',
            'created_by' => $production->id,
        ]);

        ProductBomItem::create(['product_bom_id' => $bom->id, 'raw_material_id' => $rmRice->id, 'quantity' => 97, 'unit' => 'KG', 'percentage' => 97.0]);
        ProductBomItem::create(['product_bom_id' => $bom->id, 'raw_material_id' => $rmSalt->id, 'quantity' => 2, 'unit' => 'KG', 'percentage' => 2.0]);
        ProductBomItem::create(['product_bom_id' => $bom->id, 'raw_material_id' => $rmBlackSeed->id, 'quantity' => 1, 'unit' => 'KG', 'percentage' => 1.0]);

        // 11. Production Batches
        // Batch 1 (Primary Demo Batch from Requirements: RK-M-260925-A-001)
        $b1 = ProductionBatch::create([
            'batch_id' => 'RK-M-260925-A-001',
            'finished_goods_barcode' => 'FG-RK-M-260925-A-001',
            'product_id' => $pMini->id,
            'product_bom_id' => $bom->id,
            'production_date' => '2026-09-25',
            'shift' => 'A',
            'production_line' => 'Line 1',
            'production_supervisor' => 'م. أحمد الرشيد',
            'planned_quantity' => 400,
            'actual_quantity' => 398,
            'cartons_produced' => 200,
            'units_per_carton' => 24,
            'accepted_quantity' => 395,
            'rejected_quantity' => 3,
            'waste_quantity' => 2,
            'waste_unit' => 'KG',
            'productivity_yield' => 99.5,
            'scrap_percentage' => 0.5,
            'transferred_to_warehouse' => true,
            'batch_status' => 'released',
            'qc_status' => 'passed',
            'final_release_status' => 'released',
            'notes' => 'تشغيل ممتاز ومطابق لسرعة وضغط قوالب خط الإنتاج 1',
            'created_by' => $production->id,
        ]);

        // Link consumed lots to Batch 1
        ProductionBatchMaterial::create(['production_batch_id' => $b1->id, 'raw_material_lot_id' => $lotRice->id, 'quantity_used' => 97, 'unit' => 'KG']);
        ProductionBatchMaterial::create(['production_batch_id' => $b1->id, 'raw_material_lot_id' => $lotSalt->id, 'quantity_used' => 2, 'unit' => 'KG']);
        ProductionBatchMaterial::create(['production_batch_id' => $b1->id, 'raw_material_lot_id' => $lotBS->id, 'quantity_used' => 1, 'unit' => 'KG']);
        ProductionBatchPackaging::create(['production_batch_id' => $b1->id, 'packaging_material_lot_id' => $lotBags->id, 'quantity_used' => 4800, 'unit' => 'PCS']);
        ProductionBatchPackaging::create(['production_batch_id' => $b1->id, 'packaging_material_lot_id' => $lotCtns->id, 'quantity_used' => 200, 'unit' => 'PCS']);

        // Batch 2 (In production)
        $b2 = ProductionBatch::create([
            'batch_id' => 'RK-M-260925-B-002',
            'finished_goods_barcode' => 'FG-RK-M-260925-B-002',
            'product_id' => $pMini->id,
            'product_bom_id' => $bom->id,
            'production_date' => '2026-09-25',
            'shift' => 'B',
            'production_line' => 'Line 1',
            'production_supervisor' => 'سامي يوسف',
            'planned_quantity' => 300,
            'actual_quantity' => 150,
            'cartons_produced' => 75,
            'units_per_carton' => 24,
            'accepted_quantity' => 150,
            'waste_quantity' => 1,
            'productivity_yield' => 99.0,
            'batch_status' => 'in_production',
            'qc_status' => 'in_progress',
            'final_release_status' => 'pending',
            'created_by' => $production->id,
        ]);

        // 12. Quality Inspections
        $qi = QualityInspection::create([
            'inspection_number' => 'QC-2026-0001',
            'inspection_target' => 'finished_goods',
            'production_batch_id' => $b1->id,
            'inspection_date' => '2026-09-25',
            'inspector' => 'د. ليلى محمود (Quality Assurance Lead)',
            'inspection_type' => 'Final Release',
            'overall_result' => 'pass',
            'approval_status' => 'approved',
            'moisture_percentage' => 6.2,
            'notes' => 'اجتازت الدفعة كافة الفحوصات الحسية والفيزيائية والميكروبيولوجية بنجاح تام وتم اعتماد الإفراج.',
            'created_by' => $qc->id,
        ]);

        QualityInspectionResult::create(['quality_inspection_id' => $qi->id, 'parameter' => 'نسبة الرطوبة (Moisture %)', 'specification' => '< 8.0%', 'actual_result' => '6.2%', 'unit' => '%', 'result' => 'pass']);
        QualityInspectionResult::create(['quality_inspection_id' => $qi->id, 'parameter' => 'وزن القطعة الواحدة (Weight/Piece)', 'specification' => '30g ± 1g', 'actual_result' => '30.1g', 'unit' => 'g', 'result' => 'pass']);
        QualityInspectionResult::create(['quality_inspection_id' => $qi->id, 'parameter' => 'المظهر والقوام (Appearance & Texture)', 'specification' => 'مقرمش ومتجانس وخالي من العيوب', 'actual_result' => 'مقرمش ومثالي', 'result' => 'pass']);
        QualityInspectionResult::create(['quality_inspection_id' => $qi->id, 'parameter' => 'إحكام غلق الأكياس (Sealing Integrity)', 'specification' => 'محكم وخالي من التسريب مع ضخ نيتروجين', 'actual_result' => 'محكم 100%', 'result' => 'pass']);
        QualityInspectionResult::create(['quality_inspection_id' => $qi->id, 'parameter' => 'فحص السالمونيلا (Salmonella)', 'specification' => 'خالي تماماً / 25g', 'actual_result' => 'سلبي (Absent)', 'result' => 'pass']);

        // 13. QC Notification
        QcNotification::create([
            'type' => 'finished_goods',
            'title' => 'فحص جودة دوري للدفعة RK-M-260925-B-002',
            'title_ar' => 'فحص جودة دوري للدفعة RK-M-260925-B-002',
            'message' => 'الدفعة قيد التصنيع في الوردية B وتتطلب سحب عينة عشوائية لفحص الرطوبة الآن.',
            'message_ar' => 'الدفعة قيد التصنيع في الوردية B وتتطلب سحب عينة عشوائية لفحص الرطوبة الآن.',
            'target_id' => (string)$b2->id,
            'batch_or_lot_number' => $b2->batch_id,
            'status' => 'pending',
        ]);

        // 14. Documents (COA)
        Document::create([
            'document_type' => 'coa',
            'title' => 'شهادة التحليل المخبري للأرز (COA - Rice Lot RICE-260920-005)',
            'file_name' => 'COA_Rice_Lot_260920_005.pdf',
            'file_path' => 'documents/coa_rice_sample.pdf',
            'raw_material_lot_id' => $lotRice->id,
            'upload_date' => '2026-09-20',
            'uploaded_by' => $qc->id,
            'description' => 'شهادة التحليل المعتمدة من مختبر المورد تثبت خلو الأرز من السموم الفطرية ومطابقته لمعايير الغذاء الدولية.',
        ]);

        Document::create([
            'document_type' => 'qc_report',
            'title' => 'تقرير الإفراج النهائي للدفعة RK-M-260925-A-001',
            'file_name' => 'QC_Release_Report_RKM260925.pdf',
            'file_path' => 'documents/qc_release_sample.pdf',
            'production_batch_id' => $b1->id,
            'upload_date' => '2026-09-25',
            'uploaded_by' => $qc->id,
            'description' => 'تقرير التحليل الشامل للمنتج النهائي واعتماد طرحه للتوزيع التجاري.',
        ]);

        // 15. Audit Logs
        AuditLog::create([
            'user_id' => $admin->id,
            'user_email' => $admin->email,
            'action' => 'INITIALIZE',
            'entity_type' => 'System',
            'entity_id' => '1',
            'new_value' => ['status' => 'Enterprise System Initialized'],
            'ip_address' => '127.0.0.1',
        ]);

        AuditLog::create([
            'user_id' => $qc->id,
            'user_email' => $qc->email,
            'action' => 'RELEASE',
            'entity_type' => 'ProductionBatch',
            'entity_id' => (string)$b1->id,
            'new_value' => ['batch_id' => $b1->batch_id, 'final_release_status' => 'released'],
            'ip_address' => '127.0.0.1',
        ]);
    }
}
