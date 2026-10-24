-- =====================================================================
-- Natural Snacks Traceability Enterprise System
-- Database Dump for Hostinger MySQL / phpMyAdmin / cPanel
-- Generated: 2026-10-19
-- Target Database: MySQL 8.0+ / MariaDB 10.4+
-- Charset: utf8mb4 / Collation: utf8mb4_unicode_ci
-- =====================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `natural_snacks` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `natural_snacks`;


-- --------------------------------------------------------
-- Table structure for `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `name_ar` VARCHAR(255) NULL,
  `email` VARCHAR(191) NOT NULL,
  `email_verified_at` DATETIME NULL,
  `password` TEXT NOT NULL,
  `role` TEXT NOT NULL DEFAULT 'viewer',
  `department` TEXT NOT NULL DEFAULT 'management',
  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
  `remember_token` TEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `users`
INSERT INTO `users` (`id`, `name`, `name_ar`, `email`, `email_verified_at`, `password`, `role`, `department`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Top Management Admin', 'الإدارة العليا للنظام', 'admin@naturalsnacks.com', NULL, '$2y$12$kt4i0R.Jxqu/D6hBEv0wAeseQwzg5PGrSV/yOjR7.JvovgHJFIXce', 'admin', 'management', 1, 'FdiIBsm9AqHNiDPVeoIQ8OfuNlJPnzA4yGuvURh8tRgLcWOg4yDYgsBJ9sgs', '2026-10-19 21:34:35', '2026-10-19 21:34:35'),
(2, 'Eng. Production Manager', 'م. مدير الإنتاج والتصنيع', 'production@naturalsnacks.com', NULL, '$2y$12$d9lYJKVXVi6CHB0sz0q7L.2C5a01EavgXsbwvFhvwwnqKd4L56.M.', 'production_manager', 'production', 1, NULL, '2026-10-19 21:34:35', '2026-10-19 21:34:35'),
(3, 'Dr. Quality Assurance Lead', 'د. رئيس توكيد ومراقبة الجودة', 'qc@naturalsnacks.com', NULL, '$2y$12$OzpJNoGtdITMj0neWYiFz.YHXaSGDSzXgr6PbfX8ZtwKk7sre3uSq', 'quality_control', 'quality', 1, NULL, '2026-10-19 21:34:35', '2026-10-19 21:34:35'),
(4, 'Warehouse Supervisor', 'أمين ومشرف المستودعات', 'warehouse@naturalsnacks.com', NULL, '$2y$12$yRAiaxtnbCLkwZVsWr2UMuIvv7.wkHr.KO5W0CwQxxI583.MG4QhK', 'warehouse', 'warehouse', 1, NULL, '2026-10-19 21:34:35', '2026-10-19 21:34:35'),
(5, 'External & Internal Auditor', 'المدقق والمراجع المعتمد', 'auditor@naturalsnacks.com', NULL, '$2y$12$9UWQLQ0Y6p8Iy6h7TmAUyed0M4RY5BH8.kE5UEzIz7x7I2BtstNp6', 'viewer', 'audit', 1, NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36');

-- --------------------------------------------------------
-- Table structure for `password_reset_tokens`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` VARCHAR(191) NOT NULL,
  `token` TEXT NOT NULL,
  `created_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `sessions`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `user_id` BIGINT UNSIGNED NULL,
  `ip_address` TEXT NULL,
  `user_agent` TEXT NULL,
  `payload` TEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `product_categories`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `product_categories`;
CREATE TABLE `product_categories` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `code` VARCHAR(191) NULL,
  `name` VARCHAR(255) NOT NULL,
  `name_ar` VARCHAR(255) NULL,
  `description` TEXT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `product_categories`
INSERT INTO `product_categories` (`id`, `code`, `name`, `name_ar`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'CAT-01', 'Rice Cake', 'كعكة الأرز', 'كعكات أرز صحية منتفخة', 1, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(2, 'CAT-02', 'Granola Bar', 'بار الجرانولا', 'قوالب الجرانولا المغذية', 1, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(3, 'CAT-03', 'Mixed Nuts', 'مكسرات مشكلة', 'خلطات المكسرات الطبيعية المحمصة', 1, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(4, 'CAT-04', 'Dried Fruit', 'فاكهة مجففة', 'فواكه مجففة طبيعية بدون سكر مضاف', 1, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(5, 'CAT-05', 'Seed Mix', 'خلطة بذور', 'خلطات بذور صحية غنية بأوميغا', 1, '2026-10-19 21:34:36', '2026-10-19 21:34:36');

-- --------------------------------------------------------
-- Table structure for `products`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `product_code` TEXT NOT NULL,
  `sku` VARCHAR(191) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `name_ar` VARCHAR(255) NULL,
  `product_category_id` BIGINT UNSIGNED NULL,
  `product_type` TEXT NULL,
  `size` TEXT NULL,
  `unit` TEXT NOT NULL DEFAULT 'PCS',
  `description` TEXT NULL,
  `description_ar` TEXT NULL,
  `image_path` TEXT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
  `created_by` INT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `products`
INSERT INTO `products` (`id`, `product_code`, `sku`, `name`, `name_ar`, `product_category_id`, `product_type`, `size`, `unit`, `description`, `description_ar`, `image_path`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'RC-001', 'RC-MINI', 'Rice Cake Mini', 'كعكة الأرز صغيرة', 1, 'Snack', '30g', 'PCS', 'Mini crispy rice cakes - 100% natural and healthy snacks', 'كعكات أرز صغيرة مقرمشة - طبيعية 100% وخالية من المواد الحافظة', NULL, 1, 1, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(2, 'RC-002', 'RC-LARGE', 'Rice Cake Large', 'كعكة الأرز كبيرة', 1, 'Snack', '100g', 'PCS', 'Large natural rice cakes', 'كعكات أرز طبيعية كبيرة الحجم للعائلات', NULL, 1, 1, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(3, 'GB-001', 'GB-HONEY', 'Granola Bar Honey', 'بار الجرانولا بالعسل', 2, 'Bar', '40g', 'PCS', 'Honey glazed oats granola bar', 'بار الجرانولا بالشوفان والعسل الطبيعي', NULL, 1, 1, '2026-10-19 21:34:36', '2026-10-19 21:34:36');

-- --------------------------------------------------------
-- Table structure for `suppliers`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `code` VARCHAR(191) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `name_ar` VARCHAR(255) NULL,
  `contact_person` TEXT NULL,
  `email` VARCHAR(191) NULL,
  `phone` TEXT NULL,
  `address` TEXT NULL,
  `country` TEXT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
  `notes` TEXT NULL,
  `created_by` INT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `suppliers`
INSERT INTO `suppliers` (`id`, `code`, `name`, `name_ar`, `contact_person`, `email`, `phone`, `address`, `country`, `is_active`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'SUP-001', 'ABC Agricultural Trading', 'شركة ABC للتجارة الزراعية', 'Ahmed Hassan', 'ahmed@abc-agri.com', '+966501234567', 'Al-Kharj Agricultural City', 'Saudi Arabia', 1, NULL, 4, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(2, 'SUP-002', 'Global Grain Imports', 'استيراد الحبوب العالمية', 'Mohammed Ali', 'mali@globalgrains.com', '+971501234567', NULL, 'UAE', 1, NULL, 4, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(3, 'SUP-003', 'Natural Seeds Co.', 'شركة البذور الطبيعية', 'Sara Khalid', 'sara@naturalseeds.com', '+966551234567', NULL, 'Saudi Arabia', 1, NULL, 4, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(4, 'SUP-004', 'Premium Packaging Solutions', 'حلول التغليف المتميزة للصناعات الغذائية', 'John Smith', 'jsmith@ppkg.com', '+201001234567', NULL, 'Egypt', 1, NULL, 4, '2026-10-19 21:34:36', '2026-10-19 21:34:36');

-- --------------------------------------------------------
-- Table structure for `raw_materials`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `raw_materials`;
CREATE TABLE `raw_materials` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `code` VARCHAR(191) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `name_ar` VARCHAR(255) NULL,
  `material_type` TEXT NULL,
  `supplier_id` BIGINT UNSIGNED NULL,
  `unit` TEXT NOT NULL DEFAULT 'KG',
  `specification` TEXT NULL,
  `minimum_stock` DECIMAL(12, 3) NOT NULL DEFAULT '0',
  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
  `created_by` INT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `raw_materials`
INSERT INTO `raw_materials` (`id`, `code`, `name`, `name_ar`, `material_type`, `supplier_id`, `unit`, `specification`, `minimum_stock`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'RM-RICE-001', 'Rice', 'أرز أبيض غذائي ممتاز', 'Grain', 1, 'KG', 'Food grade grain, moisture < 14%, pure whole kernel', 100, 1, NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(2, 'RM-SALT-001', 'Salt', 'ملح طعام يودي مكرر', 'Additive', 2, 'KG', 'Food grade iodized pure sea salt', 50, 1, NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(3, 'RM-BS-001', 'Black Seed', 'حبة البركة العضوية', 'Seed', 3, 'KG', 'Nigella sativa, natural food grade seed', 20, 1, NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36');

-- --------------------------------------------------------
-- Table structure for `raw_material_lots`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `raw_material_lots`;
CREATE TABLE `raw_material_lots` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `lot_number` VARCHAR(191) NOT NULL,
  `barcode` TEXT NULL,
  `raw_material_id` BIGINT UNSIGNED NOT NULL,
  `supplier_id` BIGINT UNSIGNED NULL,
  `supplier_lot_number` TEXT NULL,
  `receiving_date` DATE NOT NULL,
  `production_date` DATE NULL,
  `expiry_date` DATE NULL,
  `quantity` DECIMAL(12, 3) NOT NULL,
  `available_quantity` DECIMAL(12, 3) NOT NULL,
  `in_production_quantity` DECIMAL(12, 3) NOT NULL DEFAULT '0',
  `unit` TEXT NOT NULL DEFAULT 'KG',
  `status` TEXT NOT NULL DEFAULT 'pending',
  `moisture_percentage` DECIMAL(12, 3) NULL,
  `notes` TEXT NULL,
  `created_by` INT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `raw_material_lots`
INSERT INTO `raw_material_lots` (`id`, `lot_number`, `barcode`, `raw_material_id`, `supplier_id`, `supplier_lot_number`, `receiving_date`, `production_date`, `expiry_date`, `quantity`, `available_quantity`, `in_production_quantity`, `unit`, `status`, `moisture_percentage`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'RICE-260920-005', 'BAR-RM-RICE-260920-005', 1, 1, 'ABC-RICE-2026-09-20', '2026-09-20 00:00:00', '2026-08-01 00:00:00', '2027-08-01 00:00:00', 500, 400, 100, 'KG', 'approved', 11.2, 'شحنة ممتازة مطابقة للمواصفات القياسية لنقاء الأرز', NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(2, 'SALT-260910-003', 'BAR-RM-SALT-260910-003', 2, 2, 'GG-SALT-090-2026', '2026-09-10 00:00:00', NULL, '2028-09-10 00:00:00', 200, 198, 0, 'KG', 'approved', 0.5, NULL, NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(3, 'BS-260915-002', 'BAR-RM-BS-260915-002', 3, 3, 'NS-BS-2026-09', '2026-09-15 00:00:00', '2026-08-15 00:00:00', '2027-08-15 00:00:00', 50, 49, 0, 'KG', 'approved', 4.8, NULL, NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36');

-- --------------------------------------------------------
-- Table structure for `packaging_materials`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `packaging_materials`;
CREATE TABLE `packaging_materials` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `code` VARCHAR(191) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `name_ar` VARCHAR(255) NULL,
  `material_type` TEXT NULL,
  `supplier_id` BIGINT UNSIGNED NULL,
  `unit` TEXT NOT NULL DEFAULT 'PCS',
  `specification` TEXT NULL,
  `minimum_stock` DECIMAL(12, 3) NOT NULL DEFAULT '0',
  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
  `created_by` INT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `packaging_materials`
INSERT INTO `packaging_materials` (`id`, `code`, `name`, `name_ar`, `material_type`, `supplier_id`, `unit`, `specification`, `minimum_stock`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'PM-BAG-001', 'Mini Product Bag 30g', 'أكياس تعبئة رقائق الأرز 30 جرام', 'Bag', 4, 'PCS', 'Food grade triplex foil with nitrogen barrier', 2000, 1, NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(2, 'PM-CTN-001', 'Master Carton Box 24x', 'كرتون شحن رئيسي سعة 24 عبوة', 'Carton', 4, 'PCS', 'Corrugated 5-layer heavy duty food carton', 100, 1, NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36');

-- --------------------------------------------------------
-- Table structure for `packaging_material_lots`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `packaging_material_lots`;
CREATE TABLE `packaging_material_lots` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `lot_number` VARCHAR(191) NOT NULL,
  `barcode` TEXT NULL,
  `packaging_material_id` BIGINT UNSIGNED NOT NULL,
  `supplier_id` BIGINT UNSIGNED NULL,
  `supplier_lot_number` TEXT NULL,
  `receiving_date` DATE NOT NULL,
  `quantity` DECIMAL(12, 3) NOT NULL,
  `available_quantity` DECIMAL(12, 3) NOT NULL,
  `in_production_quantity` DECIMAL(12, 3) NOT NULL DEFAULT '0',
  `unit` TEXT NOT NULL DEFAULT 'PCS',
  `status` TEXT NOT NULL DEFAULT 'pending',
  `notes` TEXT NULL,
  `created_by` INT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `packaging_material_lots`
INSERT INTO `packaging_material_lots` (`id`, `lot_number`, `barcode`, `packaging_material_id`, `supplier_id`, `supplier_lot_number`, `receiving_date`, `quantity`, `available_quantity`, `in_production_quantity`, `unit`, `status`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'BAG-260920-001', 'BAR-PM-BAG-260920-001', 1, 4, NULL, '2026-09-20 00:00:00', 10000, 9500, 0, 'PCS', 'approved', NULL, NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(2, 'CTN-260920-001', 'BAR-PM-CTN-260920-001', 2, 4, NULL, '2026-09-20 00:00:00', 500, 480, 0, 'PCS', 'approved', NULL, NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36');

-- --------------------------------------------------------
-- Table structure for `receiving_records`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `receiving_records`;
CREATE TABLE `receiving_records` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `receiving_number` TEXT NOT NULL,
  `receiving_type` TEXT NOT NULL DEFAULT 'raw_material',
  `supplier_id` BIGINT UNSIGNED NOT NULL,
  `receiving_date` DATE NOT NULL,
  `vehicle_number` TEXT NULL,
  `driver_name` VARCHAR(255) NULL,
  `received_by` TEXT NULL,
  `status` TEXT NOT NULL DEFAULT 'completed',
  `notes` TEXT NULL,
  `created_by` INT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `receiving_records`
INSERT INTO `receiving_records` (`id`, `receiving_number`, `receiving_type`, `supplier_id`, `receiving_date`, `vehicle_number`, `driver_name`, `received_by`, `status`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'GRN-00052', 'raw_material', 1, '2026-09-20 00:00:00', 'SAU-12345', 'Khaled Ibrahim', 'فريق استلام مستودع أ', 'completed', 'تم الفحص الظاهري وسلامة الشاحنة وقياس درجات الحرارة قبل التنزيل.', 4, '2026-10-19 21:34:36', '2026-10-19 21:34:36');

-- --------------------------------------------------------
-- Table structure for `receiving_items`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `receiving_items`;
CREATE TABLE `receiving_items` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `receiving_record_id` BIGINT UNSIGNED NOT NULL,
  `raw_material_id` BIGINT UNSIGNED NULL,
  `packaging_material_id` BIGINT UNSIGNED NULL,
  `lot_number` VARCHAR(191) NOT NULL,
  `supplier_lot_number` TEXT NULL,
  `quantity` DECIMAL(12, 3) NOT NULL,
  `unit` TEXT NOT NULL DEFAULT 'KG',
  `production_date` DATE NULL,
  `expiry_date` DATE NULL,
  `qc_status` TEXT NOT NULL DEFAULT 'pending',
  `accepted_quantity` DECIMAL(12, 3) NULL,
  `rejected_quantity` DECIMAL(12, 3) NULL,
  `notes` TEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `receiving_items`
INSERT INTO `receiving_items` (`id`, `receiving_record_id`, `raw_material_id`, `packaging_material_id`, `lot_number`, `supplier_lot_number`, `quantity`, `unit`, `production_date`, `expiry_date`, `qc_status`, `accepted_quantity`, `rejected_quantity`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 'RICE-260920-005', 'ABC-RICE-2026-09-20', 500, 'KG', '2026-08-01 00:00:00', '2027-08-01 00:00:00', 'approved', 500, 0, NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36');

-- --------------------------------------------------------
-- Table structure for `material_issue_orders`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `material_issue_orders`;
CREATE TABLE `material_issue_orders` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `issue_number` TEXT NOT NULL,
  `issue_date` DATE NOT NULL,
  `raw_material_lot_id` BIGINT UNSIGNED NOT NULL,
  `quantity` DECIMAL(12, 3) NOT NULL,
  `unit` TEXT NOT NULL DEFAULT 'KG',
  `target_line` TEXT NOT NULL DEFAULT 'Line 1',
  `issued_by` TEXT NULL,
  `received_by` TEXT NULL,
  `status` TEXT NOT NULL DEFAULT 'completed',
  `notes` TEXT NULL,
  `created_by` INT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `product_boms`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `product_boms`;
CREATE TABLE `product_boms` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `version` INT NOT NULL DEFAULT '1',
  `name` VARCHAR(255) NOT NULL,
  `name_ar` VARCHAR(255) NULL,
  `effective_date` DATE NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
  `notes` TEXT NULL,
  `created_by` INT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `product_boms`
INSERT INTO `product_boms` (`id`, `product_id`, `version`, `name`, `name_ar`, `effective_date`, `is_active`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Rice Cake Mini - Black Seed Formula', 'خلطة كعكة الأرز بحبة البركة والملح الطبيعي', '2026-01-01 00:00:00', 1, 'الوصفة الرسمية المعتمدة لخط الإنتاج 1', 2, '2026-10-19 21:34:36', '2026-10-19 21:34:36');

-- --------------------------------------------------------
-- Table structure for `product_bom_items`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `product_bom_items`;
CREATE TABLE `product_bom_items` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `product_bom_id` BIGINT UNSIGNED NOT NULL,
  `raw_material_id` BIGINT UNSIGNED NOT NULL,
  `quantity` DECIMAL(12, 3) NOT NULL,
  `unit` TEXT NOT NULL DEFAULT 'KG',
  `percentage` DECIMAL(12, 3) NULL,
  `notes` TEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `product_bom_items`
INSERT INTO `product_bom_items` (`id`, `product_bom_id`, `raw_material_id`, `quantity`, `unit`, `percentage`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 97, 'KG', 97, NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(2, 1, 2, 2, 'KG', 2, NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(3, 1, 3, 1, 'KG', 1, NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36');

-- --------------------------------------------------------
-- Table structure for `production_batches`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `production_batches`;
CREATE TABLE `production_batches` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `batch_id` VARCHAR(191) NOT NULL,
  `finished_goods_barcode` TEXT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `product_bom_id` BIGINT UNSIGNED NULL,
  `production_date` DATE NOT NULL,
  `production_start_time` DATETIME NULL,
  `production_end_time` DATETIME NULL,
  `shift` TEXT NOT NULL DEFAULT 'A',
  `production_line` TEXT NOT NULL DEFAULT 'Line 1',
  `production_supervisor` TEXT NULL,
  `planned_quantity` DECIMAL(12, 3) NOT NULL DEFAULT '0',
  `actual_quantity` DECIMAL(12, 3) NOT NULL DEFAULT '0',
  `cartons_produced` INT NOT NULL DEFAULT '0',
  `units_per_carton` INT NOT NULL DEFAULT '24',
  `accepted_quantity` DECIMAL(12, 3) NOT NULL DEFAULT '0',
  `rejected_quantity` DECIMAL(12, 3) NOT NULL DEFAULT '0',
  `waste_quantity` DECIMAL(12, 3) NOT NULL DEFAULT '0',
  `waste_unit` TEXT NOT NULL DEFAULT 'KG',
  `productivity_yield` DECIMAL(12, 3) NOT NULL DEFAULT '100',
  `scrap_percentage` DECIMAL(12, 3) NOT NULL DEFAULT '0',
  `transferred_to_warehouse` INT NOT NULL DEFAULT '0',
  `batch_status` TEXT NOT NULL DEFAULT 'draft',
  `qc_status` TEXT NOT NULL DEFAULT 'pending',
  `final_release_status` TEXT NOT NULL DEFAULT 'pending',
  `notes` TEXT NULL,
  `created_by` INT NULL,
  `updated_by` INT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `production_batches`
INSERT INTO `production_batches` (`id`, `batch_id`, `finished_goods_barcode`, `product_id`, `product_bom_id`, `production_date`, `production_start_time`, `production_end_time`, `shift`, `production_line`, `production_supervisor`, `planned_quantity`, `actual_quantity`, `cartons_produced`, `units_per_carton`, `accepted_quantity`, `rejected_quantity`, `waste_quantity`, `waste_unit`, `productivity_yield`, `scrap_percentage`, `transferred_to_warehouse`, `batch_status`, `qc_status`, `final_release_status`, `notes`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'RK-M-260925-A-001', 'FG-RK-M-260925-A-001', 1, 1, '2026-09-25 00:00:00', NULL, NULL, 'A', 'Line 1', 'م. أحمد الرشيد', 400, 398, 200, 24, 395, 3, 2, 'KG', 99.5, 0.5, 1, 'released', 'passed', 'released', 'تشغيل ممتاز ومطابق لسرعة وضغط قوالب خط الإنتاج 1', 2, NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(2, 'RK-M-260925-B-002', 'FG-RK-M-260925-B-002', 1, 1, '2026-09-25 00:00:00', NULL, NULL, 'B', 'Line 1', 'سامي يوسف', 300, 150, 75, 24, 150, 0, 1, 'KG', 99, 0, 0, 'in_production', 'in_progress', 'pending', NULL, 2, NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36');

-- --------------------------------------------------------
-- Table structure for `production_batch_materials`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `production_batch_materials`;
CREATE TABLE `production_batch_materials` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `production_batch_id` BIGINT UNSIGNED NOT NULL,
  `raw_material_lot_id` BIGINT UNSIGNED NOT NULL,
  `quantity_used` DECIMAL(12, 3) NOT NULL,
  `unit` TEXT NOT NULL DEFAULT 'KG',
  `notes` TEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `production_batch_materials`
INSERT INTO `production_batch_materials` (`id`, `production_batch_id`, `raw_material_lot_id`, `quantity_used`, `unit`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 97, 'KG', NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(2, 1, 2, 2, 'KG', NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(3, 1, 3, 1, 'KG', NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36');

-- --------------------------------------------------------
-- Table structure for `production_batch_packagings`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `production_batch_packagings`;
CREATE TABLE `production_batch_packagings` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `production_batch_id` BIGINT UNSIGNED NOT NULL,
  `packaging_material_lot_id` BIGINT UNSIGNED NOT NULL,
  `quantity_used` DECIMAL(12, 3) NOT NULL,
  `unit` TEXT NOT NULL DEFAULT 'PCS',
  `notes` TEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `production_batch_packagings`
INSERT INTO `production_batch_packagings` (`id`, `production_batch_id`, `packaging_material_lot_id`, `quantity_used`, `unit`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 4800, 'PCS', NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(2, 1, 2, 200, 'PCS', NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36');

-- --------------------------------------------------------
-- Table structure for `quality_inspections`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `quality_inspections`;
CREATE TABLE `quality_inspections` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `inspection_number` TEXT NOT NULL,
  `inspection_target` TEXT NOT NULL DEFAULT 'finished_goods',
  `production_batch_id` BIGINT UNSIGNED NULL,
  `raw_material_lot_id` BIGINT UNSIGNED NULL,
  `packaging_material_lot_id` BIGINT UNSIGNED NULL,
  `inspection_date` DATE NOT NULL,
  `inspector` TEXT NULL,
  `inspection_type` TEXT NOT NULL DEFAULT 'Final Release',
  `overall_result` TEXT NOT NULL DEFAULT 'hold',
  `approval_status` TEXT NOT NULL DEFAULT 'pending',
  `moisture_percentage` DECIMAL(12, 3) NULL,
  `notes` TEXT NULL,
  `created_by` INT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `quality_inspections`
INSERT INTO `quality_inspections` (`id`, `inspection_number`, `inspection_target`, `production_batch_id`, `raw_material_lot_id`, `packaging_material_lot_id`, `inspection_date`, `inspector`, `inspection_type`, `overall_result`, `approval_status`, `moisture_percentage`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'QC-2026-0001', 'finished_goods', 1, NULL, NULL, '2026-09-25 00:00:00', 'د. ليلى محمود (Quality Assurance Lead)', 'Final Release', 'pass', 'approved', 6.2, 'اجتازت الدفعة كافة الفحوصات الحسية والفيزيائية والميكروبيولوجية بنجاح تام وتم اعتماد الإفراج.', 3, '2026-10-19 21:34:36', '2026-10-19 21:34:36');

-- --------------------------------------------------------
-- Table structure for `quality_inspection_results`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `quality_inspection_results`;
CREATE TABLE `quality_inspection_results` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `quality_inspection_id` BIGINT UNSIGNED NOT NULL,
  `parameter` TEXT NOT NULL,
  `specification` TEXT NULL,
  `actual_result` TEXT NULL,
  `unit` TEXT NULL,
  `result` TEXT NOT NULL DEFAULT 'pass',
  `notes` TEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `quality_inspection_results`
INSERT INTO `quality_inspection_results` (`id`, `quality_inspection_id`, `parameter`, `specification`, `actual_result`, `unit`, `result`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'نسبة الرطوبة (Moisture %)', '< 8.0%', '6.2%', '%', 'pass', NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(2, 1, 'وزن القطعة الواحدة (Weight/Piece)', '30g ± 1g', '30.1g', 'g', 'pass', NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(3, 1, 'المظهر والقوام (Appearance & Texture)', 'مقرمش ومتجانس وخالي من العيوب', 'مقرمش ومثالي', NULL, 'pass', NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(4, 1, 'إحكام غلق الأكياس (Sealing Integrity)', 'محكم وخالي من التسريب مع ضخ نيتروجين', 'محكم 100%', NULL, 'pass', NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(5, 1, 'فحص السالمونيلا (Salmonella)', 'خالي تماماً / 25g', 'سلبي (Absent)', NULL, 'pass', NULL, '2026-10-19 21:34:36', '2026-10-19 21:34:36');

-- --------------------------------------------------------
-- Table structure for `stock_audit_records`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `stock_audit_records`;
CREATE TABLE `stock_audit_records` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `audit_number` TEXT NOT NULL,
  `audit_date` DATE NOT NULL,
  `auditor_name` VARCHAR(255) NOT NULL,
  `warehouse_type` TEXT NOT NULL DEFAULT 'raw_materials',
  `status` TEXT NOT NULL DEFAULT 'draft',
  `notes` TEXT NULL,
  `created_by` INT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `stock_audit_items`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `stock_audit_items`;
CREATE TABLE `stock_audit_items` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `stock_audit_record_id` BIGINT UNSIGNED NOT NULL,
  `lot_number` VARCHAR(191) NOT NULL,
  `item_code` TEXT NOT NULL,
  `item_name` VARCHAR(255) NOT NULL,
  `system_quantity` DECIMAL(12, 3) NOT NULL,
  `physical_quantity` DECIMAL(12, 3) NOT NULL,
  `variance` DECIMAL(12, 3) NOT NULL DEFAULT '0',
  `unit` TEXT NOT NULL DEFAULT 'KG',
  `notes` TEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `qc_notifications`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `qc_notifications`;
CREATE TABLE `qc_notifications` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `type` TEXT NOT NULL,
  `title` TEXT NOT NULL,
  `title_ar` TEXT NOT NULL,
  `message` TEXT NOT NULL,
  `message_ar` TEXT NOT NULL,
  `target_id` TEXT NOT NULL,
  `batch_or_lot_number` TEXT NOT NULL,
  `status` TEXT NOT NULL DEFAULT 'pending',
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `qc_notifications`
INSERT INTO `qc_notifications` (`id`, `type`, `title`, `title_ar`, `message`, `message_ar`, `target_id`, `batch_or_lot_number`, `status`, `created_at`, `updated_at`) VALUES
(1, 'finished_goods', 'فحص جودة دوري للدفعة RK-M-260925-B-002', 'فحص جودة دوري للدفعة RK-M-260925-B-002', 'الدفعة قيد التصنيع في الوردية B وتتطلب سحب عينة عشوائية لفحص الرطوبة الآن.', 'الدفعة قيد التصنيع في الوردية B وتتطلب سحب عينة عشوائية لفحص الرطوبة الآن.', '2', 'RK-M-260925-B-002', 'pending', '2026-10-19 21:34:36', '2026-10-19 21:34:36');

-- --------------------------------------------------------
-- Table structure for `documents`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `documents`;
CREATE TABLE `documents` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `document_type` TEXT NOT NULL,
  `title` TEXT NOT NULL,
  `file_name` VARCHAR(255) NOT NULL,
  `file_path` TEXT NOT NULL,
  `production_batch_id` BIGINT UNSIGNED NULL,
  `raw_material_lot_id` BIGINT UNSIGNED NULL,
  `receiving_record_id` BIGINT UNSIGNED NULL,
  `packaging_material_lot_id` BIGINT UNSIGNED NULL,
  `upload_date` DATE NOT NULL,
  `uploaded_by` INT NULL,
  `description` TEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `documents`
INSERT INTO `documents` (`id`, `document_type`, `title`, `file_name`, `file_path`, `production_batch_id`, `raw_material_lot_id`, `receiving_record_id`, `packaging_material_lot_id`, `upload_date`, `uploaded_by`, `description`, `created_at`, `updated_at`) VALUES
(1, 'coa', 'شهادة التحليل المخبري للأرز (COA - Rice Lot RICE-260920-005)', 'COA_Rice_Lot_260920_005.pdf', 'documents/coa_rice_sample.pdf', NULL, 1, NULL, NULL, '2026-09-20 00:00:00', 3, 'شهادة التحليل المعتمدة من مختبر المورد تثبت خلو الأرز من السموم الفطرية ومطابقته لمعايير الغذاء الدولية.', '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(2, 'qc_report', 'تقرير الإفراج النهائي للدفعة RK-M-260925-A-001', 'QC_Release_Report_RKM260925.pdf', 'documents/qc_release_sample.pdf', 1, NULL, NULL, NULL, '2026-09-25 00:00:00', 3, 'تقرير التحليل الشامل للمنتج النهائي واعتماد طرحه للتوزيع التجاري.', '2026-10-19 21:34:36', '2026-10-19 21:34:36');

-- --------------------------------------------------------
-- Table structure for `audit_logs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  `user_id` BIGINT UNSIGNED NULL,
  `user_email` TEXT NULL,
  `action` TEXT NOT NULL,
  `entity_type` TEXT NOT NULL,
  `entity_id` TEXT NULL,
  `old_value` TEXT NULL,
  `new_value` TEXT NULL,
  `ip_address` TEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `audit_logs`
INSERT INTO `audit_logs` (`id`, `user_id`, `user_email`, `action`, `entity_type`, `entity_id`, `old_value`, `new_value`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin@naturalsnacks.com', 'INITIALIZE', 'System', '1', NULL, '{"status":"Enterprise System Initialized"}', '127.0.0.1', '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(2, 3, 'qc@naturalsnacks.com', 'RELEASE', 'ProductionBatch', '1', NULL, '{"batch_id":"RK-M-260925-A-001","final_release_status":"released"}', '127.0.0.1', '2026-10-19 21:34:36', '2026-10-19 21:34:36'),
(3, 1, 'admin@naturalsnacks.com', 'QUICK_LOGIN', 'User', '1', NULL, '{"role":"admin"}', '127.0.0.1', '2026-10-19 22:10:36', '2026-10-19 22:10:36'),
(4, 1, 'admin@naturalsnacks.com', 'QUICK_LOGIN', 'User', '1', NULL, '{"role":"admin"}', '127.0.0.1', '2026-10-19 22:10:58', '2026-10-19 22:10:58'),
(5, 1, 'admin@naturalsnacks.com', 'LOGIN', 'User', '1', NULL, '{"email":"admin@naturalsnacks.com","role":"admin"}', '127.0.0.1', '2026-10-19 22:19:05', '2026-10-19 22:19:05'),
(6, 4, 'warehouse@naturalsnacks.com', 'QUICK_LOGIN', 'User', '4', NULL, '{"role":"warehouse"}', '127.0.0.1', '2026-10-19 22:19:29', '2026-10-19 22:19:29');

SET FOREIGN_KEY_CHECKS = 1;
