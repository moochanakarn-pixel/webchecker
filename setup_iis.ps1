#Requires -RunAsAdministrator
<#
.SYNOPSIS
    Checker KDS — IIS FastCGI Setup
    รันสคริปต์นี้บนเครื่อง Windows ที่ต้องการติดตั้ง KDS
    PHP ต้องติดตั้งไว้ก่อนแล้ว
#>

[Console]::OutputEncoding = [System.Text.Encoding]::UTF8
$ErrorActionPreference = 'Stop'

function Write-Step($msg) { Write-Host "`n>> $msg" -ForegroundColor Cyan }
function Write-OK($msg)   { Write-Host "   OK  $msg" -ForegroundColor Green }
function Write-Skip($msg) { Write-Host "   --  $msg" -ForegroundColor DarkGray }
function Write-Warn($msg) { Write-Host "   !!  $msg" -ForegroundColor Yellow }

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "  Checker KDS — IIS FastCGI Setup" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan

# ─────────────────────────────────────────────────────────────
# 1. หา php-cgi.exe
# ─────────────────────────────────────────────────────────────
Write-Step "ค้นหา php-cgi.exe"

$phpCgi = $null
$candidates = @(
    "C:\php\php-cgi.exe",
    "C:\php8\php-cgi.exe",
    "C:\php81\php-cgi.exe",
    "C:\php82\php-cgi.exe",
    "C:\php83\php-cgi.exe",
    "C:\php7\php-cgi.exe",
    "C:\Program Files\PHP\php-cgi.exe",
    "C:\Program Files (x86)\PHP\php-cgi.exe"
)

foreach ($c in $candidates) {
    if (Test-Path $c) { $phpCgi = $c; break }
}

if (-not $phpCgi) {
    Write-Warn "ไม่พบ php-cgi.exe อัตโนมัติ"
    $phpCgi = Read-Host "   กรุณากรอก path เต็มของ php-cgi.exe"
}

if (-not (Test-Path $phpCgi)) {
    Write-Error "ไม่พบไฟล์: $phpCgi"
    exit 1
}

Write-OK "php-cgi.exe: $phpCgi"

# ตรวจ version
$phpExe = Join-Path (Split-Path $phpCgi) "php.exe"
if (Test-Path $phpExe) {
    $ver = & $phpExe -r "echo PHP_VERSION;" 2>$null
    Write-OK "PHP version: $ver"
}

# ─────────────────────────────────────────────────────────────
# 2. เปิด IIS CGI Feature (Windows Feature)
# ─────────────────────────────────────────────────────────────
Write-Step "ตรวจสอบ IIS CGI Feature"

$iisFeatures = @('IIS-WebServerRole','IIS-WebServer','IIS-CGI')
foreach ($feat in $iisFeatures) {
    $f = Get-WindowsOptionalFeature -Online -FeatureName $feat -ErrorAction SilentlyContinue
    if ($f -and $f.State -ne 'Enabled') {
        Write-Warn "กำลังเปิด $feat ..."
        Enable-WindowsOptionalFeature -Online -FeatureName $feat -NoRestart | Out-Null
        Write-OK "เปิด $feat แล้ว"
    } else {
        Write-Skip "$feat เปิดอยู่แล้ว"
    }
}

# ─────────────────────────────────────────────────────────────
# 3. โหลด WebAdministration module
# ─────────────────────────────────────────────────────────────
Write-Step "โหลด IIS WebAdministration module"

try {
    Import-Module WebAdministration -ErrorAction Stop
    Write-OK "โหลด WebAdministration สำเร็จ"
} catch {
    Write-Error "โหลด WebAdministration ไม่ได้ — ตรวจสอบว่า IIS ติดตั้งแล้ว: $_"
    exit 1
}

# ─────────────────────────────────────────────────────────────
# 4. เพิ่ม FastCGI Application
# ─────────────────────────────────────────────────────────────
Write-Step "ตั้งค่า FastCGI Application"

$existing = Get-WebConfiguration "system.webServer/fastCgi/application" |
    Where-Object { $_.fullPath -eq $phpCgi }

if (-not $existing) {
    Add-WebConfiguration -Filter "system.webServer/fastCgi" -PSPath "IIS:\" -Value @{
        fullPath            = $phpCgi
        maxInstances        = 8
        instanceMaxRequests = 10000
        idleTimeout         = 300
        activityTimeout     = 70
        requestTimeout      = 90
    }
    Write-OK "เพิ่ม FastCGI application แล้ว"
} else {
    Write-Skip "FastCGI application มีอยู่แล้ว"
}

# ─────────────────────────────────────────────────────────────
# 5. เพิ่ม Handler Mapping (global level)
# ─────────────────────────────────────────────────────────────
Write-Step "ตั้งค่า PHP Handler Mapping"

$handlerName = "PHP_FastCGI_KDS"
$existingHandler = Get-WebConfiguration "system.webServer/handlers/add[@name='$handlerName']" -PSPath "IIS:\" -ErrorAction SilentlyContinue

if (-not $existingHandler) {
    Add-WebConfiguration -Filter "system.webServer/handlers" -PSPath "IIS:\" -Value @{
        name            = $handlerName
        path            = "*.php"
        verb            = "*"
        modules         = "FastCgiModule"
        scriptProcessor = $phpCgi
        resourceType    = "File"
    }
    Write-OK "เพิ่ม Handler Mapping '$handlerName' แล้ว"
} else {
    Write-Skip "Handler Mapping '$handlerName' มีอยู่แล้ว"
}

# ─────────────────────────────────────────────────────────────
# 6. เพิ่ม IIS Site Binding
# ─────────────────────────────────────────────────────────────
Write-Step "ตั้งค่า Port สำหรับ KDS"

$portInput = Read-Host "   ใช้ port ไหน? (กด Enter = 80)"
$port = if ($portInput -match '^\d+$') { [int]$portInput } else { 80 }

# เพิ่ม binding port นี้ให้ Default Web Site (ถ้ายังไม่มี)
$siteName = "Default Web Site"
$existingBinding = Get-WebBinding -Name $siteName -Protocol "http" -Port $port -ErrorAction SilentlyContinue
if (-not $existingBinding) {
    New-WebBinding -Name $siteName -Protocol "http" -Port $port -IPAddress "*"
    Write-OK "เพิ่ม IIS binding port $port ให้ '$siteName' แล้ว"
} else {
    Write-Skip "IIS binding port $port มีอยู่แล้ว"
}

# ─────────────────────────────────────────────────────────────
# 7. เปิด Firewall Port
# ─────────────────────────────────────────────────────────────
Write-Step "ตั้งค่า Windows Firewall"

$ruleName  = "KDS_IIS_Inbound_TCP_$port"
$existingRule = Get-NetFirewallRule -Name $ruleName -ErrorAction SilentlyContinue

if (-not $existingRule) {
    New-NetFirewallRule `
        -Name        $ruleName `
        -DisplayName "Checker KDS — IIS port $port" `
        -Direction   Inbound `
        -Protocol    TCP `
        -LocalPort   $port `
        -Action      Allow `
        -Profile     Any | Out-Null
    Write-OK "เปิด Firewall inbound TCP port $port แล้ว"
} else {
    Write-Skip "Firewall rule สำหรับ port $port มีอยู่แล้ว"
}

# ─────────────────────────────────────────────────────────────
# 8. Restart IIS
# ─────────────────────────────────────────────────────────────
Write-Step "Restart IIS เพื่อให้ค่าใช้งานได้"

try {
    iisreset /noforce 2>&1 | Out-Null
    Write-OK "Restart IIS สำเร็จ"
} catch {
    Write-Warn "Restart IIS ไม่สำเร็จ กรุณารันคำสั่ง: iisreset"
}

# ─────────────────────────────────────────────────────────────
# 9. สรุป
# ─────────────────────────────────────────────────────────────
$hostname = $env:COMPUTERNAME
Write-Host ""
Write-Host "============================================" -ForegroundColor Green
Write-Host "  ติดตั้งเสร็จสิ้น" -ForegroundColor Green
Write-Host "============================================" -ForegroundColor Green
Write-Host ""
Write-Host "  เปิด browser แล้วไปที่:" -ForegroundColor White
Write-Host "  http://localhost:$port/checker.php" -ForegroundColor Yellow
Write-Host "  http://$hostname`:$port/checker.php  (จาก tablet)" -ForegroundColor Yellow
Write-Host ""
Write-Host "  หมายเหตุ:" -ForegroundColor White
Write-Host "  - ถ้าวาง KDS ไว้ใน subfolder ให้ใส่ชื่อโฟลเดอร์ด้วย" -ForegroundColor DarkGray
Write-Host "    เช่น http://$hostname`:$port/checker/checker.php" -ForegroundColor DarkGray
Write-Host "  - ยังไม่มี settings.local.php ให้เปิดหน้าแล้วตั้งค่าได้เลย" -ForegroundColor DarkGray
Write-Host ""
