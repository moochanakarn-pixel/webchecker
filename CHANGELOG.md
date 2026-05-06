# Checker KDS — Patch Notes

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
