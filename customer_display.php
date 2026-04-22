<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?php echo h(APP_TITLE); ?> - Customer Display</title>
    <style>
        :root{
            --bg:#edf5ff;
            --bg-2:#fff7ed;
            --surface:#ffffff;
            --surface-soft:#f8fbff;
            --text:#122033;
            --muted:#6b7a90;
            --line:#dbe8f7;
            --line-strong:#c3d5ea;
            --primary:#1683ff;
            --primary-dark:#0f69cf;
            --primary-deep:#0a3a70;
            --secondary:#ff8a1f;
            --secondary-soft:#fff1e4;
            --success:#12a150;
            --success-soft:#e6f8ee;
            --shadow:0 12px 28px rgba(15, 23, 42, .10);
            --shadow-soft:0 8px 18px rgba(22, 131, 255, .08);
            --radius-xl:26px;
            --radius-lg:20px;
            --radius-md:14px;
            --chip-bg:rgba(255,255,255,.14);
            --chip-border:rgba(255,255,255,.18);
        }
        *{box-sizing:border-box}
        html,body{height:100%}
        body{
            margin:0;
            color:var(--text);
            font-family:Tahoma, Arial, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(22,131,255,.12), transparent 28%),
                radial-gradient(circle at top right, rgba(255,138,31,.14), transparent 24%),
                linear-gradient(180deg, var(--bg), var(--bg-2));
        }
        .shell{min-height:100%;display:flex;flex-direction:column}
        .topbar{
            position:sticky;top:0;z-index:20;
            padding:16px 22px 14px;
            background:linear-gradient(135deg, rgba(8,58,112,.96), rgba(22,131,255,.92), rgba(255,138,31,.88));
            color:#fff;
            box-shadow:0 8px 20px rgba(8,58,112,.18);
        }
        .topbar-inner{
            max-width:1920px;margin:0 auto;
            display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;
        }
        .brand{display:flex;align-items:center;gap:16px;min-width:0}
        .brand-mark{
            width:58px;height:58px;border-radius:18px;display:grid;place-items:center;flex:0 0 auto;
            background:#fff;color:var(--primary-deep);font-size:22px;font-weight:700;
            box-shadow:0 8px 18px rgba(0,0,0,.12);
        }
        .brand h1{
            margin:0;font-size:28px;line-height:1.08;font-weight:700;letter-spacing:0;
            white-space:nowrap;
        }
        .brand .sub{
            margin-top:4px;font-size:12px;line-height:1.2;font-weight:700;opacity:.96;
            letter-spacing:.04em;text-transform:uppercase;
        }
        .top-right{display:flex;align-items:center;gap:10px;flex-wrap:wrap;justify-content:flex-end}
        .lang-switch{
            display:inline-flex;align-items:center;gap:4px;padding:4px;border-radius:999px;
            background:var(--chip-bg);border:1px solid var(--chip-border);
        }
        .lang-btn{
            appearance:none;border:0;background:transparent;color:rgba(255,255,255,.90);cursor:pointer;
            min-width:56px;height:44px;padding:0 16px;border-radius:999px;font-size:13px;font-weight:700;
            transition:.15s ease;
        }
        .lang-btn.active{background:#fff;color:var(--primary-deep)}
        .chip{
            min-height:44px;padding:0 14px;border-radius:999px;display:inline-flex;align-items:center;gap:8px;
            background:var(--chip-bg);border:1px solid var(--chip-border);
            color:#fff;font-size:13px;font-weight:700;white-space:nowrap;
        }
        .chip strong{
            font-size:18px;line-height:1;font-weight:700;font-variant-numeric:tabular-nums;
            font-feature-settings:"tnum" 1,"lnum" 1;
        }

        .layout{max-width:1920px;margin:0 auto;padding:18px 18px 22px;display:grid;gap:16px;flex:1}
        .hero-row{display:grid;grid-template-columns:1.15fr .9fr .9fr;gap:16px}
        .hero-card,.ticker-card,.panel,.footer-card{
            background:rgba(255,255,255,.92);
            border:1px solid rgba(255,255,255,.75);
            box-shadow:var(--shadow);
        }
        .hero-card,.ticker-card{
            border-radius:24px;padding:18px 20px;
        }
        .hero-card h3,.ticker-card h3,.footer-title{
            margin:0 0 10px;font-size:12px;line-height:1.2;color:var(--muted);
            letter-spacing:.08em;font-weight:700;text-transform:uppercase;
        }
        .hero-value,.table-num,.count-badge,.chip strong,#currentClock,#lastUpdated{
            font-variant-numeric:tabular-nums;
            font-feature-settings:"tnum" 1,"lnum" 1;
        }
        .hero-value{
            font-size:42px;line-height:1.02;font-weight:700;color:var(--primary-deep);
        }
        .hero-note{
            margin-top:8px;font-size:15px;line-height:1.4;font-weight:700;color:#4f6482;
        }
        .wait-range{
            display:inline-flex;align-items:center;min-height:42px;padding:0 14px;border-radius:999px;
            background:var(--secondary-soft);border:1px solid #ffd2a4;font-size:14px;font-weight:700;color:#9f5200;
        }
        .ticker-list{display:flex;flex-wrap:wrap;gap:10px;min-height:46px;align-items:flex-start}
        .ticker-pill{
            min-width:78px;padding:12px 16px;border-radius:999px;
            background:linear-gradient(135deg,#fff3ea,#ffd8c2);
            border:1px solid #efc4ae;font-size:24px;font-weight:700;line-height:1;color:#9f4d27;text-align:center;
        }
        .ticker-empty{font-size:16px;font-weight:700;color:var(--muted)}

        .boards{display:grid;grid-template-columns:1fr 1fr;gap:18px;align-items:start}
        .panel{
            min-height:calc(100vh - 350px);border-radius:30px;padding:18px;
            overflow:hidden;
        }
        .panel-head{
            display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:14px;
        }
        .title-wrap{display:flex;align-items:flex-start;gap:12px;min-width:0}
        .icon{
            width:54px;height:54px;border-radius:18px;display:grid;place-items:center;flex:0 0 auto;
            font-size:24px;font-weight:700;color:#fff;
            box-shadow:var(--shadow-soft);
        }
        .icon.blue{background:linear-gradient(135deg,var(--primary-deep),var(--primary))}
        .icon.orange{background:linear-gradient(135deg,#f59e0b,#f97316)}
        .panel h2{
            margin:0;font-size:28px;line-height:1.08;color:var(--primary-deep);font-weight:700;
        }
        .panel p{
            margin:4px 0 0;font-size:14px;line-height:1.35;font-weight:700;color:#667b96;
        }
        .count-badge{
            min-width:86px;min-height:50px;padding:0 18px;border-radius:999px;
            display:inline-flex;align-items:center;justify-content:center;
            background:#eef4fb;border:1px solid #c6d8ec;color:var(--primary-deep);
            font-size:22px;font-weight:700;flex:0 0 auto;
        }

        .board-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;align-content:start}
        .table-box{
            min-height:132px;border-radius:22px;display:flex;align-items:center;justify-content:center;
            position:relative;overflow:hidden;text-align:center;padding:18px 16px;
            border:1px solid #dbe8f7;box-shadow:0 10px 22px rgba(17,56,92,.08);
        }
        .table-box.blue{background:linear-gradient(180deg,#ffffff,#eef6ff)}
        .table-box.orange{background:linear-gradient(180deg,#fff9f3,#ffe8d7);border-color:#ffd8b0}
        .table-sub{
            position:absolute;left:12px;top:12px;z-index:1;font-size:11px;font-weight:700;color:#fff;
            letter-spacing:.04em;padding:6px 10px;border-radius:999px;
        }
        .table-box.blue .table-sub{background:linear-gradient(135deg,var(--primary),var(--primary-dark))}
        .table-box.orange .table-sub{background:linear-gradient(135deg,#f59e0b,#f97316)}
        .table-num{
            position:relative;z-index:1;display:block;max-width:100%;
            font-size:58px;line-height:1.02;font-weight:700;letter-spacing:0;color:var(--primary-deep);
            white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
        }
        .table-box.long .table-num{font-size:48px}
        .table-box.xlong .table-num{font-size:38px}
        .table-box.xxlong .table-num{font-size:30px}
        .table-box.orange.new-ready{animation:readyPulse 1.4s ease-in-out infinite alternate}
        .table-box.orange .table-num{animation:glow 2.1s ease-in-out infinite}
        @keyframes glow{0%,100%{transform:scale(1)}50%{transform:scale(1.04)}}
        @keyframes readyPulse{from{box-shadow:0 10px 22px rgba(17,56,92,.08)}to{box-shadow:0 0 0 4px rgba(249,115,22,.10),0 18px 34px rgba(249,115,22,.18)}}

        .empty{
            min-height:280px;display:flex;align-items:center;justify-content:center;text-align:center;
            border-radius:24px;border:2px dashed #cdddf0;background:#f8fbff;color:#667b96;
            font-size:26px;font-weight:700;padding:24px;
        }
        .info-row{display:grid;grid-template-columns:1.2fr 1fr;gap:16px}
        .footer-card{
            border-radius:24px;padding:16px 18px;display:flex;align-items:center;justify-content:space-between;gap:14px;
        }
        .footer-text{font-size:22px;font-weight:700;color:var(--primary-deep);line-height:1.3}
        .footer-text.green{color:var(--success)}

        .compact .board-grid{grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px}
        .compact .table-box{min-height:112px}
        .compact .table-num{font-size:46px}
        .compact .table-box.long .table-num{font-size:38px}
        .compact .table-box.xlong .table-num{font-size:32px}
        .compact .table-box.xxlong .table-num{font-size:26px}
        .dense .board-grid{grid-template-columns:repeat(auto-fit,minmax(135px,1fr));gap:10px}
        .dense .table-box{min-height:92px;border-radius:18px;padding:12px}
        .dense .table-num{font-size:36px}
        .dense .table-box.long .table-num{font-size:30px}
        .dense .table-box.xlong .table-num{font-size:24px}
        .dense .table-box.xxlong .table-num{font-size:20px}
        .dense .table-sub{font-size:10px;padding:5px 8px;top:10px;left:10px}

        @media (max-width:1500px){
            .hero-row{grid-template-columns:1fr 1fr}
            .hero-row .ticker-card{grid-column:1 / -1}
        }
        @media (max-width:1280px){
            .boards,.info-row{grid-template-columns:1fr}
            .panel{min-height:auto}
        }
        @media (max-width:980px){
            .topbar{padding:14px 14px 12px}
            .topbar-inner{align-items:flex-start}
            .brand h1{font-size:24px}
            .hero-value{font-size:34px}
            .panel h2{font-size:24px}
        }
        @media (max-width:700px){
            .layout{padding:12px}
            .hero-row{grid-template-columns:1fr}
            .chip{min-height:40px;font-size:12px;padding:0 12px}
            .chip strong{font-size:16px}
            .brand-mark{width:52px;height:52px;font-size:20px}
            .brand h1{font-size:22px}
            .brand .sub{font-size:11px}
            .table-num{font-size:42px}
            .table-box.long .table-num{font-size:34px}
            .table-box.xlong .table-num{font-size:28px}
            .table-box.xxlong .table-num{font-size:24px}
            .footer-text{font-size:18px}
        }
    </style>
</head>
<body>
<div class="shell">
    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand">
                <div class="brand-mark">CD</div>
                <div>
                    <h1 id="pageTitle" data-i18n="pageTitle">Customer Display</h1>
                    <div class="sub"><?php echo h(APP_TITLE); ?></div>
                </div>
            </div>
            <div class="top-right">
                <div class="lang-switch" aria-label="Language switch">
                    <button type="button" class="lang-btn active" data-lang="en">EN</button>
                    <button type="button" class="lang-btn" data-lang="th">TH</button>
                </div>
                <div class="chip"><span data-i18n="refresh">Refresh</span> <strong><?php echo (int)APP_REFRESH_MS / 1000; ?></strong>s</div>
                <div class="chip"><span data-i18n="preparing">Preparing</span> <strong id="sumCooking">0</strong></div>
                <div class="chip"><span data-i18n="ready">Ready</span> <strong id="sumReady">0</strong></div>
                <div class="chip"><span data-i18n="lastUpdate">Last Update</span> <strong id="lastUpdated">-</strong></div>
            </div>
        </div>
    </header>

    <main class="layout">
        <section class="hero-row">
            <section class="hero-card">
                <h3 data-i18n="currentTime">Current Time</h3>
                <div class="hero-value" id="currentClock">--:--:--</div>
                <div class="hero-note" data-i18n="watchScreen">Please watch this screen for your table number.</div>
            </section>
            <section class="hero-card">
                <h3 data-i18n="estimatedWait">Estimated Wait Time</h3>
                <div class="hero-value" id="waitEstimate">5-10 min</div>
                <div class="hero-note"><span class="wait-range" data-i18n="liveEstimate">Live estimate based on current kitchen activity</span></div>
            </section>
            <section class="ticker-card">
                <h3 data-i18n="recentlyReady">Recently Ready</h3>
                <div class="ticker-list" id="recentReadyList"></div>
            </section>
        </section>

        <section class="boards" id="boardsWrap">
            <section class="panel" id="panelCooking">
                <div class="panel-head">
                    <div class="title-wrap">
                        <div class="icon blue">🍳</div>
                        <div>
                            <h2 data-i18n="nowPreparing">Now Preparing</h2>
                            <p data-i18n="ordersPreparing">Orders currently being prepared</p>
                        </div>
                    </div>
                    <div class="count-badge" id="countCooking">0</div>
                </div>
                <div class="board-grid" id="cookingBoard"></div>
            </section>

            <section class="panel" id="panelReady">
                <div class="panel-head">
                    <div class="title-wrap">
                        <div class="icon orange">✔</div>
                        <div>
                            <h2 data-i18n="readyForService">Ready for Service</h2>
                            <p data-i18n="collectOrWait">Please collect or wait for staff assistance</p>
                        </div>
                    </div>
                    <div class="count-badge" id="countReady">0</div>
                </div>
                <div class="board-grid" id="readyBoard"></div>
            </section>
        </section>

        <section class="info-row">
            <section class="footer-card">
                <div>
                    <div class="footer-title" data-i18n="customerNotice">Customer Notice</div>
                    <div class="footer-text" data-i18n="customerNoticeText">Please wait until your table number appears in Ready for Service.</div>
                </div>
            </section>
            <section class="footer-card">
                <div>
                    <div class="footer-title" data-i18n="readyTables">Ready Tables</div>
                    <div class="footer-text green"><span id="readySummary">0</span> <span data-i18n="tablesReadyToServe">table(s) ready to serve</span></div>
                </div>
            </section>
        </section>
    </main>
</div>

<script>
const I18N = {
    en: {
        pageTitle: 'Customer Display',
        refresh: 'Refresh',
        preparing: 'Preparing',
        ready: 'Ready',
        lastUpdate: 'Last Update',
        currentTime: 'Current Time',
        watchScreen: 'Please watch this screen for your table number.',
        estimatedWait: 'Estimated Wait Time',
        liveEstimate: 'Live estimate based on current kitchen activity',
        recentlyReady: 'Recently Ready',
        nowPreparing: 'Now Preparing',
        ordersPreparing: 'Orders currently being prepared',
        readyForService: 'Ready for Service',
        collectOrWait: 'Please collect or wait for staff assistance',
        customerNotice: 'Customer Notice',
        customerNoticeText: 'Please wait until your table number appears in Ready for Service.',
        readyTables: 'Ready Tables',
        tablesReadyToServe: 'table(s) ready to serve',
        noRecentReady: 'No recent ready tables',
        statusReady: 'Ready',
        statusPreparing: 'Preparing',
        noTablesInProgress: 'No tables in progress',
        noTablesReady: 'No tables ready',
        unableToLoadData: 'Unable to load data',
        error: 'Error'
    },
    th: {
        pageTitle: 'จอแสดงสถานะลูกค้า',
        refresh: 'รีเฟรช',
        preparing: 'กำลังเตรียม',
        ready: 'พร้อมเสิร์ฟ',
        lastUpdate: 'อัปเดตล่าสุด',
        currentTime: 'เวลาปัจจุบัน',
        watchScreen: 'กรุณาดูหน้าจอนี้เพื่อสังเกตหมายเลขโต๊ะของท่าน',
        estimatedWait: 'เวลารอโดยประมาณ',
        liveEstimate: 'คำนวณจากปริมาณงานในครัวขณะนี้',
        recentlyReady: 'โต๊ะที่พร้อมล่าสุด',
        nowPreparing: 'กำลังเตรียม',
        ordersPreparing: 'รายการอาหารที่กำลังจัดเตรียม',
        readyForService: 'พร้อมเสิร์ฟ',
        collectOrWait: 'กรุณารับอาหารหรือติดต่อพนักงาน',
        customerNotice: 'สำหรับลูกค้า',
        customerNoticeText: 'กรุณารอจนกว่าหมายเลขโต๊ะของท่านจะปรากฏในส่วน พร้อมเสิร์ฟ',
        readyTables: 'โต๊ะพร้อมเสิร์ฟ',
        tablesReadyToServe: 'โต๊ะพร้อมเสิร์ฟ',
        noRecentReady: 'ยังไม่มีโต๊ะพร้อมล่าสุด',
        statusReady: 'พร้อมเสิร์ฟ',
        statusPreparing: 'กำลังเตรียม',
        noTablesInProgress: 'ยังไม่มีโต๊ะที่กำลังเตรียม',
        noTablesReady: 'ยังไม่มีโต๊ะพร้อมเสิร์ฟ',
        unableToLoadData: 'ไม่สามารถโหลดข้อมูลได้',
        error: 'ผิดพลาด'
    }
};
let currentLang = localStorage.getItem('customerDisplayLang') || 'th';
const REFRESH_MS = <?php echo (int)APP_REFRESH_MS; ?>;
const endpointActive = 'api_checker.php?action=list_active';
const endpointFinished = 'api_checker.php?action=list_finished';
const NEW_READY_WINDOW_MS = 2 * 60 * 1000;
let previousReadySet = new Set();

function safeArray(v){ return Array.isArray(v) ? v : []; }
function escapeHtml(str){
    return String(str ?? '').replace(/[&<>"']/g, function(m){
        return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'})[m];
    });
}
function formatClock(date = new Date()){
    return date.toLocaleTimeString(currentLang === 'th' ? 'th-TH' : 'en-GB', {hour:'2-digit', minute:'2-digit', second:'2-digit'});
}
function normalizeTableName(row){
    const raw = row.DisplayTableName || row.TableName || row.TableID || row.OrderNo || '-';
    return String(raw).trim();
}
function rowTime(row){
    return row.OrderingTime || row.FinishedTime || row.CheckerTime || row.BeginTime || row.UpdateDateTime || row.DocDate || '';
}
function t(key){
    return (I18N[currentLang] && I18N[currentLang][key]) || I18N.en[key] || key;
}
function applyLanguage(){
    document.documentElement.lang = currentLang === 'th' ? 'th' : 'en';
    document.title = `<?php echo h(APP_TITLE); ?> - ${t('pageTitle')}`;
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        el.textContent = t(key);
    });
    document.querySelectorAll('.lang-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.lang === currentLang);
    });
    updateClock();
}
function initLanguage(){
    document.querySelectorAll('.lang-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const lang = btn.dataset.lang === 'th' ? 'th' : 'en';
            if(lang === currentLang) return;
            currentLang = lang;
            localStorage.setItem('customerDisplayLang', currentLang);
            applyLanguage();
            loadBoards();
        });
    });
    applyLanguage();
}
function buildMap(activeRows, finishedRows){
    const map = new Map();

    activeRows.forEach(row => {
        const key = normalizeTableName(row);
        if(!map.has(key)) map.set(key, {name:key, active:0, finished:0, rows:[]});
        const item = map.get(key);
        item.active += 1;
        item.rows.push(row);
    });

    finishedRows.forEach(row => {
        const key = normalizeTableName(row);
        if(!map.has(key)) map.set(key, {name:key, active:0, finished:0, rows:[]});
        const item = map.get(key);
        item.finished += 1;
        item.rows.push(row);
    });

    return Array.from(map.values()).map(item => {
        item.status = (item.active === 0 && item.finished > 0) ? 'ready' : 'cooking';
        item.latestTs = Math.max(...item.rows.map(r => Date.parse(rowTime(r)) || 0), 0);
        return item;
    }).sort((a,b) => (b.latestTs || 0) - (a.latestTs || 0) || String(a.name).localeCompare(String(b.name), 'en'));
}
function renderTableBox(item, color){
    const sub = color === 'orange' ? t('statusReady') : t('statusPreparing');
    const newReadyClass = item.isNewReady ? ' new-ready' : '';
    const len = String(item.name || '').length;
    let sizeClass = '';
    if(len >= 14) sizeClass = ' xxlong';
    else if(len >= 10) sizeClass = ' xlong';
    else if(len >= 7) sizeClass = ' long';
    return `
        <div class="table-box ${color}${sizeClass}${newReadyClass}">
            <div class="table-sub">${sub}</div>
            <div class="table-num" title="${escapeHtml(item.name)}">${escapeHtml(item.name)}</div>
        </div>
    `;
}
function renderEmpty(text){
    return `<div class="empty">${escapeHtml(text)}</div>`;
}
function applyDensity(cookingCount, readyCount){
    const wrap = document.getElementById('boardsWrap');
    wrap.classList.remove('compact','dense');
    const maxCount = Math.max(cookingCount, readyCount);
    if(maxCount >= 11) wrap.classList.add('compact');
    if(maxCount >= 17) wrap.classList.add('dense');
}
function updateClock(){
    document.getElementById('currentClock').textContent = formatClock();
}
function calculateWaitEstimate(cooking){
    if(!cooking.length) return '0-5 min';
    const now = Date.now();
    const mins = cooking
        .map(item => item.latestTs ? Math.max(1, Math.round((now - item.latestTs) / 60000)) : 0)
        .filter(v => v > 0);

    if(!mins.length) return '5-10 min';
    const avg = Math.round(mins.reduce((a,b) => a+b, 0) / mins.length);
    const low = Math.max(5, Math.floor(avg / 5) * 5);
    const high = Math.max(low + 5, Math.ceil((avg + 4) / 5) * 5);
    return `${low}-${high} min`;
}
function renderRecentReady(ready){
    const list = document.getElementById('recentReadyList');
    const top = ready.slice(0, 6);
    if(!top.length){
        list.innerHTML = `<div class="ticker-empty">${escapeHtml(t('noRecentReady'))}</div>`;
        return;
    }
    list.innerHTML = top.map(item => `<div class="ticker-pill">${escapeHtml(item.name)}</div>`).join('');
}
async function loadBoards(){
    try{
        const cacheBuster = Date.now();
        const [activeRes, finishedRes] = await Promise.all([
            fetch(endpointActive + '&_=' + cacheBuster, {cache:'no-store'}),
            fetch(endpointFinished + '&_=' + cacheBuster, {cache:'no-store'})
        ]);

        const activeJson = await activeRes.json();
        const finishedJson = await finishedRes.json();

        const activeRows = safeArray(
            activeJson.active_rows || activeJson.rows || activeJson.data || activeJson
        );
        const finishedRows = safeArray(
            finishedJson.recent_finished_rows || finishedJson.finished_rows || finishedJson.rows || finishedJson.data || finishedJson
        );

        const tables = buildMap(activeRows, finishedRows);
        const cooking = tables.filter(x => x.status === 'cooking');
        const ready = tables.filter(x => x.status === 'ready');
        const now = Date.now();
        const readySet = new Set(ready.map(x => x.name));

        ready.forEach(item => {
            item.isNewReady = !previousReadySet.has(item.name) || (item.latestTs && (now - item.latestTs) <= NEW_READY_WINDOW_MS);
        });
        previousReadySet = readySet;

        document.getElementById('sumCooking').textContent = cooking.length;
        document.getElementById('sumReady').textContent = ready.length;
        document.getElementById('countCooking').textContent = cooking.length;
        document.getElementById('countReady').textContent = ready.length;
        document.getElementById('lastUpdated').textContent = formatClock();
        document.getElementById('waitEstimate').textContent = calculateWaitEstimate(cooking);
        document.getElementById('readySummary').textContent = ready.length;

        applyDensity(cooking.length, ready.length);
        renderRecentReady(ready);

        document.getElementById('cookingBoard').innerHTML = cooking.length
            ? cooking.map(item => renderTableBox(item, 'blue')).join('')
            : renderEmpty(t('noTablesInProgress'));
        document.getElementById('readyBoard').innerHTML = ready.length
            ? ready.map(item => renderTableBox(item, 'orange')).join('')
            : renderEmpty(t('noTablesReady'));
    }catch(err){
        document.getElementById('cookingBoard').innerHTML = renderEmpty(t('unableToLoadData'));
        document.getElementById('readyBoard').innerHTML = renderEmpty(t('unableToLoadData'));
        document.getElementById('recentReadyList').innerHTML = `<div class="ticker-empty">${escapeHtml(t('unableToLoadData'))}</div>`;
        document.getElementById('lastUpdated').textContent = t('error');
        console.error(err);
    }
}
function handleBoardVisibilityChange(){
    if (!document.hidden) {
        loadBoards();
    }
}

initLanguage();
setInterval(updateClock, 1000);
loadBoards();
setInterval(function(){
    if (!document.hidden) {
        loadBoards();
    }
}, REFRESH_MS);
document.addEventListener('visibilitychange', handleBoardVisibilityChange);
</script>
</body>
</html>
