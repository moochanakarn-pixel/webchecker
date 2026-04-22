<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>หน้าเสิร์ฟ — Waiter Display</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap');
:root{
  --bg:#0f1117;--sur:#1a1d27;--sur2:#232636;--bdr:#2e3248;
  --acc:#f5a623;--acc2:#e8834a;
  --grn:#22c55e;--grn-bg:rgba(34,197,94,.12);
  --ylw:#facc15;--ylw-bg:rgba(250,204,21,.10);
  --blu:#60a5fa;--blu-bg:rgba(96,165,250,.10);
  --red:#ef4444;
  --tx:#f0f0f5;--tx2:#9ba3c0;--tx3:#4b5270;
  --r:14px;--rs:8px;
}
*{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
html,body{background:var(--bg);color:var(--tx);font-family:'Noto Sans Thai',sans-serif;min-height:100vh;overscroll-behavior:none}

/* HEADER */
.hdr{background:var(--sur);border-bottom:1px solid var(--bdr);padding:12px 16px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100}
.hdr-l{display:flex;align-items:center;gap:10px}
.hdr-icon{width:36px;height:36px;background:linear-gradient(135deg,var(--acc),var(--acc2));border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
.hdr-title{font-size:17px;font-weight:700}
.hdr-sub{font-size:11px;color:var(--tx2);margin-top:1px}
.hdr-r{display:flex;align-items:center;gap:8px}
.live{width:7px;height:7px;background:var(--grn);border-radius:50%;box-shadow:0 0 6px var(--grn);animation:blink 2s infinite}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.3}}
.clock{font-family:'IBM Plex Mono',monospace;font-size:13px;color:var(--acc);font-weight:600}

/* SUMMARY BAR */
.sum-bar{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--bdr)}
.sum-item{background:var(--sur);padding:10px 8px;text-align:center}
.sum-n{font-family:'IBM Plex Mono',monospace;font-size:22px;font-weight:600;line-height:1}
.sum-l{font-size:10px;color:var(--tx3);margin-top:3px}
.s-wait .sum-n{color:var(--ylw)}
.s-rdy .sum-n{color:var(--grn)}
.s-done .sum-n{color:var(--tx3)}

/* FILTER BAR */
.fbar{display:flex;gap:6px;padding:10px 12px;background:var(--sur);border-bottom:1px solid var(--bdr);overflow-x:auto;scrollbar-width:none}
.fbar::-webkit-scrollbar{display:none}
.fbtn{flex-shrink:0;padding:7px 14px;border-radius:20px;border:1.5px solid var(--bdr);background:transparent;color:var(--tx2);font-family:'Noto Sans Thai',sans-serif;font-size:12px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:5px;transition:all .15s}
.fbtn .cnt{background:var(--bdr);color:var(--tx3);border-radius:10px;padding:1px 7px;font-size:10px;font-family:'IBM Plex Mono',monospace;min-width:18px;text-align:center}
.fbtn.on{background:var(--acc);border-color:var(--acc);color:#0f1117}
.fbtn.on .cnt{background:rgba(0,0,0,.2);color:#0f1117}

/* REFRESH */
.rfbtn{background:var(--sur2);border:1px solid var(--bdr);border-radius:var(--rs);color:var(--tx2);padding:6px 10px;font-size:12px;cursor:pointer;display:flex;align-items:center;gap:5px;font-family:'Noto Sans Thai',sans-serif;white-space:nowrap;transition:all .15s}
.rfbtn:active{background:var(--bdr)}
.rfbtn.spin svg{animation:rot .7s linear infinite}
@keyframes rot{to{transform:rotate(360deg)}}

/* CONTENT */
.content{padding:10px;display:flex;flex-direction:column;gap:8px}

.sec-lbl{font-size:10px;font-weight:700;color:var(--tx3);letter-spacing:.08em;text-transform:uppercase;padding:2px 2px 6px;display:flex;align-items:center;gap:8px}
.sec-lbl::after{content:'';flex:1;height:1px;background:var(--bdr)}

/* CARD */
.card{background:var(--sur);border:1.5px solid var(--bdr);border-radius:var(--r);overflow:hidden;transition:border-color .2s,box-shadow .2s}
.card.c-rdy{border-color:var(--grn);box-shadow:0 0 0 1px rgba(34,197,94,.25),0 4px 16px rgba(34,197,94,.1)}
.card.c-part{border-color:var(--ylw)}
.card.c-done{border-color:var(--tx3);opacity:.45}

.c-hdr{display:flex;align-items:center;justify-content:space-between;padding:12px 14px;border-bottom:1px solid var(--bdr)}
.tbl-badge{display:flex;align-items:center;gap:8px}
.tbl-num{background:linear-gradient(135deg,var(--acc),var(--acc2));color:#0f1117;font-weight:800;font-size:15px;border-radius:7px;padding:4px 10px;font-family:'IBM Plex Mono',monospace}
.tbl-name{font-weight:700;font-size:15px}

.pill{font-size:10px;font-weight:700;padding:4px 10px;border-radius:20px;display:flex;align-items:center;gap:4px;white-space:nowrap}
.p-rdy{background:var(--grn-bg);color:var(--grn);border:1px solid rgba(34,197,94,.25)}
.p-part{background:var(--ylw-bg);color:var(--ylw);border:1px solid rgba(250,204,21,.25)}
.p-done{background:rgba(75,82,112,.12);color:var(--tx3);border:1px solid rgba(75,82,112,.2)}

.c-meta{display:flex;gap:10px;padding:7px 14px;background:rgba(0,0,0,.12);border-bottom:1px solid var(--bdr)}
.m-item{font-size:10px;color:var(--tx3);display:flex;align-items:center;gap:3px}
.m-item b{color:var(--tx2);font-weight:500}

/* ITEM ROW */
.irow{display:flex;align-items:center;padding:12px 14px;gap:10px;border-bottom:1px solid rgba(46,50,72,.4);cursor:pointer;user-select:none;transition:background .1s;position:relative;-webkit-user-select:none}
.irow:last-child{border-bottom:none}
.irow:active{background:rgba(255,255,255,.03)}
.irow.served{opacity:.32}

.chk{width:28px;height:28px;flex-shrink:0;border-radius:7px;border:2px solid var(--bdr);display:flex;align-items:center;justify-content:center;transition:all .2s;position:relative;overflow:hidden}
.chk::after{content:'';position:absolute;inset:0;background:var(--grn);transform:scale(0);border-radius:5px;transition:transform .2s cubic-bezier(.34,1.56,.64,1)}
.chk-ico{position:relative;z-index:1;opacity:0;transform:scale(.5);transition:all .2s}
.irow.served .chk{border-color:var(--grn)}
.irow.served .chk::after{transform:scale(1)}
.irow.served .chk-ico{opacity:1;transform:scale(1)}

.i-info{flex:1;min-width:0}
.i-name{font-size:14px;font-weight:600;line-height:1.3}
.irow.served .i-name{text-decoration:line-through}
.i-tags{display:flex;gap:4px;margin-top:3px;flex-wrap:wrap}
.tag{font-size:9px;padding:2px 7px;border-radius:4px;font-weight:700}
.tag.set{background:var(--blu-bg);color:var(--blu);border:1px solid rgba(96,165,250,.2)}
.tag.sub{background:rgba(139,92,246,.1);color:#a78bfa;border:1px solid rgba(139,92,246,.2)}
.tag.add{background:var(--grn-bg);color:var(--grn);border:1px solid rgba(34,197,94,.2)}

.i-qty{font-family:'IBM Plex Mono',monospace;font-size:13px;font-weight:600;color:var(--tx2);background:var(--sur2);border-radius:6px;padding:3px 8px;flex-shrink:0}

/* PROGRESS */
.prog-wrap{padding:8px 14px 10px}
.prog-track{height:3px;background:var(--bdr);border-radius:2px;overflow:hidden}
.prog-fill{height:100%;background:var(--grn);border-radius:2px;transition:width .3s ease}
.prog-lbl{display:flex;justify-content:space-between;font-size:10px;color:var(--tx3);margin-top:4px}
.prog-lbl .pc{color:var(--grn);font-family:'IBM Plex Mono',monospace}

/* SERVE ALL BTN */
.srv-btn{margin:0 14px 13px;padding:12px;border:none;border-radius:var(--rs);background:var(--grn);color:#fff;font-family:'Noto Sans Thai',sans-serif;font-size:14px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;box-shadow:0 4px 12px rgba(34,197,94,.25);width:calc(100% - 28px);transition:all .15s}
.srv-btn:active{transform:scale(.98);box-shadow:none}
.srv-btn.done-btn{background:var(--sur2);color:var(--tx3);box-shadow:none;cursor:default}
.srv-btn:disabled{opacity:.5;cursor:not-allowed;transform:none}

/* EMPTY */
.empty{text-align:center;padding:64px 20px;color:var(--tx3)}
.empty .ico{font-size:48px;margin-bottom:16px}
.empty h3{font-size:16px;color:var(--tx2);font-weight:600}
.empty p{font-size:12px;margin-top:6px;line-height:1.7}

/* LOADING */
.loading{display:flex;flex-direction:column;align-items:center;justify-content:center;padding:60px 20px;gap:14px;color:var(--tx3)}
.spinner{width:28px;height:28px;border:2.5px solid var(--bdr);border-top-color:var(--acc);border-radius:50%;animation:rot .7s linear infinite}

/* TOAST */
.toast{position:fixed;bottom:20px;left:50%;transform:translateX(-50%) translateY(80px);padding:11px 22px;border-radius:24px;font-size:13px;font-weight:700;z-index:999;transition:transform .3s cubic-bezier(.34,1.56,.64,1);white-space:nowrap;box-shadow:0 8px 24px rgba(0,0,0,.5);pointer-events:none}
.toast.t-ok{background:var(--sur);border:1px solid var(--grn);color:var(--grn)}
.toast.t-err{background:var(--sur);border:1px solid var(--red);color:var(--red)}
.toast.show{transform:translateX(-50%) translateY(0)}

/* ERROR BANNER */
.err-banner{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:var(--red);font-size:12px;padding:10px 14px;margin:10px;border-radius:var(--rs);display:none}
.err-banner.show{display:block}
</style>
</head>
<body>

<!-- HEADER -->
<div class="hdr">
  <div class="hdr-l">
    <div class="hdr-icon">🍽️</div>
    <div>
      <div class="hdr-title">เสิร์ฟอาหาร</div>
      <div class="hdr-sub">Waiter Display</div>
    </div>
  </div>
  <div class="hdr-r">
    <div class="live"></div>
    <div class="clock" id="clock">--:--</div>
    <button class="rfbtn" id="rfBtn" onclick="loadData()">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 12a9 9 0 0 1-9 9 9 9 0 0 1-6.36-2.64L3 21V15h6l-2.73 2.73A7 7 0 0 0 19 12z"/>
        <path d="M3 12a9 9 0 0 1 9-9 9 9 0 0 1 6.36 2.64L21 3v6h-6l2.73-2.73A7 7 0 0 0 5 12z"/>
      </svg>
      รีเฟรช
    </button>
  </div>
</div>

<!-- SUMMARY -->
<div class="sum-bar">
  <div class="sum-item s-wait"><div class="sum-n" id="sn-wait">-</div><div class="sum-l">โต๊ะรอเสิร์ฟ</div></div>
  <div class="sum-item s-rdy"><div class="sum-n" id="sn-items">-</div><div class="sum-l">รายการค้าง</div></div>
  <div class="sum-item s-done"><div class="sum-n" id="sn-done">-</div><div class="sum-l">เสิร์ฟแล้ว</div></div>
</div>

<!-- FILTER -->
<div class="fbar">
  <button class="fbtn on" id="fb-all"  onclick="setFilter('all')">ทั้งหมด <span class="cnt" id="fc-all">-</span></button>
  <button class="fbtn"    id="fb-wait" onclick="setFilter('wait')">⏳ รอเสิร์ฟ <span class="cnt" id="fc-wait">-</span></button>
  <button class="fbtn"    id="fb-done" onclick="setFilter('done')">✅ เสิร์ฟแล้ว <span class="cnt" id="fc-done">-</span></button>
</div>

<div class="err-banner" id="errBanner"></div>
<div class="content" id="main">
  <div class="loading"><div class="spinner"></div><span>กำลังโหลด...</span></div>
</div>
<div class="toast" id="toast"></div>

<script>
/* ============================================================
   CONFIG
   StaffID ควร inject จาก session PHP จริง
   เช่น: const STAFF_ID = <?= $_SESSION['staff_id'] ?? 0 ?>;
============================================================ */
const API     = 'api_waiter.php';
const STAFF_ID = 0;        // 0 = ไม่ได้ login (demo)
const REFRESH_SEC = 30;    // auto-refresh ทุก 30 วินาที

/* ── state ── */
let tables  = [];   // grouped by tableId
let rawRows = [];   // rows จาก API
let filter  = 'all';
let timer   = null;

/* ============================================================
   LOAD จาก api_waiter.php
============================================================ */
async function loadData() {
  const btn = document.getElementById('rfBtn');
  btn.classList.add('spin');
  clearTimeout(timer);

  try {
    const res  = await fetch(`${API}?action=list_pending`);
    const json = await res.json();

    if (!json.success) throw new Error(json.message || 'API error');

    rawRows = json.rows;
    tables  = groupByTable(rawRows);
    hideError();

  } catch (e) {
    showError('โหลดข้อมูลไม่ได้: ' + e.message);
  } finally {
    btn.classList.remove('spin');
    render();
    timer = setTimeout(loadData, REFRESH_SEC * 1000);
  }
}

/* ── group rows by TableID ── */
function groupByTable(rows) {
  const map = {};
  rows.forEach(r => {
    const k = r.TableID;
    if (!map[k]) map[k] = {
      tableId:   r.TableID,
      tableName: r.DisplayTableName || String(r.TableID),
      earliest:  r.SubmitOrderDateTime,
      rows: []
    };
    if (r.SubmitOrderDateTime < map[k].earliest)
      map[k].earliest = r.SubmitOrderDateTime;
    map[k].rows.push(r);
  });
  return Object.values(map).sort((a,b) => {
    // pending ก่อน → sort ตามเวลาสั่ง
    const aDone = a.rows.every(r => r.ServeStatus == 1) ? 1 : 0;
    const bDone = b.rows.every(r => r.ServeStatus == 1) ? 1 : 0;
    return aDone - bDone || a.earliest.localeCompare(b.earliest);
  });
}

/* ============================================================
   RENDER
============================================================ */
function render() {
  // summary counts
  let nWait = 0, nItems = 0, nDone = 0;
  tables.forEach(t => {
    const d = t.rows.filter(r => r.ServeStatus == 1).length;
    if (d < t.rows.length) nWait++;
    nItems += (t.rows.length - d);
    nDone  += d;
  });
  document.getElementById('sn-wait').textContent  = nWait;
  document.getElementById('sn-items').textContent = nItems;
  document.getElementById('sn-done').textContent  = nDone;

  const nAll  = tables.length;
  const nW    = tables.filter(t => t.rows.some(r  => r.ServeStatus == 0)).length;
  const nD    = tables.filter(t => t.rows.every(r => r.ServeStatus == 1)).length;
  document.getElementById('fc-all').textContent  = nAll;
  document.getElementById('fc-wait').textContent = nW;
  document.getElementById('fc-done').textContent = nD;

  // filter
  let shown = tables;
  if (filter === 'wait') shown = tables.filter(t => t.rows.some(r  => r.ServeStatus == 0));
  if (filter === 'done') shown = tables.filter(t => t.rows.every(r => r.ServeStatus == 1));

  const el = document.getElementById('main');
  if (!shown.length) {
    el.innerHTML = `<div class="empty">
      <div class="ico">${filter === 'done' ? '🎉' : '🍳'}</div>
      <h3>${filter === 'done' ? 'ยังไม่มีโต๊ะที่เสิร์ฟครบ' : 'ไม่มีรายการในหมวดนี้'}</h3>
      <p>${filter === 'wait' ? 'ทุกโต๊ะเสิร์ฟครบแล้ว 👍' : ''}</p>
    </div>`;
    return;
  }

  const pend = shown.filter(t => t.rows.some(r  => r.ServeStatus == 0));
  const done = shown.filter(t => t.rows.every(r => r.ServeStatus == 1));

  let html = '';
  if (pend.length) { html += `<div class="sec-lbl">⏳ รอเสิร์ฟ · ${pend.length} โต๊ะ</div>`; pend.forEach(t => html += buildCard(t)); }
  if (done.length) { html += `<div class="sec-lbl">✅ เสิร์ฟแล้ว · ${done.length} โต๊ะ</div>`; done.forEach(t => html += buildCard(t)); }
  el.innerHTML = html;
}

/* ── build card HTML ── */
function buildCard(t) {
  const total   = t.rows.length;
  const served  = t.rows.filter(r => r.ServeStatus == 1).length;
  const allDone = served === total;
  const pct     = total ? Math.round(served / total * 100) : 0;

  const cls  = allDone ? 'c-done' : served > 0 ? 'c-part' : 'c-rdy';
  const pill = allDone
    ? `<span class="pill p-done">✅ เสิร์ฟครบ</span>`
    : served > 0
      ? `<span class="pill p-part">⏳ ${served}/${total}</span>`
      : `<span class="pill p-rdy">🟢 รอเสิร์ฟ</span>`;

  const t0  = new Date(t.earliest);
  const ts  = `${pad(t0.getHours())}:${pad(t0.getMinutes())}`;

  const items = t.rows.map(r => {
    const srv = r.ServeStatus == 1;
    const key = rowKey(r);
    let tag = '';
    if      (r.ProductSetType ==  7) tag = `<span class="tag set">📦 เซต</span>`;
    else if (r.ProductSetType <   0) tag = `<span class="tag sub">↳ ในเซต</span>`;
    else if (r.ProductSetType == 15) tag = `<span class="tag add">➕ Add-on</span>`;

    return `<div class="irow${srv ? ' served' : ''}" data-key="${key}" onclick="tapItem('${key}')">
      <div class="chk">
        <svg class="chk-ico" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <div class="i-info">
        <div class="i-name">${esc(r.ProductName)}</div>
        ${tag ? `<div class="i-tags">${tag}</div>` : ''}
      </div>
      <div class="i-qty">×${parseFloat(r.ProductAmount)}</div>
    </div>`;
  }).join('');

  const btn = allDone
    ? `<button class="srv-btn done-btn" disabled>✅ เสิร์ฟครบแล้ว</button>`
    : `<button class="srv-btn" onclick="tapServeAll(${t.tableId})">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        เสิร์ฟครบโต๊ะ ${t.tableName}
      </button>`;

  return `<div class="card ${cls}">
    <div class="c-hdr">
      <div class="tbl-badge">
        <div class="tbl-num">T${esc(t.tableName)}</div>
        <div class="tbl-name">โต๊ะ ${esc(t.tableName)}</div>
      </div>
      ${pill}
    </div>
    <div class="c-meta">
      <div class="m-item">🕐 <b>${ts}</b></div>
      <div class="m-item">🍽️ <b>${total}</b> รายการ</div>
      <div class="m-item" style="color:var(--grn)">✅ <b>${served}/${total}</b></div>
    </div>
    <div class="items">${items}</div>
    <div class="prog-wrap">
      <div class="prog-track"><div class="prog-fill" style="width:${pct}%"></div></div>
      <div class="prog-lbl"><span>ความคืบหน้า</span><span class="pc">${served}/${total}</span></div>
    </div>
    ${btn}
  </div>`;
}

/* ============================================================
   ACTIONS — POST ไปที่ api_waiter.php
============================================================ */
async function tapItem(key) {
  const r = rawRows.find(r => rowKey(r) === key);
  if (!r) return;

  const wasServed = r.ServeStatus == 1;
  const action    = wasServed ? 'unserve_item' : 'serve_item';

  // Optimistic UI update
  r.ServeStatus = wasServed ? 0 : 1;
  if (!wasServed) {
    // ถ้า toggle parent set → toggle ลูกในเซตด้วย
    if (r.ProductSetType == 7) {
      rawRows.filter(c => c.ParentProcessID == r.ProcessID && c.TableID == r.TableID)
             .forEach(c => c.ServeStatus = 1);
    }
  }
  tables = groupByTable(rawRows);
  render();
  toast(wasServed ? '↩️ ยกเลิกติ๊ก' : '✅ ติ๊กเสิร์ฟแล้ว');

  // POST to API
  try {
    const fd = new FormData();
    fd.append('action',         action);
    fd.append('ProductLevelID', r.ProductLevelID);
    fd.append('ProcessID',      r.ProcessID);
    fd.append('SubProcessID',   r.SubProcessID);
    fd.append('PrinterID',      r.PrinterID);
    fd.append('StaffID',        STAFF_ID);
    const res  = await fetch(API, { method: 'POST', body: fd });
    const json = await res.json();
    if (!json.success) throw new Error(json.message);
  } catch (e) {
    // Rollback
    r.ServeStatus = wasServed ? 1 : 0;
    tables = groupByTable(rawRows);
    render();
    toast('⚠️ บันทึกไม่สำเร็จ', true);
  }
}

async function tapServeAll(tableId) {
  const t = tables.find(t => t.tableId == tableId);
  if (!t) return;

  // Optimistic
  t.rows.forEach(r => r.ServeStatus = 1);
  tables = groupByTable(rawRows);
  render();
  toast(`✅ เสิร์ฟครบโต๊ะ ${t.tableName} แล้ว!`);

  try {
    const fd = new FormData();
    fd.append('action',  'serve_table');
    fd.append('TableID', tableId);
    fd.append('StaffID', STAFF_ID);
    const res  = await fetch(API, { method: 'POST', body: fd });
    const json = await res.json();
    if (!json.success) throw new Error(json.message);
  } catch (e) {
    // Reload from server on failure
    await loadData();
    toast('⚠️ บันทึกไม่สำเร็จ กำลังโหลดใหม่', true);
  }
}

/* ── filter ── */
function setFilter(f) {
  filter = f;
  ['all', 'wait', 'done'].forEach(x =>
    document.getElementById('fb-' + x).classList.toggle('on', x === f)
  );
  render();
}

/* ============================================================
   HELPERS
============================================================ */
function rowKey(r) {
  return `${r.ProductLevelID}_${r.ProcessID}_${r.SubProcessID}_${r.PrinterID}`;
}
function pad(n)  { return String(n).padStart(2, '0'); }
function esc(s)  { return String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }

/* TOAST */
let _tt;
function toast(msg, err = false) {
  const el = document.getElementById('toast');
  el.textContent = msg;
  el.className   = `toast show ${err ? 't-err' : 't-ok'}`;
  clearTimeout(_tt);
  _tt = setTimeout(() => el.classList.remove('show'), 2200);
}

/* ERROR BANNER */
function showError(msg) {
  const el = document.getElementById('errBanner');
  el.textContent = '⚠️ ' + msg;
  el.classList.add('show');
}
function hideError() {
  document.getElementById('errBanner').classList.remove('show');
}

/* CLOCK */
function tick() {
  const n = new Date();
  document.getElementById('clock').textContent =
    `${pad(n.getHours())}:${pad(n.getMinutes())}:${pad(n.getSeconds())}`;
}
setInterval(tick, 1000);
tick();

/* START */
loadData();
</script>
</body>
</html>
