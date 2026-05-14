# Checker KDS — Patch Notes

---

## v1.7.0 — 2026-05-14

### 🆕 Features
- **[Filter] กรอง SaleMode ต่อสถานี** — แต่ละ KDS เลือก SaleMode ที่ต้องการแสดงได้ผ่าน Settings → บันทึกลง `settings.local.php` → กรองฝั่ง server ก่อนส่งข้อมูล (ไม่เลือก = แสดงทั้งหมด)
- **[Filter] กรองโซนต่อสถานี (server-side)** — เพิ่ม zone filter ฝั่ง server (`allowed_zone_ids`) แยกจาก zone selector บน topbar — บันทึกลง `settings.local.php` ถาวร ไม่หายเมื่อ refresh
- **[Filter] Smart SaleMode + Zone logic** — เมื่อตั้งทั้ง 2 filter พร้อมกัน: `TableID > 0` (มีโต๊ะในโซน) ผ่านเสมอโดยไม่สนใจ SaleMode, `TableID = 0` (ไม่มีโต๊ะ) กรองด้วย SaleMode — รองรับกรณี TW ลงโต๊ะข้ามโซน

### 🐛 Bug Fixes
- **[UI] Filter chip double-toggle** — แก้ `<label>` + `<input type="checkbox">` toggle ซ้ำกัน (browser synthetic click + handler) ทำให้ chip ไม่บันทึกค่า — เพิ่ม `e.preventDefault()`
- **[Zone Lock] localStorage หาย → zone รีเซ็ตเป็น "ทั้งหมด"** — เมื่อ `zone_lock=ON` และ localStorage ถูกล้าง ระบบจะ auto-select zone แรกจาก `allowed_zone_ids` อัตโนมัติ (รองรับทั้งกรณี `kdsStartupCheck` หรือ `loadZones` เสร็จก่อน)

### ⚙️ API
- เพิ่ม action `list_sale_modes` — ดึง SaleMode ที่ `Deleted = 0` จาก DB
- `normalizeSystemSettingsPayload`, `systemSettingsSnapshot`, `writeSystemSettingsFile` รองรับ field `allowed_sale_mode_ids` และ `allowed_zone_ids` (เก็บเป็น PHP array ใน `settings.local.php`)
- เพิ่ม `parseIdList()` — แปลง comma-separated string หรือ array → array ของ int
- เพิ่ม `getStationFilter()` — โหลด filter ของสถานีจาก `settings.local.php` ตาม `kds_sub`
- `fetchActiveRows` / `fetchFinishedRows` เพิ่ม `LEFT JOIN tableno` เฉพาะเมื่อ zone filter ใช้งาน

---

## v1.6.0 — 2026-05-13

### 🆕 Features
- **[Settings] toggle ปุ่มสินค้าหมด** — เพิ่ม toggle "แสดงปุ่มสินค้าหมด" ในหน้าตั้งค่า บันทึกลง `settings.local.php` → ซ่อน/แสดงปุ่ม "ปิดสินค้าหมด" บนแถบเครื่องมือได้

### 🐛 Bug Fixes
- **[Startup] ตรวจสอบ DB ก่อน restore staff** — เปลี่ยนลำดับ startup ให้เช็คการเชื่อมต่อ DB ก่อน แล้วค่อย restore session พนักงาน ป้องกัน error loop เมื่อ DB ไม่พร้อม
- **[API] `finish_staff_id` validation ผิด** — แก้ validation จาก `<= 0` เป็น `< 0` เพื่อรองรับ `finish_staff_id = 0` (ไม่ระบุพนักงาน)
- **[API] CURDATE() timezone mismatch** — เปลี่ยนจาก MySQL `CURDATE()` เป็น PHP `date('Y-m-d')` ใน `fetchActiveRows()`, `fetchFinishedRows()`, และ `fetchOutOfStockRows()` เพื่อให้ใช้ timezone Asia/Bangkok ที่กำหนดใน config

### ⚙️ Config
- `ACTIVE_ROWS_TODAY_ONLY`: เปลี่ยนค่าเริ่มต้นเป็น `true` สำหรับ production
- `RECENT_FINISHED_LIMIT`: เปลี่ยนจาก 0 (ไม่จำกัด) เป็น 50

---

## v1.5.0 — 2026-05-06

### 🆕 Features
- **[Staff] บังคับ Login ก่อน Checkout** — ถ้ายังไม่ได้ล็อกอินพนักงาน ระบบจะแสดง notice "กรุณาเข้าสู่ระบบก่อน" และไม่อนุญาตให้ checkout ทั้ง 3 ช่องทาง (กดการ์ด, barcode, กล้อง)
- **[Zone] ล็อคโซน** — เพิ่ม toggle "ล็อคโซน" ในหน้าตั้งค่า บันทึกลง `settings.local.php` → ซ่อนปุ่มเปลี่ยนโซนบนหน้าหลัก ป้องกันเปลี่ยนโซนโดยไม่ตั้งใจ
- **[Zone] จำโซนข้ามรอบ refresh** — บันทึกโซนที่เลือกลง localStorage (`checker_zone_id`, `checker_zone_name`) และคืนค่าอัตโนมัติเมื่อโหลดหน้าใหม่

---

## v1.4.2 — 2026-05-06

### 🐛 Bug Fixes
- **[Security] SQL Injection ใน `tableExists()`** — เปลี่ยนจาก `SHOW TABLES LIKE` + `real_escape_string()` เป็น `information_schema.TABLES` + prepared statement
- **[API] `strtotime()` บนค่าว่าง** — เพิ่ม guard ใน `buildCheckoutPrintServerPayload()` กัน PHP warning เมื่อ `finishedAt` เป็น null หรือว่าง
- **[API] Missing `isset()`** — เพิ่ม `isset()` ก่อน access `SubmitOrderDateTime` และ `OrderDate` ใน insert row

---

## v1.4.1 — 2026-05-06

### 🐛 Bug Fixes
- **[Finished] รายการเสร็จแล้วสะสมไม่หาย** — เปิด `FINISHED_ROWS_TODAY_ONLY = true` ใน `config.php` เพื่อกรองเฉพาะรายการของวันนี้ ป้องกันตัวเลขในแถบขวาบนโตขึ้นเรื่อยๆ ตลอดวัน

---

## v1.4.0 — 2026-05-03

### 🆕 Features
- **SET menu display mode** — อ่านค่า `displayflexibleproductatchecker` จากตาราง `products` เพื่อควบคุมการแสดงผลกลุ่มสินค้า:
  - `= 1` → แสดงเฉพาะหัวเมนู (parent card), ลูกซ่อนทั้งหมด
  - `= 0` → แสดงเฉพาะลูก (child cards), หัวซ่อน (พฤติกรรมเดิม)

### 🐛 Bug Fixes
- **[SET menu] หัวเมนูโผล่กลับหลัง checkout ลูกครบ** — เพิ่ม 2 ชั้นป้องกัน:
  - Server-side: เมื่อ checkout ลูกตัวสุดท้าย → auto-checkout หัวอัตโนมัติใน DB
  - Display-side: ซ่อนหัว SET ที่ไม่มีลูก active เหลืออยู่ใน result

---

## v1.3.1 — 2026-05-02

### 🐛 Bug Fixes
- **[screen_lock] PHP 500 error** เมื่อ `api_checker.php` โหลด `screen_lock.php` — เกิดจาก IIFE syntax `(function(){})()` ที่ PHP บางเวอร์ชันไม่รองรับ แก้โดยเปลี่ยนเป็น named function `_slComputeBase()`

---

## v1.3.0 — 2026-05-02

### 🆕 Features
- **กล้องสแกนต่อเนื่อง** — เปิดกล้องค้างไว้และสแกน barcode อัตโนมัติทุก 800ms โดยไม่ต้องกดซ้ำ
- **เปิดกล้องอัตโนมัติ** เมื่อโหลดหน้าครั้งแรก (ถ้าเปิดใช้ barcode camera)
- **Multi-KDS subfolder** — รองรับการติดตั้ง KDS หลายจุดในโฟลเดอร์ย่อย (`KDS_TEMPLATE/`) บน IIS
- **jsQR library** — เพิ่ม `jsqr.min.js` สำรองสำหรับ browser ที่ไม่รองรับ BarcodeDetector API

### 🐛 Bug Fixes
- **[multi-KDS] settings บันทึกผิดที่** — แก้ path ของ `settings.local.php` ให้บันทึกในโฟลเดอร์ KDS ของตัวเอง ไม่ใช่ root
- **[multi-KDS] session ปนกัน** ระหว่าง KDS — เพิ่ม `kds_cid` (computer ID) เป็น parameter ทุก request
- **[multi-KDS] port validation** ใน settings form ไม่ทำงาน
- **[IIS] Base URL คำนวณผิด** — แก้การหา base path สำหรับ virtual application บน IIS
- **[IIS] API fetch URL ผิด** สำหรับ subfolder KDS
- **[MySQL] charset utf8mb4** ไม่รองรับใน MySQL เวอร์ชันเก่า — revert กลับเป็น `utf8`

---

## v1.2.0 — 2026-05-01

### 🆕 Features
- **QR code scanning** — เพิ่ม format `qr_code` ใน BarcodeDetector API สำหรับสแกน QR บน checkout

### 🐛 Bug Fixes
- แก้ bugs หลายจุดจาก local files ล่าสุด

---

## v1.1.0 — 2026-04-23

### 🆕 Features
- **Waiter Display** — หน้าจอแสดงคิวสำหรับพนักงาน (`waiter_display.php` + `api_waiter.php`)
  - ธีม light blue/white
  - Auth check ก่อนเข้าถึง
  - AJAX fetch พร้อม header ที่ถูกต้อง

---

## v1.0.1 — 2026-04-22

### 🐛 Bug Fixes
- **[API] PHP warnings ใน JSON response** — เพิ่ม output buffering ป้องกัน warning ปนออกมาใน response
- **[API] Error suppression** — ย้าย `@` error suppression ไปก่อน `require` statements

---

## v1.0.0 — 2026-04-22

### 🆕 Initial Setup
- ย้าย DB credentials ออกจาก code ไปไว้ใน `settings.local.php`
- เพิ่ม `.gitignore` — ไม่ track `settings.local.php` (มี password)
- ระบบ KDS พร้อมใช้งาน: `checker.php`, `api_checker.php`, `config.php`, `auth_check.php`, `screen_lock.php`
