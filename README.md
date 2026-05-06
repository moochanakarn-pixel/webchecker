# Checker KDS

ระบบ Kitchen Display System (KDS) สำหรับแสดงคิวอาหารในครัว รองรับการ checkout, สแกนบาร์โค้ด และควบคุมสินค้าหมด

**เวอร์ชันปัจจุบัน:** v1.5.0

---

## โครงสร้างไฟล์

| ไฟล์ | หน้าที่ |
|---|---|
| `checker.php` | หน้าหลัก UI ของระบบ KDS |
| `api_checker.php` | API backend สำหรับดึงและอัปเดตข้อมูล |
| `config.php` | ค่า configuration ของระบบ |
| `auth_check.php` | ตรวจสอบสิทธิ์การเข้าถึง |
| `screen_lock.php` | หน้า screen lock |
| `screen_lock.js` | Logic การ lock/unlock หน้าจอ |
| `settings.local.php` | ค่า local เฉพาะเครื่อง (DB credentials ฯลฯ) |
| `KDS_TEMPLATE/` | Template สำหรับติดตั้ง KDS หลายจุด (subfolder) |

---

## ความต้องการของระบบ

- PHP รองรับ `mysqli`
- MySQL 5.1 ขึ้นไป
- Browser รองรับ BarcodeDetector API (Chrome/Edge) หรือใช้ jsQR fallback

---

## การตั้งค่า

แก้ไขค่าใน `settings.local.php`:

```php
return [
    'db_host'     => '127.0.0.1',
    'db_port'     => 3306,
    'db_name'     => 'ชื่อฐานข้อมูล',
    'db_user'     => 'username',
    'db_pass'     => 'password',
    'shop_id'     => 1,
    'current_computer_id' => 1,
];
```

ค่าสำคัญใน `config.php`:

| ค่า | Default | ความหมาย |
|---|---|---|
| `APP_REFRESH_MS` | `15000` | รีเฟรชคิวทุก 15 วินาที |
| `FINISHED_REFRESH_EVERY` | `3` | โหลดรายการเสร็จทุก 3 รอบ (45 วิ) |
| `FINISHED_ROWS_TODAY_ONLY` | `true` | แสดงรายการเสร็จเฉพาะวันนี้ |
| `RECENT_FINISHED_LIMIT` | `0` | จำนวนรายการเสร็จสูงสุด (0 = ไม่จำกัด) |
| `ENABLE_BARCODE_CHECKOUT` | `true` | เปิดใช้สแกนบาร์โค้ด |

---

## ฟีเจอร์หลัก

- **แสดงคิวอาหาร** — แสดง order ที่รอทำและกำลังทำอยู่แบบ real-time
- **Checkout** — กดยืนยันรายการเสร็จแล้ว พร้อม 2-step checkout option
- **Undo** — ย้อนกลับรายการที่ checkout ผิด
- **Barcode scan** — สแกนบาร์โค้ดจากคีย์บอร์ดหรือกล้อง
- **กล้องสแกนต่อเนื่อง** — เปิดกล้องค้างและสแกนอัตโนมัติทุก 800ms
- **SET menu** — รองรับการแสดงเมนูชุด (parent/child cards)
- **สินค้าหมด** — ควบคุมสถานะสินค้าหมดได้จากหน้าจอ
- **Sound alert** — แจ้งเตือนเสียงเมื่อมี order ใหม่
- **Screen lock** — ล็อคหน้าจอเมื่อไม่ใช้งาน รองรับหลายเครื่องพร้อมกัน
- **Multi-KDS** — ติดตั้งหลายจุดในโฟลเดอร์ย่อย (`KDS_TEMPLATE/`) บน IIS
- **Staff login บน topbar** — กรอกรหัสพนักงานได้จากหน้าหลัก บังคับ login ก่อน checkout ทุกช่องทาง
- **Zone lock** — ล็อคโซนไม่ให้เปลี่ยนโดยไม่ตั้งใจ ตั้งค่าผ่าน settings modal; จำโซนที่เลือกข้ามรอบ refresh

---

## สถานะบัคปัจจุบัน

### ✅ แก้แล้ว

| เวอร์ชัน | รายการ |
|---|---|
| v1.5.0 | บังคับ login ก่อน checkout |
| v1.5.0 | Zone lock — ซ่อนปุ่มเปลี่ยนโซนเมื่อ lock |
| v1.5.0 | จำโซนข้ามรอบ refresh ด้วย localStorage |
| v1.4.2 | SQL injection ใน `tableExists()` |
| v1.4.2 | `strtotime()` บนค่า null |
| v1.4.2 | Missing `isset()` ใน insert row |
| v1.4.1 | รายการเสร็จสะสมไม่หาย (เปิด `FINISHED_ROWS_TODAY_ONLY`) |
| v1.4.x | Refresh loop หยุดเมื่อ API error |
| v1.4.x | กล้อง barcode เปิดซ้อนกัน |
| v1.4.x | Render sold out modal หลังปิดไปแล้ว |
| v1.4.x | Notice หายเร็วเกินไป |

### ⚠️ ค้างอยู่ (กำหนดแก้รอบถัดไป)

| ความเสี่ยง | รายการ |
|---|---|
| 🟠 กลาง | กด Checkout เร็วๆ 2 ครั้ง อาจ submit ซ้ำ |
| 🟠 กลาง | Barcode buffer reset ผิดจังหวะเมื่อ checkout fail |
| 🟡 ต่ำ | Screen lock kick ช้า ~8 วินาที |
| 🟡 ต่ำ | Undo state อาจไม่ sync กับ DB ในบางกรณี |

---

## หมายเหตุ

- ไฟล์ `settings.local.php` ไม่ถูก track ใน git (มี DB credentials)
- รองรับ IIS และ Apache
