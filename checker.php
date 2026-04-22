<?php require_once __DIR__ . '/config.php'; require_once __DIR__ . '/auth_check.php'; $machineDisplayName = getMachineDisplayName(); ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?php echo h(APP_TITLE); ?></title>
    <style>
        :root{
            --bg:#edf5ff;
            --bg-2:#fff7ed;
            --surface:#ffffff;
            --surface-soft:#f8fbff;
            --text:#122033;
            --muted:#6b7a90;
            --line:#dbe8f7;
            --primary:#1683ff;
            --primary-dark:#0f69cf;
            --secondary:#ff8a1f;
            --secondary-soft:#fff1e4;
            --success:#12a150;
            --success-soft:#e6f8ee;
            --danger:#e44c3a;
            --danger-soft:#ffe8e4;
            --shadow:0 12px 28px rgba(15, 23, 42, .10);
            --shadow-soft:0 8px 18px rgba(22, 131, 255, .08);
            --radius:22px;
        }
        *{box-sizing:border-box;-webkit-tap-highlight-color:transparent}
        html,body{height:100%}
        body{
            margin:0;
            font-family:Tahoma, Arial, sans-serif;
            color:var(--text);
            background:
                radial-gradient(circle at top left, rgba(22,131,255,.12), transparent 28%),
                radial-gradient(circle at top right, rgba(255,138,31,.14), transparent 24%),
                linear-gradient(180deg, var(--bg), var(--bg-2));
        }
        body.drawer-open{overflow:hidden}
        button,input{font:inherit}

        /* ── Topbar ── */
        .topbar{
            position:sticky;top:0;z-index:30;
            padding:8px 14px 7px;
            backdrop-filter:blur(12px);
            background:linear-gradient(135deg, rgba(8,58,112,.92), rgba(22,131,255,.88), rgba(255,138,31,.84));
            color:#fff;
            box-shadow:0 8px 20px rgba(8,58,112,.18);
        }
        .topbar-inner{
            max-width:1920px;margin:0 auto;
            display:flex;flex-wrap:wrap;align-items:center;gap:8px;
            justify-content:space-between;
        }
        .brand h1{margin:0;font-size:22px;line-height:1.1;letter-spacing:.2px;white-space:nowrap}
        .controls{display:flex;flex-wrap:wrap;gap:7px;align-items:center}
        .field-card{
            display:flex;align-items:center;gap:6px;padding:5px 8px;
            background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.18);border-radius:14px
        }
        .field-card label{font-size:12px;color:#fff;white-space:nowrap}
        .field-card input,
        .field-card select{
            height:34px;border:none;border-radius:10px;padding:0 8px;
            background:#fff;color:var(--text);font-weight:bold;font-size:14px
        }
        .field-card input{
            width:72px;text-align:center;
        }
        .field-card select{
            min-width:156px;max-width:220px
        }
        .field-card-url input{
            width:280px;max-width:42vw;min-width:180px;text-align:left
        }
        .field-card-barcode{
            gap:8px
        }
        .field-card-barcode label{font-size:12px;color:#fff;white-space:nowrap}
        .field-card-barcode input{
            width:150px;max-width:200px;min-width:108px;text-align:left;letter-spacing:.8px
        }
        .barcode-tools{display:flex;flex-wrap:wrap;gap:7px;align-items:center}
        .barcode-hint{
            display:inline-flex;align-items:center;min-height:34px;padding:0 12px;border-radius:10px;
            background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.18);
            color:#fff;font-size:12px;font-weight:bold;white-space:nowrap
        }
        .btn{
            appearance:none;border:none;border-radius:12px;min-height:36px;padding:0 12px;font-size:13px;font-weight:bold;
            cursor:pointer;touch-action:manipulation;transition:transform .12s ease,filter .12s ease,opacity .12s ease;
            box-shadow:var(--shadow-soft)
        }
        .btn:active{transform:scale(.985)}
        .btn:disabled{opacity:.65;cursor:not-allowed}
        .btn-primary{background:#fff;color:var(--primary-dark)}
        .btn-accent{background:linear-gradient(135deg,#fff,#fef3e7);color:#8a4b00}
        .btn-success{background:linear-gradient(135deg,var(--secondary),#ffad59);color:#fff}
        .btn-neutral{background:#eef4fb;color:#1f324a}
        .btn-ghost{background:#e9f3ff;color:#0f5bba}
        .btn-danger{background:var(--danger-soft);color:#b33023}
        .btn-checkout-soft{background:linear-gradient(135deg,#fff7ed,#ffe3c2);color:#a34b00;border:1px solid #ffd2a4}
        .btn-checkout-dark{background:linear-gradient(135deg,var(--secondary),#ffad59);color:#fff}

        /* ── Stats bar (compact single row) ── */
        .stats{
            display:flex;gap:8px;padding:8px 14px 4px;
            max-width:1920px;margin:0 auto;
        }
        .stat{
            display:flex;align-items:center;gap:8px;
            background:rgba(255,255,255,.88);border:1px solid rgba(255,255,255,.72);border-radius:14px;
            padding:7px 14px;box-shadow:var(--shadow)
        }
        .stat-label{font-size:12px;color:var(--muted);white-space:nowrap}
        .stat-value{font-size:20px;font-weight:bold;line-height:1}

        /* ── Main page ── */
        .page{max-width:1920px;margin:0 auto;padding:6px 10px 16px}
        .panel{
            background:rgba(255,255,255,.92);border:1px solid rgba(255,255,255,.75);border-radius:20px;
            box-shadow:var(--shadow);overflow:hidden
        }
        .panel-head{
            display:flex;justify-content:space-between;align-items:center;gap:10px;
            padding:10px 14px;border-bottom:1px solid var(--line);
            background:linear-gradient(180deg,rgba(255,255,255,.96),rgba(245,250,255,.9))
        }
        .panel-title{margin:0;font-size:17px;font-weight:bold}
        .panel-badge{
            flex:0 0 auto;display:inline-flex;align-items:center;min-height:30px;padding:4px 12px;border-radius:999px;
            background:var(--secondary-soft);color:#9a5200;font-size:13px;font-weight:bold
        }

        /* ── Cards grid: target 8 cards visible ── */
        .cards{
            display:grid;
            grid-template-columns:repeat(auto-fill, minmax(220px, 1fr));
            gap:8px;padding:10px
        }
        .card{
            background:linear-gradient(180deg,#fff,#fbfdff);border:1px solid var(--line);
            border-radius:16px;padding:10px;box-shadow:0 4px 12px rgba(17,56,92,.05)
        }
        .card.warn-yellow{border-color:#ffe066;background:linear-gradient(180deg,#fffde7,#fffbf0);box-shadow:0 0 0 3px rgba(255,214,0,.18)}
        .card.warn-red   {border-color:#ffb3ab;background:linear-gradient(180deg,#fff2f0,#fff8f7);box-shadow:0 0 0 3px rgba(228,76,58,.14)}
        .card.checkout-soft{border-color:#ffd2a4;background:linear-gradient(180deg,#fffaf4,#fff1e4);box-shadow:0 0 0 3px rgba(255,138,31,.10)}
        .card.checkout-dark{border-color:#ffd8b0;background:linear-gradient(180deg,#fff7ed,#fff1e4);box-shadow:0 0 0 3px rgba(255,138,31,.14)}
        /* สถานะพิเศษ */
        .card.voided{border-color:#9ca3af;background:linear-gradient(180deg,#e5e7eb,#f3f4f6);box-shadow:none;opacity:.78}
        .card.voided .product-name{text-decoration:line-through;color:#6b7280}
        .card.voided .table-name{color:#6b7280}
        .card.voided .qty-badge{background:#d1d5db;border-color:#9ca3af;color:#6b7280}
        .card.moved   {border-color:#93c5fd;background:linear-gradient(180deg,#eff6ff,#f5f9ff);box-shadow:0 0 0 3px rgba(59,130,246,.12)}
        .card.combined{border-color:#a78bfa;background:linear-gradient(180deg,#f5f3ff,#faf9ff);box-shadow:0 0 0 3px rgba(139,92,246,.12)}
        .status-badge{
            display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:999px;
            font-size:11px;font-weight:bold;margin-bottom:6px
        }
        .status-badge.voided  {background:#6b7280;color:#fff}
        .status-badge.moved   {background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff}
        .status-badge.combined{background:linear-gradient(135deg,#8b5cf6,#7c3aed);color:#fff}
        .status-badge.checkout-soft{background:linear-gradient(135deg,#fff1e4,#ffe0bf);color:#a34b00;border:1px solid #ffc792}
        .status-badge.checkout-dark{background:linear-gradient(135deg,var(--secondary),#ffad59);color:#fff}
        .card-head{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:8px;align-items:start;margin-bottom:8px}
        .table-name{font-size:20px;font-weight:bold;line-height:1.1;word-break:break-word}
        .order-line{margin-top:3px;color:var(--muted);font-size:11px;line-height:1.4}
        .qty-badge{
            min-width:52px;min-height:52px;display:flex;align-items:center;justify-content:center;padding:6px;
            border-radius:14px;background:linear-gradient(135deg,var(--secondary-soft),#fff);
            color:#b35e00;border:1px solid #ffd8b0;font-size:22px;font-weight:bold
        }
        .qty-badge.checkout-soft{background:linear-gradient(135deg,#fff1e4,#fffaf5);border-color:#ffd2a4;color:#a34b00}
        .qty-badge.checkout-dark{background:linear-gradient(135deg,var(--secondary),#ffad59);border-color:#ffd8b0;color:#fff}
        .product-block{margin:0 0 8px}
        .product-name{margin:0;font-size:17px;line-height:1.2;word-break:break-word;font-weight:bold}
        .product-total-hint{margin-top:6px;display:block;padding:0;background:transparent;border:none;border-radius:0;font-size:12px;font-weight:bold;color:#1758a5}
        .parent-name-label{
            display:inline-block;margin-bottom:3px;font-size:11px;font-weight:bold;color:#fff;
            background:linear-gradient(135deg,var(--primary),var(--primary-dark));
            padding:2px 8px;border-radius:999px;letter-spacing:.3px
        }
        .product-comments-inline{margin-top:8px;display:flex;flex-direction:column;gap:6px}
        .comment-inline{font-size:13px;line-height:1.4;color:#5b2c00;word-break:break-word;padding:8px 10px;border-radius:12px;border:1px solid #ffd8b0;background:#fff8f0}
        .comment-inline .label{font-weight:bold;margin-right:4px;color:#9a5200}
        .comment-inline.priced{color:#8b4a00;border-color:#ffc792;background:#fff1e4}
        .queue-tags{display:flex;flex-wrap:wrap;gap:5px;margin-bottom:8px}
        .tag{
            display:inline-flex;align-items:center;min-height:26px;padding:4px 9px;border-radius:999px;
            font-size:11px;font-weight:bold;background:#eef6ff;color:#1758a5;border:1px solid #d5e7ff
        }
        .tag.wait{background:var(--secondary-soft);color:#9f5200;border-color:#ffd2a4}
        .tag.good{background:var(--success-soft);color:#11783c;border-color:#bfeacc}
        .tag.urgent{background:#ffe8e4;color:#b33023;border-color:#ffb3ab}
        .grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:6px;margin-bottom:8px}
        .field{padding:7px 10px;border-radius:12px;background:var(--surface-soft);border:1px solid #e7f0fa}
        .field-label{font-size:11px;color:var(--muted);margin-bottom:2px}
        .field-value{font-size:13px;font-weight:bold;word-break:break-word}
        .card-actions{display:flex;gap:8px}
        .card-actions .btn{flex:1;min-height:42px;font-size:13px}
        .field.is-special{background:#fff8f0;border-color:#ffd8b0}
        .field.is-special .field-label{color:#9a5200}
        .btn-warning{background:linear-gradient(135deg,#f59e0b,#f97316);color:#fff}
        .empty{padding:24px 14px;text-align:center;color:var(--muted);font-size:14px}

        /* ── Notice ── */
        .notice{
            position:fixed;left:14px;right:14px;bottom:calc(14px + env(safe-area-inset-bottom));z-index:80;display:none;
            padding:12px 14px;border-radius:16px;font-size:14px;font-weight:bold;box-shadow:0 16px 32px rgba(15,23,42,.16)
        }
        .notice.success{display:block;background:var(--success-soft);color:#0f7b3b;border:1px solid #bfeacc}
        .notice.error  {display:block;background:var(--danger-soft);color:#b33023;border:1px solid #f6beb7}

        /* ── Drawer ── */
        .drawer-backdrop{
            position:fixed;inset:0;background:rgba(7,23,42,.42);backdrop-filter:blur(4px);
            opacity:0;pointer-events:none;transition:opacity .18s ease;z-index:70
        }
        .drawer-backdrop.open{opacity:1;pointer-events:auto}
        .drawer{
            position:fixed;top:0;right:0;width:min(460px,100vw);height:100vh;background:#fff;z-index:71;
            box-shadow:-20px 0 40px rgba(15,23,42,.18);transform:translateX(100%);transition:transform .22s ease;
            display:flex;flex-direction:column
        }
        .drawer.open{transform:translateX(0)}
        .drawer-head{
            display:flex;justify-content:space-between;gap:10px;align-items:flex-start;
            padding:16px 16px 12px;border-bottom:1px solid var(--line);
            background:linear-gradient(180deg,#fff,#f7fbff)
        }
        .drawer-title{margin:0;font-size:20px;line-height:1.12}
        .drawer-sub{margin-top:4px;font-size:12px;color:var(--muted)}
        .drawer-close{min-width:44px;padding:0 12px}
        .drawer-list{padding:10px 12px 20px;display:flex;flex-direction:column;gap:8px;overflow:auto;flex:1}
        .finished-item{border:1px solid var(--line);border-radius:16px;padding:11px;background:#fff}
        .finished-top{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:8px;align-items:start;margin-bottom:7px}
        .finished-name{font-size:16px;font-weight:bold;line-height:1.25}
        .finished-qty{font-size:20px;font-weight:bold;color:var(--success)}
        .finished-meta{font-size:12px;color:var(--muted);line-height:1.5;margin-bottom:8px}
        .comment-list{display:flex;flex-direction:column;gap:6px;margin:10px 0 0}
        .comment-list.compact{margin-top:6px}
        .comment-group{
            display:flex;flex-wrap:wrap;align-items:flex-start;gap:6px;padding:7px 10px;
            border-radius:12px;border:1px solid #ffe0bc;background:#fff8f0;color:#6a3900;font-size:12px;line-height:1.4
        }
        .comment-group.priced{background:#fff1e4;border-color:#ffc792}
        .comment-group-label{font-weight:bold;white-space:nowrap;color:#9a5200}
        .comment-group-items{flex:1 1 auto;min-width:0;word-break:break-word}

        /* ── FAB ── */
        .fab{
            position:fixed;right:14px;bottom:calc(18px + env(safe-area-inset-bottom));z-index:65;display:none;
            min-height:52px;padding:0 16px;border-radius:999px;
            background:linear-gradient(135deg,var(--primary),var(--secondary));
            color:#fff;border:none;font-weight:bold;font-size:14px;box-shadow:0 14px 28px rgba(22,131,255,.28)
        }

        /* ── Modal ── */
        .modal-backdrop{position:fixed;inset:0;background:rgba(7,23,42,.45);backdrop-filter:blur(4px);opacity:0;pointer-events:none;transition:opacity .18s ease;z-index:80}
        .modal-backdrop.open{opacity:1;pointer-events:auto}
        .modal{
            position:fixed;top:50%;left:50%;transform:translate(-50%,-54%);
            width:min(480px,95vw);background:#fff;border-radius:24px;
            box-shadow:0 24px 56px rgba(15,23,42,.22);z-index:81;
            transition:transform .2s ease,opacity .2s ease;opacity:0;pointer-events:none;
            max-height:92vh;display:flex;flex-direction:column
        }
        .modal.open{transform:translate(-50%,-50%);opacity:1;pointer-events:auto}
        .modal-head{padding:18px 20px 12px;border-bottom:1px solid var(--line);flex-shrink:0}
        .modal-title{margin:0;font-size:19px;font-weight:bold}
        .modal-sub{margin-top:3px;font-size:12px;color:var(--muted)}
        .modal-body{padding:16px 20px;overflow-y:auto;flex:1}
        .modal-section{margin-bottom:18px}
        .modal-section:last-child{margin-bottom:0}
        .modal-section-title{font-size:13px;font-weight:bold;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px}
        .modal-row{display:flex;align-items:center;gap:10px;margin-bottom:12px}
        .modal-row:last-child{margin-bottom:0}
        .modal-swatch{width:24px;height:24px;border-radius:7px;flex-shrink:0;border:2px solid rgba(0,0,0,.08)}
        .modal-swatch.yellow{background:#ffe066}
        .modal-swatch.red{background:#ffb3ab}
        .modal-label{flex:1;font-size:14px;font-weight:600}
        .modal-input{width:72px;height:36px;border:2px solid var(--line);border-radius:10px;padding:0 10px;font-size:15px;font-weight:bold;text-align:center;color:var(--text)}
        .modal-unit{font-size:13px;color:var(--muted);white-space:nowrap}
        .modal-foot{padding:12px 20px 16px;border-top:1px solid var(--line);display:flex;justify-content:flex-end;gap:8px;flex-shrink:0}
        .setting-check{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;padding:10px 12px;border:1px solid var(--line);border-radius:12px;background:#f8fbff}
        .setting-check input{width:18px;height:18px;flex:0 0 auto;margin-top:2px;cursor:pointer}
        .setting-check-title{font-size:14px;font-weight:600;color:var(--text)}
        .setting-check-sub{margin-top:3px;font-size:12px;color:var(--muted);line-height:1.45}
        .setting-stack{display:flex;flex-direction:column;gap:14px}
        .setting-grid{display:grid;grid-template-columns:1fr 160px;gap:12px}
        .setting-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .setting-field label{display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px}
        .setting-field input{width:100%;height:42px;border:2px solid var(--line);border-radius:12px;padding:0 12px;font-size:14px;font-weight:700;color:var(--text);background:#fff}
        .setting-field input:focus{outline:none;border-color:#9ecbff;box-shadow:0 0 0 4px rgba(22,131,255,.10)}
        .setting-help{font-size:12px;color:var(--muted);line-height:1.45;margin-top:6px}
        .settings-status{padding:10px 12px;border-radius:12px;background:#f8fbff;border:1px solid var(--line);font-size:13px;color:#334155;line-height:1.5;display:none}
        .settings-status.show{display:block}
        .settings-status.success{background:#e6f8ee;border-color:#bfeacc;color:#0f7b3b}
        .settings-status.error{background:#ffe8e4;border-color:#f6beb7;color:#b33023}
        .settings-inline{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
        .settings-staff-name{font-size:13px;font-weight:700;color:#0f69cf}
        .settings-headline{display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap}
        .settings-pill{display:inline-flex;align-items:center;min-height:30px;padding:0 10px;border-radius:999px;background:#eef4fb;border:1px solid var(--line);font-size:12px;font-weight:700;color:#334155}
        .camera-wrap{display:flex;flex-direction:column;gap:12px}
        .camera-frame{position:relative;border-radius:18px;overflow:hidden;background:#0b1220;min-height:320px;box-shadow:inset 0 0 0 1px rgba(255,255,255,.06)}
        .camera-video{display:block;width:100%;height:min(62vh,520px);object-fit:cover;background:#0b1220}
        .camera-overlay{position:absolute;inset:0;pointer-events:none;display:flex;align-items:center;justify-content:center;background:linear-gradient(180deg,rgba(0,0,0,.08),rgba(0,0,0,.18))}
        .camera-guide{width:min(82%,420px);height:min(28vw,140px);max-height:140px;border:3px solid rgba(255,255,255,.92);border-radius:18px;box-shadow:0 0 0 9999px rgba(0,0,0,.18)}
        .camera-status{padding:10px 12px;border-radius:12px;background:#f8fbff;border:1px solid var(--line);font-size:13px;color:var(--muted);line-height:1.5}
        .camera-modal .modal-body{padding:14px 16px}
        /* sound upload zone */
        .sound-upload-zone{
            border:2px dashed var(--line);border-radius:14px;padding:14px 16px;
            display:flex;flex-direction:column;gap:8px;background:#f8fbff
        }
        .sound-upload-row{display:flex;align-items:center;gap:10px}
        .sound-name{flex:1;font-size:13px;color:var(--muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
        .sound-name.loaded{color:var(--success);font-weight:600}
        .sound-preview-btn{min-height:32px;padding:0 12px;font-size:12px}
        .sound-file-input{display:none}

        /* ── Responsive ── */
        @media (max-width:1200px){
            .cards{grid-template-columns:repeat(auto-fill,minmax(200px,1fr))}
        }
        @media (max-width:820px){
            .cards{grid-template-columns:repeat(2,1fr)}
            .grid{grid-template-columns:1fr}
            .panel-head{padding:10px 12px}
            .fab{display:none !important}
        }
        @media (max-width:760px){
            .field-card-url input,.field-card-barcode input{width:100%;max-width:100%;min-width:0}
        }
        @media (max-width:700px){.setting-grid,.setting-grid-2{grid-template-columns:1fr}}
        @media (max-width:560px){
            .topbar{padding:8px 10px 7px}
            .page{padding:6px 8px 14px}
            .brand h1{font-size:18px}
            .stats{padding:6px 8px 3px}
            .stat{padding:5px 10px}
            .stat-value{font-size:17px}
            .cards{grid-template-columns:repeat(2,1fr);padding:8px;gap:7px}
            .card{padding:9px;border-radius:14px}
            .table-name{font-size:17px}
            .qty-badge{min-width:44px;min-height:44px;font-size:18px;border-radius:12px}
            .product-name{font-size:14px}
            .card-actions .btn{min-height:38px;padding:0 8px}
            .drawer{width:100vw;top:auto;bottom:0;height:min(82vh,720px);transform:translateY(100%);right:0;border-radius:22px 22px 0 0}
            .drawer.open{transform:translateY(0)}
        }
        @media (max-width:820px){
            .camera-modal{top:0;left:0;width:100vw;max-width:none;height:100dvh;max-height:none;border-radius:0;transform:translateY(100%)}
            .camera-modal.open{transform:translateY(0)}
            .camera-modal .modal-head{padding:16px 16px 10px}
            .camera-modal .modal-body{padding:12px 12px 8px}
            .camera-modal .modal-foot{padding:10px 12px calc(12px + env(safe-area-inset-bottom))}
            .camera-frame{min-height:calc(100dvh - 220px);border-radius:16px}

        .soldout-search{width:100%;height:42px;border:2px solid var(--line);border-radius:12px;padding:0 14px;font-size:15px;font-weight:600;color:var(--text);outline:none}
        .soldout-note{margin-top:8px;font-size:12px;color:var(--muted)}
        .soldout-list{display:flex;flex-direction:column;gap:10px;max-height:52vh;overflow:auto}
        .soldout-item{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:12px;align-items:center;padding:12px 14px;border:1px solid var(--line);border-radius:14px;background:#fff}
        .soldout-item.closed{background:#f9fafb;border-color:#d1d5db}
        .soldout-meta{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:6px}
        .soldout-chip{display:inline-flex;align-items:center;padding:4px 8px;border-radius:999px;font-size:11px;font-weight:700;background:#eef2ff;color:#1d4ed8}
        .soldout-chip.closed{background:#fee2e2;color:#b91c1c}
        .soldout-name{font-size:18px;font-weight:800;color:var(--head);line-height:1.3}
        .soldout-sub{margin-top:4px;font-size:12px;color:var(--muted);line-height:1.5}
        .soldout-actions{display:flex;gap:8px;align-items:center}
        .btn-small{padding:10px 14px;font-size:13px;border-radius:12px}
            .camera-video{height:calc(100dvh - 240px)}
            .camera-guide{width:88%;height:min(34vw,150px)}
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="topbar-inner">
            <div class="brand">
                <div><h1><?php echo h(APP_TITLE); ?></h1><div id="machineDisplayName" style="margin-top:4px;font-size:12px;opacity:.95;font-weight:bold;display:<?php echo $machineDisplayName !== '' ? 'block' : 'none'; ?>"><?php echo h($machineDisplayName); ?></div></div>
            </div>
            <div class="controls">
                <button type="button" class="btn btn-accent" id="openSystemSettingsBtn">⚙️ ตั้งค่าระบบ</button>
                <button type="button" class="btn btn-neutral" id="openSoldOutBtn">🥫 ปิดสินค้าหมด</button>
                <div class="barcode-tools" id="barcodeTools">
                    <div class="field-card field-card-barcode">
                        <label for="barcodeInput">สแกนบาร์โค้ด</label>
                        <input type="text" id="barcodeInput" inputmode="numeric" autocomplete="off" placeholder="ยิงบาร์โค้ดแล้วเช็คเอาต์ทันที">
                    </div>
                    <button type="button" class="btn btn-neutral" id="openBarcodeCameraBtn" style="display:none">📷 สแกนกล้อง</button>
                </div>
                <button type="button" class="btn btn-ghost js-open-finished" id="openFinishedBtn">✅ เสร็จแล้ว <span id="topFinishedCount">0</span></button>
                <button type="button" class="btn btn-primary" id="refreshBtn">🔄 รีเฟรช</button>
            </div>
        </div>
    </div>

    <div class="stats">
        <div class="stat">
            <div class="stat-label">คิวค้าง</div>
            <div class="stat-value" id="statActiveRows">0</div>
        </div>
        <div class="stat">
            <div class="stat-label">รายการ</div>
            <div class="stat-value" id="statActiveQty">0</div>
        </div>
        <div class="stat">
            <div class="stat-label">สถานะ</div>
            <div class="stat-value" id="statStatusText" style="font-size:15px;color:var(--success)">พร้อมใช้งาน</div>
        </div>
    </div>
    <div class="page">
        <div class="layout">
            <section class="panel">
                <div class="panel-head">
                    <div>
                        <h2 class="panel-title">คิวครัวที่ยังค้างอยู่</h2>
                        
                    </div>
                    <div class="panel-badge" id="queueSummary">กำลังโหลด...</div>
                </div>
                <div class="cards" id="activeCards">
                    <div class="empty">กำลังโหลดข้อมูล...</div>
                </div>
            </section>
        </div>
    </div>

    <button type="button" class="fab js-open-finished" id="openFinishedFab">ดูรายการเสร็จ <span id="fabFinishedCount">0</span></button>

    <div class="drawer-backdrop" id="finishedDrawerBackdrop"></div>
    <aside class="drawer" id="finishedDrawer" aria-hidden="true">
        <div class="drawer-head">
            <div>
                <h2 class="drawer-title">รายการที่เสร็จแล้ววันนี้</h2>
                <div class="drawer-sub">แสดงรายการที่เสร็จแล้วทั้งหมดของวันนี้ และกดย้อนกลับ 1 รายการได้</div>
            </div>
            <button type="button" class="btn btn-neutral drawer-close js-close-finished">ปิด</button>
        </div>
        <div class="drawer-list" id="recentFinishedList">
            <div class="empty">ยังไม่มีรายการ</div>
        </div>
    </aside>

    <div class="modal-backdrop" id="timerSettingsBackdrop"></div>
    <div class="modal" id="timerSettingsModal" role="dialog" aria-modal="true" aria-labelledby="timerSettingsTitle">
        <div class="modal-head">
            <h2 class="modal-title" id="timerSettingsTitle">⚙️ ตั้งค่าระบบ</h2>
            <div class="modal-sub">ตั้งค่าฐานข้อมูล เครื่องที่ใช้งาน การแจ้งเตือน และฟีเจอร์หลักทั้งหมดจากปุ่มเดียว</div>
        </div>
        <div class="modal-body">
            <div class="settings-status" id="systemSettingsStatusBox"></div>

            <div class="modal-section">
                <div class="settings-headline">
                    <div class="modal-section-title">ฐานข้อมูล</div>
                    <span class="settings-pill" id="dbUserHint" style="display:none">User: -</span>
                </div>
                <div class="setting-stack">
                    <div class="setting-field">
                        <label for="settingsDbHost">DB Host / IP</label>
                        <input type="text" id="settingsDbHost" placeholder="เช่น 127.0.0.1 หรือ 192.168.1.10">
                    </div>
                    <div class="setting-grid">
                        <div class="setting-field">
                            <label for="settingsDbName">Database Name</label>
                            <input type="text" id="settingsDbName" placeholder="เช่น ini76">
                        </div>
                        <div class="setting-field">
                            <label for="settingsDbPort">Port</label>
                            <input type="number" id="settingsDbPort" min="1" placeholder="3307">
                        </div>
                    </div>
                    <div class="settings-inline">
                        <button type="button" class="btn btn-neutral" id="testDbConnectionBtn">ทดสอบการเชื่อมต่อ</button>
                        <div class="setting-help"></div>
                    </div>
                </div>
            </div>

            <div class="modal-section">
                <div class="modal-section-title">เครื่องที่ใช้งาน</div>
                <div class="setting-grid">
                    <div class="setting-field">
                        <label for="settingsComputerId">Computer ID</label>
                        <input type="number" id="settingsComputerId" min="1" placeholder="เช่น 2">
                    </div>
                    <div class="setting-field">
                        <label for="settingsComputerName">ชื่อเครื่องที่จะแสดง</label>
                        <input type="text" id="settingsComputerName" placeholder="เช่น ครัวร้อน หรือ KDS 01">
                    </div>
                </div>
            </div>

            <div class="modal-section">
                <div class="modal-section-title">ผู้ใช้งานและการแจ้งเตือน</div>
                <div class="setting-stack">
                    <div class="setting-grid">
                        <div class="setting-field">
                            <label for="settingsFinishStaffId">Finish Staff ID</label>
                            <input type="number" id="settingsFinishStaffId" min="1" placeholder="เช่น 3">
                        </div>
                        <div class="setting-field">
                            <label>ชื่อพนักงาน</label>
                            <div class="settings-status show" id="settingsStaffNameBox">ยังไม่ได้เลือกพนักงาน</div>
                        </div>
                    </div>
                    <div class="setting-grid-2">
                        <div class="modal-row" style="margin-bottom:0">
                            <div class="modal-swatch yellow"></div>
                            <div class="modal-label">เวลาแจ้งเตือนสีเหลือง</div>
                            <input type="number" class="modal-input" id="thresholdYellow" min="1" max="999" value="10">
                            <span class="modal-unit">นาที</span>
                        </div>
                        <div class="modal-row" style="margin-bottom:0">
                            <div class="modal-swatch red"></div>
                            <div class="modal-label">เวลาแจ้งเตือนสีแดง</div>
                            <input type="number" class="modal-input" id="thresholdRed" min="1" max="999" value="20">
                            <span class="modal-unit">นาที</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-section">
                <div class="modal-section-title">ฟีเจอร์</div>
                <div class="setting-stack">
                    <label class="setting-check">
                        <div>
                            <div class="setting-check-title">เปิดเสียงแจ้งเตือน</div>
                            <div class="setting-check-sub">ใช้เสียงแจ้งเตือนเมื่อมีออเดอร์ใหม่เข้ามาในจอ Checker</div>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px"><input type="checkbox" id="soundEnabled"><span id="soundEnabledLabel" style="font-size:13px;color:var(--muted);font-weight:700">ปิดอยู่</span></div>
                    </label>
                    <div class="sound-upload-zone">
                        <div class="sound-upload-row">
                            <span class="sound-name" id="soundFileName">ยังไม่ได้เลือกไฟล์เสียง</span>
                            <button type="button" class="btn btn-neutral sound-preview-btn" id="soundPreviewBtn" disabled>▶ ทดสอบ</button>
                            <button type="button" class="btn btn-danger sound-preview-btn" id="soundClearBtn" style="display:none">✕</button>
                        </div>
                        <div class="sound-upload-row">
                            <label class="btn btn-accent" style="cursor:pointer;min-height:36px;padding:0 14px;display:inline-flex;align-items:center;font-size:13px">
                                📁 เลือกไฟล์เสียง (.mp3 / .wav / .ogg)
                                <input type="file" class="sound-file-input" id="soundFileInput" accept="audio/mp3,audio/wav,audio/ogg,audio/*">
                            </label>
                        </div>
                    </div>
                    <label class="setting-check">
                        <div>
                            <div class="setting-check-title">เปิดสแกนกล้อง</div>
                            <div class="setting-check-sub">ใช้สำหรับเปิดกล้องบนมือถือเพื่อสแกนบาร์โค้ด และเมื่อสแกนเจอจะปิดกล้องให้อัตโนมัติ</div>
                        </div>
                        <input type="checkbox" id="barcodeCameraEnabled">
                    </label>
                    <label class="setting-check">
                        <div>
                            <div class="setting-check-title">เช็ค 2 ชั้น</div>
                            <div class="setting-check-sub">คลิกครั้งแรก = ยืนยันไปครัว, คลิกครั้งที่สอง = checkout จริง</div>
                        </div>
                        <input type="checkbox" id="kdsTwoStepCheckout">
                    </label>
                </div>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn btn-neutral" id="timerSettingsCancelBtn">ยกเลิก</button>
            <button type="button" class="btn btn-success" id="timerSettingsSaveBtn">บันทึกค่าระบบ</button>
        </div>
    </div>

    <div id="notice" class="notice"></div>



    <div class="modal-backdrop" id="soldOutBackdrop"></div>
    <div class="modal" id="soldOutModal" role="dialog" aria-modal="true" aria-labelledby="soldOutTitle">
        <div class="modal-head">
            <h2 class="modal-title" id="soldOutTitle">🥫 ปิดสินค้าหมด</h2>
            <div class="modal-sub">เลือกสินค้าแล้วกดยืนยัน ระบบจะอัปเดตสถานะสินค้าหมดในระบบหลักทันที</div>
        </div>
        <div class="modal-body">
            <div class="modal-section">
                <input type="text" class="soldout-search" id="soldOutSearchInput" placeholder="ค้นหาจากรหัสสินค้า ชื่อสินค้า กลุ่ม หรือหมวดสินค้า">
                <div class="soldout-note">แสดงสินค้าในระบบหลัก สามารถกด <strong>ปิดสินค้าหมด</strong> หรือ <strong>เปิดขาย</strong> ได้จากหน้าจอนี้</div>
            </div>
            <div class="modal-section">
                <div class="soldout-list" id="soldOutList">
                    <div class="empty">ยังไม่ได้โหลดรายการสินค้า</div>
                </div>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn btn-neutral" id="soldOutCloseBtn">ปิด</button>
        </div>
    </div>

    <div class="modal-backdrop" id="barcodeCameraBackdrop"></div>
    <div class="modal camera-modal" id="barcodeCameraModal" role="dialog" aria-modal="true" aria-labelledby="barcodeCameraTitle">
        <div class="modal-head">
            <h2 class="modal-title" id="barcodeCameraTitle">📷 สแกนบาร์โค้ดด้วยกล้อง</h2>
            <div class="modal-sub">ใช้กล้องหน้าเป็นค่าเริ่มต้น และสลับเป็นกล้องหลังได้ตามการใช้งาน</div>
        </div>
        <div class="modal-body">
            <div class="camera-wrap">
                <div class="camera-frame">
                    <video id="barcodeCameraVideo" class="camera-video" playsinline muted autoplay></video>
                    <div class="camera-overlay"><div class="camera-guide"></div></div>
                </div>
                <div class="camera-status" id="barcodeCameraStatus">กดเปิดกล้องแล้วหันไปที่บาร์โค้ดบนใบออเดอร์</div>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn btn-accent" id="switchBarcodeCameraBtn">🔄 สลับเป็นกล้องหลัง</button>
            <button type="button" class="btn btn-neutral" id="closeBarcodeCameraBtn">ปิด</button>
        </div>
    </div>

    <script>
        const refreshMs = <?php echo (int)APP_REFRESH_MS; ?>;
        const finishedRefreshEvery = <?php echo (int)FINISHED_REFRESH_EVERY; ?>;
        const recentFinishedLimit = <?php echo (int)RECENT_FINISHED_LIMIT; ?>;
        const defaultFinishStaffId = <?php echo (int)DEFAULT_FINISH_STAFF_ID; ?>;
        const currentComputerIdFromConfig = <?php echo defined('CURRENT_COMPUTER_ID') ? (int)CURRENT_COMPUTER_ID : 0; ?>;
        const barcodeCheckoutEnabled = <?php echo defined('ENABLE_BARCODE_CHECKOUT') && ENABLE_BARCODE_CHECKOUT ? 'true' : 'false'; ?>;
        const barcodeAutoSubmitDefault = <?php echo defined('BARCODE_AUTO_SUBMIT_DEFAULT') && BARCODE_AUTO_SUBMIT_DEFAULT ? 'true' : 'false'; ?>;
        const barcodeCameraEnabledDefault = <?php echo defined('BARCODE_CAMERA_ENABLED_DEFAULT') && BARCODE_CAMERA_ENABLED_DEFAULT ? 'true' : 'false'; ?>;
        const soundEnabledDefault = <?php echo defined('SOUND_ALERT_ENABLED_DEFAULT') && SOUND_ALERT_ENABLED_DEFAULT ? 'true' : 'false'; ?>;
        const kdsTwoStepCheckoutDefault = <?php echo defined('KDS_TWO_STEP_CHECKOUT_DEFAULT') && KDS_TWO_STEP_CHECKOUT_DEFAULT ? 'true' : 'false'; ?>;
        const thresholdYellowDefault = <?php echo defined('ALERT_THRESHOLD_YELLOW_DEFAULT') ? (int)ALERT_THRESHOLD_YELLOW_DEFAULT : 10; ?>;
        const thresholdRedDefault = <?php echo defined('ALERT_THRESHOLD_RED_DEFAULT') ? (int)ALERT_THRESHOLD_RED_DEFAULT : 20; ?>;
        const barcodeMediaSupported = !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
        const barcodeCameraSupported = !!(window.BarcodeDetector && barcodeMediaSupported);
        const outOfStockControlEnabled = <?php echo defined('ENABLE_OUT_OF_STOCK_CONTROL') && ENABLE_OUT_OF_STOCK_CONTROL ? 'true' : 'false'; ?>;

        let isSubmitting = false;
        let noticeTimer = null;
        let activeRefreshTick = 0;

        const state = {
            stats: { active_rows: 0, active_qty: 0, recent_finished_rows: 0 },
            active_rows: [],
            recent_finished_rows: [],
            filters: { active_today_only: true, finished_today_only: true, current_computer_id: currentComputerIdFromConfig },
            finishedDrawerOpen: false,
            timerSettingsOpen: false,
            soldOutModalOpen: false,
            barcodeCameraOpen: false,
            systemSettingsLoaded: false,
            currentSystemSettings: null,
            soldOutProducts: [],
            soldOutKeyword: '',
            kdsTwoStepCheckout: kdsTwoStepCheckoutDefault
        };

        const barcodeCaptureState = {
            buffer: '',
            lastAt: 0,
            resetTimer: null,
            cameraStream: null,
            cameraDetector: null,
            cameraScanTimer: null,
            lastCameraValue: '',
            awaitingFreshScan: true,
            preferredFacingMode: 'user'
        };

        // ค่าเวลาแจ้งเตือน (โหลดจาก localStorage)
        const timerThresholds = {
            yellow: 10,
            red: 20
        };

        function getBarcodeAutoSubmitStorageKey() {
            return 'checker_barcode_auto_submit_' + String(currentComputerIdFromConfig || 0);
        }

        function getBarcodeCameraEnabledStorageKey() {
            return 'checker_barcode_camera_enabled_' + String(currentComputerIdFromConfig || 0);
        }

        function getBarcodeCameraFacingStorageKey() {
            return 'checker_barcode_camera_facing_' + String(currentComputerIdFromConfig || 0);
        }

        function getPreferredBarcodeFacingMode() {
            const raw = String(localStorage.getItem(getBarcodeCameraFacingStorageKey()) || '').trim();
            return raw === 'environment' ? 'environment' : 'user';
        }

        function savePreferredBarcodeFacingMode(value) {
            const next = value === 'environment' ? 'environment' : 'user';
            localStorage.setItem(getBarcodeCameraFacingStorageKey(), next);
            barcodeCaptureState.preferredFacingMode = next;
        }

        function getBarcodeFacingLabel(value) {
            return value === 'environment' ? 'กล้องหลัง' : 'กล้องหน้า';
        }

        function syncBarcodeCameraSwitchButton() {
            const btn = document.getElementById('switchBarcodeCameraBtn');
            if (!btn) return;
            const current = barcodeCaptureState.preferredFacingMode === 'environment' ? 'environment' : 'user';
            const next = current === 'environment' ? 'user' : 'environment';
            btn.textContent = '🔄 สลับเป็น' + getBarcodeFacingLabel(next);
        }

        function getBarcodeAutoSubmitEnabled() {
            return true;
        }

        function saveBarcodeAutoSubmit(value) {
            return true;
        }

        function getBarcodeCameraEnabled() {
            const raw = localStorage.getItem(getBarcodeCameraEnabledStorageKey());
            if (raw === null) return barcodeCameraEnabledDefault;
            return raw === '1';
        }

        function saveBarcodeCameraEnabled(value) {
            localStorage.setItem(getBarcodeCameraEnabledStorageKey(), value ? '1' : '0');
        }

        function clearBarcodeInput(resetBuffer) {
            const input = document.getElementById('barcodeInput');
            if (input) input.value = '';
            if (resetBuffer !== false) {
                resetGlobalBarcodeBuffer();
            } else {
                barcodeCaptureState.awaitingFreshScan = true;
            }
        }

        function applyBarcodeCameraAvailability() {
            const cameraBtn = document.getElementById('openBarcodeCameraBtn');
            const switchBtn = document.getElementById('switchBarcodeCameraBtn');
            const enabled = barcodeCheckoutEnabled && barcodeCameraSupported && getBarcodeCameraEnabled();
            if (cameraBtn) {
                cameraBtn.style.display = enabled ? '' : 'none';
                cameraBtn.disabled = !enabled;
                cameraBtn.title = barcodeCameraSupported ? '' : 'เบราว์เซอร์นี้ยังไม่รองรับสแกนด้วยกล้อง';
            }
            if (switchBtn) {
                switchBtn.disabled = !enabled;
                syncBarcodeCameraSwitchButton();
            }
        }

        function focusBarcodeInput(selectText) {
            if (state.barcodeCameraOpen) return;
            const input = document.getElementById('barcodeInput');
            if (!input || !barcodeCheckoutEnabled) return;
            window.requestAnimationFrame(function() {
                try { input.focus({ preventScroll: true }); } catch (e) { input.focus(); }
                if (selectText) {
                    try { input.select(); } catch (e) {}
                }
            });
        }

        function isEditableElement(el) {
            if (!el) return false;
            const tag = (el.tagName || '').toUpperCase();
            if (tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT') return true;
            return !!el.isContentEditable;
        }

        function setBarcodeReadyHint(message, variant) {
            return;
        }

        function applyScannedBarcodeValue(value, shouldSubmit, keepFocus) {
            const input = document.getElementById('barcodeInput');
            if (!input) return;
            const clean = String(value || '').trim();
            resetGlobalBarcodeBuffer();
            barcodeCaptureState.buffer = clean;
            barcodeCaptureState.awaitingFreshScan = false;
            input.value = clean;
            if (shouldSubmit) {
                checkoutBarcode();
            } else if (keepFocus === true) {
                focusBarcodeInput(false);
            }
        }

        function resetGlobalBarcodeBuffer() {
            barcodeCaptureState.buffer = '';
            barcodeCaptureState.lastAt = 0;
            barcodeCaptureState.awaitingFreshScan = true;
            if (barcodeCaptureState.resetTimer) {
                clearTimeout(barcodeCaptureState.resetTimer);
                barcodeCaptureState.resetTimer = null;
            }
        }

        function queueGlobalBarcodeDigit(digit) {
            const now = Date.now();
            const isFreshScan = barcodeCaptureState.awaitingFreshScan || (now - barcodeCaptureState.lastAt > 280) || !barcodeCaptureState.buffer;
            if (isFreshScan) {
                const input = document.getElementById('barcodeInput');
                if (input) input.value = '';
                barcodeCaptureState.buffer = '';
                barcodeCaptureState.awaitingFreshScan = false;
            }
            barcodeCaptureState.lastAt = now;
            barcodeCaptureState.buffer += digit;
            const input = document.getElementById('barcodeInput');
            if (input) {
                input.value = barcodeCaptureState.buffer;
            }
            if (barcodeCaptureState.resetTimer) clearTimeout(barcodeCaptureState.resetTimer);
            barcodeCaptureState.resetTimer = setTimeout(function() {
                resetGlobalBarcodeBuffer();
            }, 1200);
        }

        function initBarcodeSettings() {
            const input = document.getElementById('barcodeInput');
            const tools = document.getElementById('barcodeTools');
            barcodeCaptureState.preferredFacingMode = getPreferredBarcodeFacingMode();
            if (!barcodeCheckoutEnabled) {
                if (tools) tools.style.display = 'none';
                return;
            }
            applyBarcodeCameraAvailability();
            if (input) {
                input.setAttribute('autocapitalize', 'off');
                input.setAttribute('autocorrect', 'off');
                input.setAttribute('spellcheck', 'false');
            }
            focusBarcodeInput();
        }

        async function switchBarcodeCameraFacing() {
            const next = barcodeCaptureState.preferredFacingMode === 'environment' ? 'user' : 'environment';
            savePreferredBarcodeFacingMode(next);
            syncBarcodeCameraSwitchButton();
            const status = document.getElementById('barcodeCameraStatus');
            if (status) status.textContent = 'กำลังสลับเป็น' + getBarcodeFacingLabel(next) + '...';
            if (state.barcodeCameraOpen) {
                const shouldRefocus = document.activeElement !== document.getElementById('barcodeInput');
                stopBarcodeCamera();
                await openBarcodeCamera();
                if (shouldRefocus) {
                    focusBarcodeInput();
                }
            }
        }

        function stopBarcodeCamera() {
            if (barcodeCaptureState.cameraScanTimer) {
                cancelAnimationFrame(barcodeCaptureState.cameraScanTimer);
                barcodeCaptureState.cameraScanTimer = null;
            }
            if (barcodeCaptureState.cameraStream) {
                barcodeCaptureState.cameraStream.getTracks().forEach(function(track) { try { track.stop(); } catch (e) {} });
                barcodeCaptureState.cameraStream = null;
            }
            const video = document.getElementById('barcodeCameraVideo');
            if (video) {
                try { video.pause(); } catch (e) {}
                video.srcObject = null;
            }
            const status = document.getElementById('barcodeCameraStatus');
            if (status) status.textContent = 'กดเปิด' + getBarcodeFacingLabel(barcodeCaptureState.preferredFacingMode) + 'แล้วหันไปที่บาร์โค้ดบนใบออเดอร์';
            barcodeCaptureState.lastCameraValue = '';
            syncBarcodeCameraSwitchButton();
            state.barcodeCameraOpen = false;
            const backdrop = document.getElementById('barcodeCameraBackdrop');
            const modal = document.getElementById('barcodeCameraModal');
            if (backdrop) backdrop.classList.remove('open');
            if (modal) modal.classList.remove('open');
            syncOverlayState();
        }

        async function scanBarcodeFrame() {
            if (!state.barcodeCameraOpen) return;
            const video = document.getElementById('barcodeCameraVideo');
            const status = document.getElementById('barcodeCameraStatus');
            if (!video || !barcodeCaptureState.cameraDetector) return;
            try {
                const results = await barcodeCaptureState.cameraDetector.detect(video);
                if (Array.isArray(results) && results.length) {
                    const value = String(results[0].rawValue || '').trim();
                    if (value && value !== barcodeCaptureState.lastCameraValue) {
                        barcodeCaptureState.lastCameraValue = value;
                        if (status) status.innerHTML = '<strong>พบบาร์โค้ด:</strong> ' + escapeHtml(value);
                        applyScannedBarcodeValue(value, true);
                        stopBarcodeCamera();
                        return;
                    }
                }
            } catch (e) {
                if (status) status.textContent = 'กำลังสแกน...';
            }
            barcodeCaptureState.cameraScanTimer = requestAnimationFrame(scanBarcodeFrame);
        }

        async function openBarcodeCamera() {
            if (!getBarcodeCameraEnabled()) {
                showNotice('ปิดปุ่มสแกนกล้องอยู่ในตั้งค่า', 'error');
                return;
            }
            if (!barcodeMediaSupported) {
                showNotice('อุปกรณ์นี้ไม่รองรับการเปิดกล้องจากหน้าเว็บ', 'error');
                return;
            }
            if (!barcodeCameraSupported) {
                showNotice('เบราว์เซอร์นี้ยังไม่รองรับการอ่านบาร์โค้ดจากกล้อง', 'error');
                return;
            }
            const backdrop = document.getElementById('barcodeCameraBackdrop');
            const modal = document.getElementById('barcodeCameraModal');
            const video = document.getElementById('barcodeCameraVideo');
            const status = document.getElementById('barcodeCameraStatus');
            try {
                clearBarcodeInput();
                barcodeCaptureState.lastCameraValue = '';
                barcodeCaptureState.cameraDetector = new BarcodeDetector({ formats: ['code_128', 'ean_13', 'ean_8', 'upc_a', 'upc_e', 'itf', 'codabar'] });
                const preferredFacingMode = barcodeCaptureState.preferredFacingMode === 'environment' ? 'environment' : 'user';
                let stream = null;
                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: { ideal: preferredFacingMode },
                            width: { ideal: 1280 },
                            height: { ideal: 720 }
                        },
                        audio: false
                    });
                } catch (primaryError) {
                    const fallbackFacingMode = preferredFacingMode === 'environment' ? 'user' : 'environment';
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: { ideal: fallbackFacingMode },
                            width: { ideal: 1280 },
                            height: { ideal: 720 }
                        },
                        audio: false
                    });
                    savePreferredBarcodeFacingMode(fallbackFacingMode);
                }
                barcodeCaptureState.cameraStream = stream;
                if (video) {
                    video.srcObject = stream;
                    await video.play();
                }
                if (backdrop) backdrop.classList.add('open');
                if (modal) modal.classList.add('open');
                state.barcodeCameraOpen = true;
                syncBarcodeCameraSwitchButton();
                if (status) status.textContent = getBarcodeFacingLabel(barcodeCaptureState.preferredFacingMode) + 'พร้อมแล้ว หันไปที่บาร์โค้ดเพื่อเช็คเอาต์อัตโนมัติ';
                scanBarcodeFrame();
            } catch (error) {
                stopBarcodeCamera();
                const msg = (error && error.name === 'NotAllowedError')
                    ? 'ยังไม่ได้อนุญาตให้ใช้กล้อง'
                    : 'ไม่สามารถเปิดกล้องได้';
                showNotice(msg, 'error');
            }
        }

        // ── เสียงแจ้งเตือน ──
        const soundSettings = {
            enabled: false,
            audioBuffer: null,   // AudioBuffer จากไฟล์ที่ upload
            audioCtx: null,
            lastKnownProcessIds: null  // set ของ ProcessID ที่รู้จักแล้ว
        };

        function getAudioCtx() {
            if (!soundSettings.audioCtx) {
                soundSettings.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            return soundSettings.audioCtx;
        }

        function playAlertSound() {
            if (!soundSettings.enabled || !soundSettings.audioBuffer) return;
            try {
                const ctx = getAudioCtx();
                if (ctx.state === 'suspended') ctx.resume();
                const src = ctx.createBufferSource();
                src.buffer = soundSettings.audioBuffer;
                src.connect(ctx.destination);
                src.start(0);
            } catch(e) { console.warn('playAlertSound error', e); }
        }

        function checkForNewOrders(newRows) {
            const newIds = new Set(newRows.map(r => String(r.ProcessID)));
            if (soundSettings.lastKnownProcessIds === null) {
                // ครั้งแรก: บันทึกไว้แต่ไม่ดังเสียง
                soundSettings.lastKnownProcessIds = newIds;
                return;
            }
            let hasNew = false;
            newIds.forEach(id => {
                if (!soundSettings.lastKnownProcessIds.has(id)) hasNew = true;
            });
            soundSettings.lastKnownProcessIds = newIds;
            if (hasNew) playAlertSound();
        }

        function initSoundSettings() {
            const rawEnabled = localStorage.getItem('checker_sound_enabled');
            const enabled = rawEnabled === null ? !!soundEnabledDefault : rawEnabled === '1';
            soundSettings.enabled = enabled;
            document.getElementById('soundEnabled').checked = enabled;
            document.getElementById('soundEnabledLabel').textContent = enabled ? 'เปิดอยู่' : 'ปิดอยู่';

            // โหลดไฟล์เสียงที่ cached ใน IndexedDB (ถ้ามี)
            loadCachedSound();
        }

        function initSoundUI() {
            const fileInput = document.getElementById('soundFileInput');
            const enabledChk = document.getElementById('soundEnabled');

            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function(ev) {
                    const ab = ev.target.result;
                    getAudioCtx().decodeAudioData(ab.slice(0), function(buf) {
                        soundSettings.audioBuffer = buf;
                        document.getElementById('soundFileName').textContent = file.name;
                        document.getElementById('soundFileName').className = 'sound-name loaded';
                        document.getElementById('soundPreviewBtn').disabled = false;
                        document.getElementById('soundClearBtn').style.display = '';
                        // บันทึกลง IndexedDB
                        saveSoundToCache(ab, file.name);
                    }, function() {
                        showNotice('ไม่สามารถโหลดไฟล์เสียงนี้ได้', 'error');
                    });
                };
                reader.readAsArrayBuffer(file);
            });

            document.getElementById('soundPreviewBtn').addEventListener('click', function() {
                if (soundSettings.audioBuffer) {
                    const tmp = soundSettings.enabled;
                    soundSettings.enabled = true;
                    playAlertSound();
                    soundSettings.enabled = tmp;
                }
            });

            document.getElementById('soundClearBtn').addEventListener('click', function() {
                soundSettings.audioBuffer = null;
                document.getElementById('soundFileName').textContent = 'ยังไม่ได้เลือกไฟล์เสียง';
                document.getElementById('soundFileName').className = 'sound-name';
                document.getElementById('soundPreviewBtn').disabled = true;
                document.getElementById('soundClearBtn').style.display = 'none';
                document.getElementById('soundFileInput').value = '';
                clearSoundCache();
            });

            enabledChk.addEventListener('change', function() {
                document.getElementById('soundEnabledLabel').textContent = this.checked ? 'เปิดอยู่' : 'ปิดอยู่';
            });
        }

        // ── IndexedDB สำหรับเก็บไฟล์เสียง ──
        function openSoundDB() {
            return new Promise((res, rej) => {
                const req = indexedDB.open('checker_sound_db', 1);
                req.onupgradeneeded = e => e.target.result.createObjectStore('sounds');
                req.onsuccess = e => res(e.target.result);
                req.onerror   = e => rej(e);
            });
        }
        async function saveSoundToCache(arrayBuffer, name) {
            try {
                const db = await openSoundDB();
                const tx = db.transaction('sounds', 'readwrite');
                tx.objectStore('sounds').put({ buffer: arrayBuffer, name }, 'alert');
            } catch(e) { console.warn('saveSoundToCache', e); }
        }
        async function loadCachedSound() {
            try {
                const db = await openSoundDB();
                const tx = db.transaction('sounds', 'readonly');
                const req = tx.objectStore('sounds').get('alert');
                req.onsuccess = function() {
                    const rec = req.result;
                    if (!rec) return;
                    getAudioCtx().decodeAudioData(rec.buffer.slice(0), function(buf) {
                        soundSettings.audioBuffer = buf;
                        document.getElementById('soundFileName').textContent = rec.name;
                        document.getElementById('soundFileName').className = 'sound-name loaded';
                        document.getElementById('soundPreviewBtn').disabled = false;
                        document.getElementById('soundClearBtn').style.display = '';
                    });
                };
            } catch(e) { console.warn('loadCachedSound', e); }
        }
        async function clearSoundCache() {
            try {
                const db = await openSoundDB();
                const tx = db.transaction('sounds', 'readwrite');
                tx.objectStore('sounds').delete('alert');
            } catch(e) {}
        }

        function initTimerThresholds() {
            timerThresholds.yellow = thresholdYellowDefault > 0 ? thresholdYellowDefault : 10;
            timerThresholds.red = thresholdRedDefault > 0 ? thresholdRedDefault : 20;
            const y = Number(localStorage.getItem('checker_threshold_yellow'));
            const r = Number(localStorage.getItem('checker_threshold_red'));
            if (y > 0) timerThresholds.yellow = y;
            if (r > 0) timerThresholds.red = r;
            document.getElementById('thresholdYellow').value = timerThresholds.yellow;
            document.getElementById('thresholdRed').value = timerThresholds.red;
        }

        async function openTimerSettings() {
            document.getElementById('systemSettingsStatusBox').className = 'settings-status';
            document.getElementById('systemSettingsStatusBox').textContent = '';
            state.timerSettingsOpen = true;
            syncTimerSettingsState();
            try {
                const response = await fetch('api_checker.php?action=get_system_settings&_=' + Date.now(), { cache: 'no-store' });
                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error(data.error || 'โหลดค่าระบบไม่สำเร็จ');
                }
                applySystemSettingsToModal(data.settings || {}, data.staff_name || '', data.connection_message || '');
                state.systemSettingsLoaded = true;
                state.currentSystemSettings = data.settings || {};
            } catch (error) {
                showSystemSettingsStatus(error.message || 'โหลดค่าระบบไม่สำเร็จ', 'error');
            }
        }

        function closeTimerSettings() {
            state.timerSettingsOpen = false;
            syncTimerSettingsState();
        }

        async function saveTimerSettings() {
            const payload = collectSystemSettingsFromModal();
            try {
                const response = await fetch('api_checker.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                    body: buildSystemSettingsParams('save_system_settings', payload).toString()
                });
                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error(data.error || 'บันทึกค่าระบบไม่สำเร็จ');
                }
                applySavedSystemSettingsToRuntime(payload, data.staff_name || '');
                showSystemSettingsStatus(data.message || 'บันทึกค่าระบบเรียบร้อยแล้ว', 'success');
                showNotice(data.message || 'บันทึกค่าระบบเรียบร้อยแล้ว', 'success');
                if (typeof data.machine_display_name === 'string') {
                    updateMachineDisplayName(data.machine_display_name);
                }
                setTimeout(function() { window.location.reload(); }, 700);
            } catch (error) {
                showSystemSettingsStatus(error.message || 'บันทึกค่าระบบไม่สำเร็จ', 'error');
            }
        }

        function syncOverlayState() {
            const hasOverlay = !!state.timerSettingsOpen || !!state.finishedDrawerOpen || !!state.barcodeCameraOpen || !!state.soldOutModalOpen;
            document.body.classList.toggle('drawer-open', hasOverlay);
        }

        function syncTimerSettingsState() {
            const modal    = document.getElementById('timerSettingsModal');
            const backdrop = document.getElementById('timerSettingsBackdrop');
            const isOpen   = !!state.timerSettingsOpen;
            modal.classList.toggle('open', isOpen);
            backdrop.classList.toggle('open', isOpen);
            syncOverlayState();
        }

        function openSoldOutModal() {
            state.soldOutModalOpen = true;
            syncSoldOutModalState();
            loadSoldOutProducts(true);
            const input = document.getElementById('soldOutSearchInput');
            if (input) {
                input.value = state.soldOutKeyword || '';
                setTimeout(function(){ try { input.focus({ preventScroll: true }); } catch (e) { input.focus(); } }, 30);
            }
        }

        function closeSoldOutModal() {
            state.soldOutModalOpen = false;
            syncSoldOutModalState();
            focusBarcodeInput();
        }

        function syncSoldOutModalState() {
            const modal = document.getElementById('soldOutModal');
            const backdrop = document.getElementById('soldOutBackdrop');
            const isOpen = !!state.soldOutModalOpen;
            if (modal) modal.classList.toggle('open', isOpen);
            if (backdrop) backdrop.classList.toggle('open', isOpen);
            syncOverlayState();
        }

        async function loadSoldOutProducts(force) {
            if (!outOfStockControlEnabled) return;
            const list = document.getElementById('soldOutList');
            const q = state.soldOutKeyword || '';
            if (list) list.innerHTML = '<div class="empty">กำลังโหลดรายการสินค้า...</div>';
            try {
                const response = await fetch('api_checker.php?action=list_out_of_stock_products&q=' + encodeURIComponent(q) + '&_=' + Date.now(), { cache: 'no-store' });
                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error(data.error || 'โหลดรายการสินค้าไม่สำเร็จ');
                }
                state.soldOutProducts = Array.isArray(data.rows) ? data.rows : [];
                renderSoldOutProducts(state.soldOutProducts);
            } catch (error) {
                if (list) list.innerHTML = '<div class="empty">โหลดรายการสินค้าไม่สำเร็จ</div>';
                showNotice(error.message || 'โหลดรายการสินค้าไม่สำเร็จ', 'error');
            }
        }

        function renderSoldOutProducts(rows) {
            const list = document.getElementById('soldOutList');
            if (!list) return;
            if (!rows || !rows.length) {
                list.innerHTML = '<div class="empty">ไม่พบรายการสินค้า</div>';
                return;
            }
            list.innerHTML = rows.map(function(row){
                const closed = Number(row.IsOutOfStock || 0) === 1;
                const code = row.ProductCode ? escapeHtml(row.ProductCode) : ('#' + escapeHtml(row.ProductID));
                const name = escapeHtml(row.ProductName || row.ProductName1 || ('สินค้า #' + row.ProductID));
                const rawName = String(row.ProductName || row.ProductName1 || ('สินค้า #' + row.ProductID));
                const dept = escapeHtml(row.ProductDeptName || '-');
                const group = escapeHtml(row.ProductGroupName || '-');
                const actionLabel = closed ? 'เปิดขาย' : 'ปิดสินค้าหมด';
                const actionClass = closed ? 'btn btn-neutral btn-small' : 'btn btn-danger btn-small';
                const nextState = closed ? 0 : 1;
                const confirmText = closed ? ('ยืนยันเปิดขาย ' + rawName + ' อีกครั้ง?') : ('ยืนยันปิดสินค้าหมด ' + rawName + ' ?');
                return '<div class="soldout-item ' + (closed ? 'closed' : '') + '">' +
                    '<div>' +
                        '<div class="soldout-meta">' +
                            '<span class="soldout-chip">' + code + '</span>' +
                            '<span class="soldout-chip">' + group + '</span>' +
                            '<span class="soldout-chip">' + dept + '</span>' +
                            '<span class="soldout-chip ' + (closed ? 'closed' : '') + '">' + (closed ? 'ปิดอยู่' : 'เปิดขายอยู่') + '</span>' +
                        '</div>' +
                        '<div class="soldout-name">' + name + '</div>' +
                        '<div class="soldout-sub">Product ID: ' + escapeHtml(row.ProductID) + '</div>' +
                    '</div>' +
                    '<div class="soldout-actions">' +
                        '<button type="button" class="' + actionClass + ' js-toggle-outofstock" data-product-id="' + Number(row.ProductID || 0) + '" data-next-state="' + nextState + '" data-product-name="' + escapeHtml(rawName) + '" data-confirm-text="' + escapeHtml(confirmText) + '">' + actionLabel + '</button>' +
                    '</div>' +
                '</div>';
            }).join('');
        }

        async function toggleOutOfStock(productId, isOutOfStock, productName, confirmText) {
            if (!outOfStockControlEnabled || isSubmitting) return;
            if (!window.confirm(confirmText || 'ยืนยันทำรายการนี้?')) return;
            isSubmitting = true;
            setStatusText(isOutOfStock ? 'กำลังปิดสินค้าหมด...' : 'กำลังเปิดขายสินค้า...');
            try {
                const params = new URLSearchParams();
                params.set('action', 'set_product_out_of_stock');
                params.set('product_id', productId);
                params.set('is_out_of_stock', isOutOfStock ? '1' : '0');
                params.set('update_by', getFinishStaffId());
                const response = await fetch('api_checker.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                    body: params.toString()
                });
                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error(data.error || 'อัปเดตสินค้าหมดไม่สำเร็จ');
                }
                showNotice(data.message || 'อัปเดตสถานะสินค้าแล้ว', 'success');
                closeSoldOutModal();
            } catch (error) {
                showNotice(error.message || 'อัปเดตสินค้าหมดไม่สำเร็จ', 'error');
            } finally {
                isSubmitting = false;
                setStatusText('พร้อมใช้งาน');
                loadSoldOutProducts(true);
            }
        }

        function showSystemSettingsStatus(message, type) {
            const box = document.getElementById('systemSettingsStatusBox');
            if (!box) return;
            const variant = type === 'error' ? 'error' : 'success';
            box.className = 'settings-status show ' + variant;
            box.textContent = String(message || '');
        }

        function updateMachineDisplayName(name) {
            const el = document.getElementById('machineDisplayName');
            if (!el) return;
            const clean = String(name || '').trim();
            el.textContent = clean;
            el.style.display = clean ? 'block' : 'none';
        }

        function setStaffNameBox(name) {
            const box = document.getElementById('settingsStaffNameBox');
            if (!box) return;
            const clean = String(name || '').trim();
            box.textContent = clean ? clean : 'ไม่พบข้อมูลพนักงาน';
            box.className = 'settings-status show ' + (clean ? 'success' : 'error');
        }

        function applySystemSettingsToModal(settings, staffName, connectionMessage) {
            document.getElementById('settingsDbHost').value = String(settings.db_host || '');
            document.getElementById('settingsDbPort').value = Number(settings.db_port || 3306);
            document.getElementById('settingsDbName').value = String(settings.db_name || '');
            document.getElementById('settingsComputerId').value = Number(settings.current_computer_id || 0) || '';
            document.getElementById('settingsComputerName').value = String(settings.current_computer_name || '');
            document.getElementById('settingsFinishStaffId').value = Number(settings.finish_staff_id || 0) || '';
            document.getElementById('thresholdYellow').value = Number(settings.threshold_yellow || thresholdYellowDefault || 10);
            document.getElementById('thresholdRed').value = Number(settings.threshold_red || thresholdRedDefault || 20);
            document.getElementById('soundEnabled').checked = Number(settings.sound_enabled || 0) === 1;
            document.getElementById('soundEnabledLabel').textContent = document.getElementById('soundEnabled').checked ? 'เปิดอยู่' : 'ปิดอยู่';
            const twoStepInput = document.getElementById('kdsTwoStepCheckout');
            if (twoStepInput) twoStepInput.checked = Number(settings.kds_two_step_checkout || 0) === 1;
            const cameraEnabledInput = document.getElementById('barcodeCameraEnabled');
            if (cameraEnabledInput) {
                cameraEnabledInput.checked = Number(settings.barcode_camera_enabled || 0) === 1;
                cameraEnabledInput.disabled = !barcodeCameraSupported;
            }
            const dbUserHint = document.getElementById('dbUserHint');
            if (dbUserHint) {
                dbUserHint.textContent = 'User: ' + String(settings.db_user_hint || '-');
            }
            setStaffNameBox(staffName);
            if (connectionMessage) {
                showSystemSettingsStatus(connectionMessage, connectionMessage.indexOf('เชื่อมต่อ') === 0 ? 'success' : 'error');
            }
        }

        function collectSystemSettingsFromModal() {
            return {
                db_host: String(document.getElementById('settingsDbHost').value || '').trim(),
                db_port: Number(document.getElementById('settingsDbPort').value || 0),
                db_name: String(document.getElementById('settingsDbName').value || '').trim(),
                current_computer_id: Number(document.getElementById('settingsComputerId').value || 0),
                current_computer_name: String(document.getElementById('settingsComputerName').value || '').trim(),
                finish_staff_id: Number(document.getElementById('settingsFinishStaffId').value || 0),
                threshold_yellow: Number(document.getElementById('thresholdYellow').value || 0),
                threshold_red: Number(document.getElementById('thresholdRed').value || 0),
                sound_enabled: document.getElementById('soundEnabled').checked ? 1 : 0,
                barcode_camera_enabled: document.getElementById('barcodeCameraEnabled').checked ? 1 : 0,
                kds_two_step_checkout: document.getElementById('kdsTwoStepCheckout').checked ? 1 : 0
            };
        }

        function buildSystemSettingsParams(action, payload) {
            const params = new URLSearchParams();
            params.set('action', action);
            Object.keys(payload).forEach(function(key) {
                params.set(key, String(payload[key]));
            });
            return params;
        }

        async function testSystemSettingsConnection() {
            const payload = collectSystemSettingsFromModal();
            showSystemSettingsStatus('กำลังทดสอบการเชื่อมต่อ...', 'success');
            try {
                const response = await fetch('api_checker.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                    body: buildSystemSettingsParams('test_system_settings_connection', payload).toString()
                });
                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error(data.error || 'ทดสอบการเชื่อมต่อไม่สำเร็จ');
                }
                if (typeof data.staff_name === 'string') {
                    setStaffNameBox(data.staff_name);
                }
                showSystemSettingsStatus(data.message || 'เชื่อมต่อสำเร็จ', 'success');
            } catch (error) {
                showSystemSettingsStatus(error.message || 'ทดสอบการเชื่อมต่อไม่สำเร็จ', 'error');
                setStaffNameBox('');
            }
        }

        async function lookupStaffNameForSettings() {
            const staffId = Number(document.getElementById('settingsFinishStaffId').value || 0);
            if (staffId <= 0) {
                setStaffNameBox('');
                return;
            }
            try {
                const response = await fetch('api_checker.php?action=lookup_staff_name&staff_id=' + encodeURIComponent(staffId) + '&_=' + Date.now(), { cache: 'no-store' });
                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error(data.error || 'ไม่สามารถตรวจสอบชื่อพนักงานได้');
                }
                setStaffNameBox(data.staff_name || '');
            } catch (error) {
                setStaffNameBox('');
            }
        }

        function applySavedSystemSettingsToRuntime(payload, staffName) {
            localStorage.setItem('checker_finish_staff_id', String(payload.finish_staff_id || defaultFinishStaffId));
            localStorage.setItem('checker_threshold_yellow', String(payload.threshold_yellow || thresholdYellowDefault));
            localStorage.setItem('checker_threshold_red', String(payload.threshold_red || thresholdRedDefault));
            localStorage.setItem('checker_sound_enabled', payload.sound_enabled ? '1' : '0');
            saveBarcodeCameraEnabled(!!payload.barcode_camera_enabled);
            timerThresholds.yellow = Number(payload.threshold_yellow || thresholdYellowDefault || 10);
            timerThresholds.red = Number(payload.threshold_red || thresholdRedDefault || 20);
            soundSettings.enabled = !!payload.sound_enabled;
            state.kdsTwoStepCheckout = !!payload.kds_two_step_checkout;
            applyBarcodeCameraAvailability();
            setStaffNameBox(staffName || '');
        }

        function initFinishStaffId() {
            const saved = localStorage.getItem('checker_finish_staff_id');
            if (saved && Number(saved) > 0) {
                return;
            }
            localStorage.setItem('checker_finish_staff_id', String(defaultFinishStaffId));
        }

        function saveFinishStaffId() {
            showNotice('กรุณาตั้งค่า Finish Staff ID ผ่านปุ่มตั้งค่าระบบ', 'success');
        }

        function getFinishStaffId() {
            const raw = Number(localStorage.getItem('checker_finish_staff_id') || 0);
            return raw > 0 ? raw : defaultFinishStaffId;
        }
        function applyFilterInfo(filters) {
            state.filters = Object.assign({}, state.filters, filters || {});
        }

        async function loadAll() {
            const results = await Promise.all([
                loadActiveRows(),
                loadFinishedRows({ silent: true })
            ]);

            const activeOk = !!results[0];
            const finishedOk = !!results[1];
            if (activeOk && !finishedOk) {
                setStatusText('พร้อมใช้งาน');
                console.warn('โหลดรายการเสร็จไม่สำเร็จ แต่คิวหลักยังใช้งานได้');
            }
        }

        async function loadActiveRows(options) {
            options = options || {};
            const silent = !!options.silent;
            try {
                setStatusText('กำลังโหลด...');
                const response = await fetch('api_checker.php?action=list_active&_=' + Date.now(), { cache: 'no-store' });
                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error(data.error || 'โหลดคิวไม่สำเร็จ');
                }

                state.stats.active_rows = Number((data.stats || {}).active_rows || 0);
                state.stats.active_qty = Number((data.stats || {}).active_qty || 0);
                state.active_rows = Array.isArray(data.active_rows) ? data.active_rows : [];
                applyFilterInfo(data.filters || {});
                checkForNewOrders(state.active_rows);
                updateView();
                setStatusText('พร้อมใช้งาน');
                return true;
            } catch (error) {
                setStatusText('เกิดข้อผิดพลาด');
                if (!silent) {
                    showNotice(error.message || 'โหลดคิวไม่สำเร็จ', 'error');
                }
                return false;
            }
        }

        async function loadFinishedRows(options) {
            options = options || {};
            const silent = !!options.silent;
            try {
                const response = await fetch('api_checker.php?action=list_finished&_=' + Date.now(), { cache: 'no-store' });
                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error(data.error || 'โหลดรายการเสร็จไม่สำเร็จ');
                }

                state.recent_finished_rows = Array.isArray(data.recent_finished_rows) ? data.recent_finished_rows : [];
                state.stats.recent_finished_rows = state.recent_finished_rows.length;
                applyFilterInfo(data.filters || {});
                updateView();
                return true;
            } catch (error) {
                if (!silent) {
                    showNotice(error.message || 'โหลดรายการเสร็จไม่สำเร็จ', 'error');
                }
                return false;
            }
        }

        function updateView() {
            renderStats(state.stats || {});
            renderActiveRows(state.active_rows || []);
            renderRecentFinished(state.recent_finished_rows || []);
            syncDrawerState();
        }
        function renderFilterInfo() {
        }

        function renderStats(stats) {
            document.getElementById('statActiveRows').textContent = Number(stats.active_rows || 0);
            document.getElementById('statActiveQty').textContent = formatQty(stats.active_qty || 0);
            const finishedCount = Number(stats.recent_finished_rows || 0);
            document.getElementById('fabFinishedCount').textContent = finishedCount;
            document.getElementById('topFinishedCount').textContent = finishedCount;
        }
        function renderComments(comments, compact) {
            const list = Array.isArray(comments) ? comments : [];
            if (!list.length) {
                return '';
            }

            const normalItems = [];
            const pricedItems = [];

            list.forEach(function(comment) {
                const type = Number(comment.type || 0);
                const amount = Number(comment.amount || 0);
                const suffix = amount > 1 ? ' x' + formatQty(amount) : '';
                const rawText = String(comment.text || '').trim();
                if (!rawText) {
                    return;
                }
                const text = escapeHtml(rawText) + suffix;
                if (type === 15) {
                    pricedItems.push(text);
                } else {
                    normalItems.push(text);
                }
            });

            if (compact) {
                const blocks = [];
                if (normalItems.length) {
                    blocks.push(`
                        <div class="comment-group">
                            <span class="comment-group-label">คอมเมนต์</span>
                            <span class="comment-group-items">${normalItems.join(', ')}</span>
                        </div>
                    `);
                }
                if (pricedItems.length) {
                    blocks.push(`
                        <div class="comment-group priced">
                            <span class="comment-group-label">คอมเมนต์เพิ่มราคา</span>
                            <span class="comment-group-items">${pricedItems.join(', ')}</span>
                        </div>
                    `);
                }
                return blocks.length ? `<div class="comment-list compact">${blocks.join('')}</div>` : '';
            }

            const lines = [];
            if (normalItems.length) {
                lines.push(`<div class="comment-inline"><span class="label">คอมเมนต์:</span>${normalItems.join(', ')}</div>`);
            }
            if (pricedItems.length) {
                lines.push(`<div class="comment-inline priced"><span class="label">คอมเมนต์เพิ่มราคา:</span>${pricedItems.join(', ')}</div>`);
            }
            return lines.length ? `<div class="product-comments-inline">${lines.join('')}</div>` : '';
        }

        function buildActiveProductTotals(rows) {
            const totals = {};
            (Array.isArray(rows) ? rows : []).forEach(function(row) {
                const key = String(row && row.ProductName ? row.ProductName : '').trim();
                if (!key) {
                    return;
                }
                const qty = Number(row && row.ProductAmount ? row.ProductAmount : 0) || 0;
                totals[key] = (totals[key] || 0) + qty;
            });
            return totals;
        }

        function renderActiveRows(rows) {
            const wrap = document.getElementById('activeCards');
            const productTotals = buildActiveProductTotals(rows);
            document.getElementById('queueSummary').textContent = rows.length ? ('ค้าง ' + rows.length + ' แถว') : 'ไม่มีคิวค้าง';

            if (!rows.length) {
                wrap.innerHTML = '<div class="empty">ไม่มีรายการค้างของวันนี้ในครัว</div>';
                return;
            }

            wrap.innerHTML = rows.map(function(row) {
                const waitMinutes = getMinutesDiff(row.SubmitOrderDateTime);
                const isVoided   = !!row.is_voided;
                const isMoved    = !!row.is_moved;
                const isCombined = !!row.is_combined;
                const movedTo    = row.moved_to || '';
                const yMin = timerThresholds.yellow;
                const rMin = timerThresholds.red;
                const isConfirmed = Number(row.ProcessStatus || 0) === 2;
                const isTwoStepMode = !!state.kdsTwoStepCheckout;
                const checkoutTone = isTwoStepMode ? (isConfirmed ? 'dark' : 'soft') : 'dark';

                // กำหนด class การ์ด
                let cardClass    = 'card';
                let waitTagClass = 'tag good';
                let qtyBadgeClass = 'qty-badge checkout-' + checkoutTone;
                if (isVoided) {
                    cardClass = 'card voided';
                    waitTagClass = 'tag';
                    qtyBadgeClass = 'qty-badge';
                } else if (isMoved) {
                    cardClass = 'card moved';
                    waitTagClass = 'tag';
                    qtyBadgeClass = 'qty-badge checkout-dark';
                } else if (isCombined) {
                    cardClass = 'card combined';
                    waitTagClass = 'tag';
                    qtyBadgeClass = 'qty-badge';
                } else if (waitMinutes >= rMin) {
                    cardClass = 'card warn-red';
                    waitTagClass = 'tag wait';
                } else if (waitMinutes >= yMin) {
                    cardClass = 'card warn-yellow';
                    waitTagClass = 'tag wait';
                } else {
                    cardClass = 'card checkout-' + checkoutTone;
                }

                const tableText = row.DisplayTableName || row.TableID || '-';

                // badge สถานะพิเศษ
                let statusBadge = '';
                if (isVoided) {
                    statusBadge = `<div class="status-badge voided">🚫 ยกเลิกแล้ว</div>`;
                } else if (isMoved) {
                    statusBadge = `<div class="status-badge moved">🔀 ย้ายไปโต๊ะ ${escapeHtml(movedTo)}</div>`;
                } else if (isCombined) {
                    statusBadge = `<div class="status-badge combined">🔗 รวมโต๊ะแล้ว</div>`;
                }

                const actionButtons = [];
                if (isVoided) {
                    actionButtons.push(`
                        <button
                            class="btn btn-warning js-resolve-status"
                            data-product-level-id="${Number(row.ProductLevelID || 0)}"
                            data-process-id="${Number(row.ProcessID || 0)}"
                            data-sub-process-id="${Number(row.SubProcessID || 0)}"
                            data-printer-id="${Number(row.PrinterID || 0)}"
                            ${isSubmitting ? 'disabled' : ''}
                        >จบสถานะ</button>
                    `);
                } else if (!isCombined) {
                    actionButtons.push(`
                        <button
                            class="btn btn-checkout-${checkoutTone} js-checkout"
                            data-product-level-id="${Number(row.ProductLevelID || 0)}"
                            data-process-id="${Number(row.ProcessID || 0)}"
                            data-sub-process-id="${Number(row.SubProcessID || 0)}"
                            data-printer-id="${Number(row.PrinterID || 0)}"
                            ${isSubmitting ? 'disabled' : ''}
                        >${state.kdsTwoStepCheckout ? (isConfirmed ? 'Checkout 1 รายการ' : 'ยืนยันไปครัว') : (isMoved ? 'Checkout' : 'Checkout 1 รายการ')}</button>
                    `);
                }

                const actionHtml = actionButtons.length ? `<div class="card-actions">${actionButtons.join('')}</div>` : '';
                const productKey = String(row.ProductName || '').trim();
                const totalQtyForProduct = productKey && Object.prototype.hasOwnProperty.call(productTotals, productKey)
                    ? productTotals[productKey]
                    : Number(row.ProductAmount || 0);
                const totalQtyHint = totalQtyForProduct > Number(row.ProductAmount || 0)
                    ? `<div class="product-total-hint">รวมทั้งคิว ${formatQty(totalQtyForProduct)}</div>`
                    : '';

                return `
                    <article class="${cardClass}">
                        <div class="card-head">
                            <div>
                                <div class="table-name">โต๊ะ ${escapeHtml(tableText)}</div>
                            </div>
                        </div>

                        ${statusBadge}

                        <div class="product-block">
                            ${row.parent_name ? `<div class="parent-name-label">${escapeHtml(row.parent_name)}</div>` : ''}
                            <h3 class="product-name">${formatQty(row.ProductAmount)}x ${escapeHtml(row.ProductName || '-')}</h3>
                            ${totalQtyHint}
                            ${renderComments(row.comments || [], false)}
                        </div>

                        <div class="queue-tags">
                            <span class="${waitTagClass}">⏱️ รอ ${waitMinutes} นาที</span>
                            <span class="tag">${escapeHtml(row.SaleModeName || '-')}</span>
                        </div>

                        <div class="grid">
                            <div class="field">
                                <div class="field-label">ส่งเข้าเมื่อ</div>
                                <div class="field-value">${escapeHtml(formatTime(row.SubmitOrderDateTime))}</div>
                            </div>
                            ${isVoided ? `
                                <div class="field is-special">
                                    <div class="field-label">ยกเลิกเมื่อ</div>
                                    <div class="field-value">${escapeHtml(formatTime(row.FinishDateTime))}</div>
                                </div>
                            ` : ''}
                        </div>

                        ${actionHtml}
                    </article>
                `;
            }).join('');
        }

        function renderRecentFinished(rows) {
            const listWrap = document.getElementById('recentFinishedList');

            if (!rows.length) {
                listWrap.innerHTML = '<div class="empty">ยังไม่มีรายการเสร็จวันนี้</div>';
                return;
            }

            listWrap.innerHTML = rows.map(function(row) {
                const tableText = row.DisplayTableName || row.TableID || '-';
                return `
                    <div class="finished-item">
                        <div class="finished-top">
                            <div>
                                ${row.parent_name ? `<div class="parent-name-label" style="margin-bottom:6px">${escapeHtml(row.parent_name)}</div>` : ''}
                                <div class="finished-name">${escapeHtml(row.ProductName || '-')}</div>
                                <div class="finished-meta">โต๊ะ ${escapeHtml(tableText)}</div>
                            </div>
                            <div class="finished-qty">x${formatQty(row.ProductAmount)}</div>
                        </div>
                        ${renderComments(row.comments || [], true)}
                        <div class="finished-meta">
                            เสร็จเมื่อ: ${escapeHtml(formatDateTime(row.FinishDateTime))}<br>
                            Finish Staff ID: ${escapeHtml(row.FinishStaffID || '-')}
                        </div>
                        <button
                            class="btn btn-neutral js-undo"
                            data-product-level-id="${Number(row.ProductLevelID || 0)}"
                            data-process-id="${Number(row.ProcessID || 0)}"
                            data-sub-process-id="${Number(row.SubProcessID || 0)}"
                            data-printer-id="${Number(row.PrinterID || 0)}"
                            ${isSubmitting ? 'disabled' : ''}
                        >ย้อนกลับ 1 รายการ</button>
                    </div>
                `;
            }).join('');
        }

        function openFinishedDrawer() {

            state.finishedDrawerOpen = true;
            syncDrawerState();
        }

        function closeFinishedDrawer() {
            state.finishedDrawerOpen = false;
            syncDrawerState();
        }

        function syncDrawerState() {
            const drawer = document.getElementById('finishedDrawer');
            const backdrop = document.getElementById('finishedDrawerBackdrop');
            const isOpen = !!state.finishedDrawerOpen;
            drawer.classList.toggle('open', isOpen);
            backdrop.classList.toggle('open', isOpen);
            drawer.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
            document.body.classList.toggle('drawer-open', isOpen);
        }

        function findActiveRow(productLevelId, processId, subProcessId, printerId) {
            return (state.active_rows || []).find(function(row) {
                return Number(row.ProductLevelID) === Number(productLevelId)
                    && Number(row.ProcessID) === Number(processId)
                    && Number(row.SubProcessID) === Number(subProcessId)
                    && Number(row.PrinterID) === Number(printerId);
            }) || null;
        }

        function markRowConfirmedInState(row) {
            if (!row) return;
            state.active_rows = (state.active_rows || []).map(function(item) {
                const isSame = Number(item.ProductLevelID) === Number(row.ProductLevelID)
                    && Number(item.ProcessID) === Number(row.ProcessID)
                    && Number(item.SubProcessID) === Number(row.SubProcessID)
                    && Number(item.PrinterID) === Number(row.PrinterID);
                if (!isSame) return item;
                return Object.assign({}, item, { ProcessStatus: 2 });
            });
        }

        function findFinishedRow(productLevelId, processId, subProcessId, printerId) {
            return (state.recent_finished_rows || []).find(function(row) {
                return Number(row.ProductLevelID) === Number(productLevelId)
                    && Number(row.ProcessID) === Number(processId)
                    && Number(row.SubProcessID) === Number(subProcessId)
                    && Number(row.PrinterID) === Number(printerId);
            }) || null;
        }

        function applyCheckoutToState(row) {
            if (!row) return;
            const qty = Number(row.ProductAmount || 0);
            const nextActiveRows = [];
            let removedRow = false;

            (state.active_rows || []).forEach(function(item) {
                const isSame = Number(item.ProductLevelID) === Number(row.ProductLevelID)
                    && Number(item.ProcessID) === Number(row.ProcessID)
                    && Number(item.SubProcessID) === Number(row.SubProcessID)
                    && Number(item.PrinterID) === Number(row.PrinterID);

                if (!isSame) {
                    nextActiveRows.push(item);
                    return;
                }

                if (qty > 1) {
                    nextActiveRows.push(Object.assign({}, item, {
                        ProductAmount: Number(item.ProductAmount || 0) - 1
                    }));
                } else {
                    removedRow = true;
                }
            });

            state.active_rows = nextActiveRows;
            state.stats.active_qty = Math.max(0, Number(state.stats.active_qty || 0) - 1);
            if (removedRow || qty <= 1) {
                state.stats.active_rows = Math.max(0, Number(state.stats.active_rows || 0) - 1);
            }

            const finishedItem = Object.assign({}, row, {
                ProductAmount: 1,
                FinishDateTime: new Date().toISOString().slice(0, 19).replace('T', ' '),
                FinishStaffID: getFinishStaffId(),
                ProcessStatus: 1
            });
            state.recent_finished_rows = [finishedItem].concat(state.recent_finished_rows || []);
            if (recentFinishedLimit > 0) {
                state.recent_finished_rows = state.recent_finished_rows.slice(0, recentFinishedLimit);
            }
            state.stats.recent_finished_rows = state.recent_finished_rows.length;
            updateView();
        }

        function applyUndoToState(row) {
            if (!row) return;

            state.recent_finished_rows = (state.recent_finished_rows || []).filter(function(item) {
                return !(Number(item.ProductLevelID) === Number(row.ProductLevelID)
                    && Number(item.ProcessID) === Number(row.ProcessID)
                    && Number(item.SubProcessID) === Number(row.SubProcessID)
                    && Number(item.PrinterID) === Number(row.PrinterID));
            });
            state.stats.recent_finished_rows = state.recent_finished_rows.length;

            let merged = false;
            state.active_rows = (state.active_rows || []).map(function(item) {
                const isSameGroup = Number(item.ProductLevelID) === Number(row.ProductLevelID)
                    && Number(item.ProcessID) === Number(row.ProcessID)
                    && Number(item.PrinterID) === Number(row.PrinterID)
                    && Number(item.ProcessStatus) !== 1;
                if (isSameGroup && !merged) {
                    merged = true;
                    return Object.assign({}, item, {
                        ProductAmount: Number(item.ProductAmount || 0) + Number(row.ProductAmount || 0)
                    });
                }
                return item;
            });

            if (!merged) {
                state.active_rows.push(Object.assign({}, row, {
                    FinishDateTime: null,
                    FinishStaffID: 0,
                    ProcessStatus: 0
                }));
                state.stats.active_rows = Number(state.stats.active_rows || 0) + 1;
            }

            state.stats.active_qty = Number(state.stats.active_qty || 0) + Number(row.ProductAmount || 0);
            updateView();
        }

        async function checkoutOne(productLevelId, processId, subProcessId, printerId) {
            if (isSubmitting) return;
            const clickedRow = findActiveRow(productLevelId, processId, subProcessId, printerId);
            if (!clickedRow) {
                await loadActiveRows();
                return;
            }

            const isTwoStepEnabled = !!state.kdsTwoStepCheckout;
            const currentStatus = Number(clickedRow.ProcessStatus || 0);
            const shouldConfirmFirst = isTwoStepEnabled && currentStatus !== 2;

            isSubmitting = true;
            setStatusText(shouldConfirmFirst ? 'กำลังยืนยันรายการ...' : 'กำลัง checkout...');

            try {
                const params = new URLSearchParams();
                params.set('action', shouldConfirmFirst ? 'confirm_one' : 'checkout_one');
                params.set('ProductLevelID', productLevelId);
                params.set('ProcessID', processId);
                params.set('SubProcessID', subProcessId);
                params.set('PrinterID', printerId);
                params.set('finish_staff_id', getFinishStaffId());

                const response = await fetch('api_checker.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                    body: params.toString()
                });
                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error(data.error || 'checkout ไม่สำเร็จ');
                }

                if (shouldConfirmFirst) {
                    markRowConfirmedInState(clickedRow);
                    showNotice(data.message || 'ยืนยันรายการแล้ว', 'success');
                    setStatusText('อัปเดตแล้ว');
                    updateView();
                    setTimeout(loadActiveRows, 120);
                } else {
                    applyCheckoutToState(clickedRow);
                    showNotice(data.message || 'checkout สำเร็จ', 'success');
                    setStatusText('อัปเดตแล้ว');
                    setTimeout(loadActiveRows, 120);
                    if (data.refresh_finished) {
                        setTimeout(loadFinishedRows, 160);
                    }
                }
            } catch (error) {
                setStatusText('เกิดข้อผิดพลาด');
                showNotice(error.message || 'checkout ไม่สำเร็จ', 'error');
                loadAll();
            } finally {
                isSubmitting = false;
                updateView();
            }
        }


        async function checkoutBarcode() {
            if (!barcodeCheckoutEnabled || isSubmitting) return;
            const input = document.getElementById('barcodeInput');
            if (!input) return;
            const barcode = String(input.value || '').trim();
            if (!barcode) {
                showNotice('กรุณาสแกนหรือกรอกรหัสบาร์โค้ด', 'error');
                focusBarcodeInput();
                return;
            }

            isSubmitting = true;
            setStatusText('กำลังค้นหา Barcode...');

            try {
                const params = new URLSearchParams();
                params.set('action', 'checkout_barcode');
                params.set('barcode', barcode);
                params.set('finish_staff_id', getFinishStaffId());

                const response = await fetch('api_checker.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                    body: params.toString()
                });
                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error(data.error || data.message || 'Barcode not found');
                }

                clearBarcodeInput();
                showNotice(data.message || 'checkout สำเร็จ', 'success');
                setStatusText('อัปเดตแล้ว');
                await loadAll();
                focusBarcodeInput();
            } catch (error) {
                setStatusText('เกิดข้อผิดพลาด');
                setBarcodeReadyHint('ไม่พบบาร์โค้ด', 'error');
                clearBarcodeInput();
                showNotice(error.message || 'Barcode not found', 'error');
                focusBarcodeInput();
            } finally {
                isSubmitting = false;
                updateView();
            }
        }

        async function resolveStatus(productLevelId, processId, subProcessId, printerId) {
            if (isSubmitting) return;
            const clickedRow = findActiveRow(productLevelId, processId, subProcessId, printerId);
            if (!clickedRow) {
                await loadActiveRows();
                return;
            }

            isSubmitting = true;
            setStatusText('กำลังจบสถานะ...');

            try {
                const params = new URLSearchParams();
                params.set('action', 'resolve_status');
                params.set('ProductLevelID', productLevelId);
                params.set('ProcessID', processId);
                params.set('SubProcessID', subProcessId);
                params.set('PrinterID', printerId);
                params.set('finish_staff_id', getFinishStaffId());

                const response = await fetch('api_checker.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                    body: params.toString()
                });
                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error(data.error || 'จบสถานะไม่สำเร็จ');
                }

                state.active_rows = (state.active_rows || []).filter(function(item) {
                    return !(Number(item.ProductLevelID) === Number(productLevelId)
                        && Number(item.ProcessID) === Number(processId)
                        && Number(item.SubProcessID) === Number(subProcessId)
                        && Number(item.PrinterID) === Number(printerId));
                });
                state.stats.active_rows = Math.max(0, Number(state.stats.active_rows || 0) - 1);
                state.stats.active_qty = Math.max(0, Number(state.stats.active_qty || 0) - Number(clickedRow.ProductAmount || 0));

                showNotice(data.message || 'จบสถานะสำเร็จ', 'success');
                setStatusText('อัปเดตแล้ว');
                updateView();
                setTimeout(loadActiveRows, 120);
            } catch (error) {
                setStatusText('เกิดข้อผิดพลาด');
                showNotice(error.message || 'จบสถานะไม่สำเร็จ', 'error');
                loadAll();
            } finally {
                isSubmitting = false;
                updateView();
            }
        }

        async function undoOne(productLevelId, processId, subProcessId, printerId) {
            if (isSubmitting) return;
            const clickedRow = findFinishedRow(productLevelId, processId, subProcessId, printerId);
            isSubmitting = true;
            setStatusText('กำลังย้อนกลับ...');

            try {
                const params = new URLSearchParams();
                params.set('action', 'undo_one');
                params.set('ProductLevelID', productLevelId);
                params.set('ProcessID', processId);
                params.set('SubProcessID', subProcessId);
                params.set('PrinterID', printerId);

                const response = await fetch('api_checker.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                    body: params.toString()
                });
                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error(data.error || 'ย้อนกลับไม่สำเร็จ');
                }

                if (clickedRow) {
                    applyUndoToState(clickedRow);
                }
                showNotice(data.message || 'ย้อนกลับสำเร็จ', 'success');
                setStatusText('อัปเดตแล้ว');
                setTimeout(loadActiveRows, 120);
                if (data.refresh_finished) {
                    setTimeout(loadFinishedRows, 160);
                }
            } catch (error) {
                setStatusText('เกิดข้อผิดพลาด');
                showNotice(error.message || 'ย้อนกลับไม่สำเร็จ', 'error');
                loadAll();
            } finally {
                isSubmitting = false;
                updateView();
            }
        }

        function setStatusText(text) {
            document.getElementById('statStatusText').textContent = text;
        }

        function showNotice(message, type) {
            const box = document.getElementById('notice');
            box.className = 'notice ' + (type || 'success');
            box.textContent = message;
            clearTimeout(noticeTimer);
            noticeTimer = setTimeout(function() {
                box.className = 'notice';
                box.textContent = '';
            }, 2600);
        }

        function formatQty(value) {
            const num = Number(value || 0);
            return Number.isInteger(num) ? String(num) : num.toFixed(2);
        }

        function formatDateTime(value) {
            if (!value) return '-';
            const safe = String(value).replace(' ', 'T');
            const dt = new Date(safe);
            if (Number.isNaN(dt.getTime())) return String(value);
            return dt.toLocaleString('th-TH', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }

        function formatTime(value) {
            if (!value) return '-';
            const safe = String(value).replace(' ', 'T');
            const dt = new Date(safe);
            if (Number.isNaN(dt.getTime())) {
                const raw = String(value);
                const parts = raw.split(' ');
                return parts.length > 1 ? parts[1] : raw;
            }
            return dt.toLocaleTimeString('th-TH', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }

        function formatDate(value) {
            if (!value) return '-';
            const dt = new Date(String(value));
            if (Number.isNaN(dt.getTime())) return String(value);
            return dt.toLocaleDateString('th-TH', {
                year: 'numeric', month: '2-digit', day: '2-digit'
            });
        }

        function getMinutesDiff(value) {
            if (!value) return 0;
            const safe = String(value).replace(' ', 'T');
            const dt = new Date(safe);
            if (Number.isNaN(dt.getTime())) return 0;
            return Math.max(0, Math.floor((Date.now() - dt.getTime()) / 60000));
        }

        function jsEscape(value) {
            return String(value || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'");
        }

        function escapeHtml(value) {
            return String(value == null ? '' : value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function escapeAttribute(value) {
            return escapeHtml(value).replace(/`/g, '&#096;');
        }

        document.getElementById('refreshBtn').addEventListener('click', loadAll);
        const openSystemSettingsBtn = document.getElementById('openSystemSettingsBtn');
        if (openSystemSettingsBtn) {
            openSystemSettingsBtn.addEventListener('click', openTimerSettings);
        }
        const barcodeInput = document.getElementById('barcodeInput');
        if (barcodeInput) {
            barcodeInput.addEventListener('focus', function() {
                resetGlobalBarcodeBuffer();
            });
            barcodeInput.addEventListener('input', function() {
                barcodeCaptureState.buffer = String(barcodeInput.value || '').trim();
                barcodeCaptureState.lastAt = Date.now();
                barcodeCaptureState.awaitingFreshScan = !barcodeCaptureState.buffer;
            });
            barcodeInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && getBarcodeAutoSubmitEnabled()) {
                    e.preventDefault();
                    checkoutBarcode();
                }
            });
        }
        const openBarcodeCameraBtn = document.getElementById('openBarcodeCameraBtn');
        if (openBarcodeCameraBtn) {
            openBarcodeCameraBtn.addEventListener('click', openBarcodeCamera);
        }
        const closeBarcodeCameraBtn = document.getElementById('closeBarcodeCameraBtn');
        if (closeBarcodeCameraBtn) {
            closeBarcodeCameraBtn.addEventListener('click', function() {
                stopBarcodeCamera();
                focusBarcodeInput();
            });
        }
        const switchBarcodeCameraBtn = document.getElementById('switchBarcodeCameraBtn');
        if (switchBarcodeCameraBtn) {
            switchBarcodeCameraBtn.addEventListener('click', function() {
                switchBarcodeCameraFacing();
            });
        }
        const barcodeCameraBackdrop = document.getElementById('barcodeCameraBackdrop');
        if (barcodeCameraBackdrop) {
            barcodeCameraBackdrop.addEventListener('click', function() {
                stopBarcodeCamera();
                focusBarcodeInput();
            });
        }
        const soldOutBtn = document.getElementById('openSoldOutBtn');
        if (soldOutBtn) soldOutBtn.addEventListener('click', openSoldOutModal);
        const soldOutCloseBtn = document.getElementById('soldOutCloseBtn');
        if (soldOutCloseBtn) soldOutCloseBtn.addEventListener('click', closeSoldOutModal);
        const soldOutBackdrop = document.getElementById('soldOutBackdrop');
        if (soldOutBackdrop) soldOutBackdrop.addEventListener('click', closeSoldOutModal);
        const soldOutSearchInput = document.getElementById('soldOutSearchInput');
        if (soldOutSearchInput) {
            soldOutSearchInput.addEventListener('input', function() {
                state.soldOutKeyword = String(this.value || '').trim();
                if (window.__soldOutSearchTimer) clearTimeout(window.__soldOutSearchTimer);
                window.__soldOutSearchTimer = setTimeout(function() { loadSoldOutProducts(true); }, 220);
            });
        }
        const soldOutListEl = document.getElementById('soldOutList');
        if (soldOutListEl) {
            soldOutListEl.addEventListener('click', function(event) {
                const btn = event.target.closest('.js-toggle-outofstock');
                if (!btn) return;
                toggleOutOfStock(
                    Number(btn.getAttribute('data-product-id') || 0),
                    Number(btn.getAttribute('data-next-state') || 0),
                    btn.getAttribute('data-product-name') || '',
                    btn.getAttribute('data-confirm-text') || ''
                );
            });
        }
        document.getElementById('timerSettingsCancelBtn').addEventListener('click', closeTimerSettings);
        document.getElementById('timerSettingsSaveBtn').addEventListener('click', saveTimerSettings);
        document.getElementById('testDbConnectionBtn').addEventListener('click', testSystemSettingsConnection);
        document.getElementById('settingsFinishStaffId').addEventListener('input', function(){
            if (window.__settingsStaffTimer) clearTimeout(window.__settingsStaffTimer);
            window.__settingsStaffTimer = setTimeout(lookupStaffNameForSettings, 220);
        });
        document.getElementById('timerSettingsBackdrop').addEventListener('click', closeTimerSettings);
        document.getElementById('finishedDrawerBackdrop').addEventListener('click', closeFinishedDrawer);

        document.addEventListener('click', function(event) {
            const checkoutBtn = event.target.closest('.js-checkout');
            if (checkoutBtn) {
                checkoutOne(
                    checkoutBtn.dataset.productLevelId,
                    checkoutBtn.dataset.processId,
                    checkoutBtn.dataset.subProcessId,
                    checkoutBtn.dataset.printerId
                );
                return;
            }

            const resolveBtn = event.target.closest('.js-resolve-status');
            if (resolveBtn) {
                resolveStatus(
                    resolveBtn.dataset.productLevelId,
                    resolveBtn.dataset.processId,
                    resolveBtn.dataset.subProcessId,
                    resolveBtn.dataset.printerId
                );
                return;
            }

            const undoBtn = event.target.closest('.js-undo');
            if (undoBtn) {
                undoOne(
                    undoBtn.dataset.productLevelId,
                    undoBtn.dataset.processId,
                    undoBtn.dataset.subProcessId,
                    undoBtn.dataset.printerId
                );
                return;
            }

            if (event.target.closest('.js-open-finished')) {
                openFinishedDrawer();
                if (!(state.recent_finished_rows || []).length) {
                    loadFinishedRows();
                }
                return;
            }

            if (event.target.closest('.js-close-finished')) {
                closeFinishedDrawer();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                if (state.barcodeCameraOpen) { stopBarcodeCamera(); focusBarcodeInput(); return; }
                if (state.timerSettingsOpen) { closeTimerSettings(); focusBarcodeInput(); return; }
                if (state.finishedDrawerOpen) { closeFinishedDrawer(); focusBarcodeInput(); }
                return;
            }

            if (!barcodeCheckoutEnabled || state.barcodeCameraOpen) return;
            if (event.ctrlKey || event.altKey || event.metaKey) return;
            const target = event.target;
            const targetIsBarcode = target && target.id === 'barcodeInput';
            if (isEditableElement(target) && !targetIsBarcode) return;

            if (/^[0-9]$/.test(event.key)) {
                if (targetIsBarcode) {
                    return;
                }
                queueGlobalBarcodeDigit(event.key);
                event.preventDefault();
                return;
            }

            if (event.key === 'Enter') {
                const input = document.getElementById('barcodeInput');
                if (getBarcodeAutoSubmitEnabled() && input && String(input.value || '').trim()) {
                    event.preventDefault();
                    checkoutBarcode();
                }
            }
        });

        document.addEventListener('click', function(event) {
            const target = event.target;
            if (!barcodeCheckoutEnabled || state.barcodeCameraOpen) return;
            if (target.closest('#timerSettingsModal') || target.closest('#barcodeCameraModal')) return;
            if (isEditableElement(target) && target.id !== 'barcodeInput') return;
            if (target.closest('.drawer')) return;
            if (target.id === 'barcodeInput') return;
            focusBarcodeInput(false);
        });

        initFinishStaffId();
        state.kdsTwoStepCheckout = kdsTwoStepCheckoutDefault;
        initTimerThresholds();
        initSoundSettings();
        initSoundUI();
        initBarcodeSettings();
        loadAll().then(function() {
            focusBarcodeInput();
        });

        setInterval(function() {
            if (isSubmitting) return;
            activeRefreshTick += 1;
            loadActiveRows();
            if (state.finishedDrawerOpen || activeRefreshTick % finishedRefreshEvery === 0) {
                loadFinishedRows();
            }
        }, refreshMs);
    </script>
</body>
</html>
