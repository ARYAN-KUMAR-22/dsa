<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Merge Sort - Interactive Visualizer</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fira+Code:wght@400;500&display=swap"
    rel="stylesheet" />
  <style>
    :root {
      --bg: #0d1117;
      --surface: #161b27;
      --card: #1d2436;
      --panel: #12161f;
      --border: #2b3550;
      --accent: #06b6d4;
      --accent-2: #67e8f9;
      --good: #22c55e;
      --warn: #f59e0b;
      --danger: #ef4444;
      --text: #e2e8f0;
      --muted: #8b98b6;
      --shadow: 0 18px 50px rgba(0, 0, 0, 0.25);
    }

    body.light-mode {
      --bg: #f5f7ff;
      --panel: #ffffff;
      --surface: #eef2ff;
      --card: #e8effe;
      --border: rgba(99, 102, 241, 0.20);
      --text: #1e2a45;
      --muted: #52637a;
      --shadow: 0 18px 45px rgba(0, 0, 0, 0.10);
      --glow: rgba(99, 102, 241, 0.10);
      --focus: rgba(99, 102, 241, 0.20);
      --accent: #0e7490;
      --accent-2: #0e7490;
      --good: #15803d;
      --warn: #b45309;
      --danger: #b91c1c;
    }
    body.light-mode header {
      background: var(--panel);
      border-bottom-color: var(--border);
    }

    body.light-mode .left-panel {
      background: var(--panel);
      border-right-color: var(--border);
    }
    body.light-mode .tabs {
      background: var(--panel);
    }
    body.light-mode .tab:hover {
      background: var(--surface);
      color: var(--text);
    }
    body.light-mode .tab.active {
      background: var(--surface);
    }
    body.light-mode .metric {
      background: var(--surface);
    }
    body.light-mode .field {
      background: var(--surface);
    }
    body.light-mode .card {
      background: var(--card);
    }
    body.light-mode code {
      color: var(--text);
    }
    body.light-mode .panel-section {
      border-bottom-color: var(--border);
    }




    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Inter', sans-serif;
      height: 100vh;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    header {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 12px 24px;
      border-bottom: 1px solid var(--border);
      background: linear-gradient(90deg, #00141a, #0d1117);
      flex-shrink: 0;
    }

    header h1 {
      font-size: 1.2rem;
      font-weight: 700;
    }

    header h1 span {
      color: var(--accent);
    }

    header p {
      font-size: 0.78rem;
      color: var(--muted);
    }

    .speed-label {
      margin-left: auto;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.78rem;
      color: var(--muted);
    }

    .speed-label input {
      accent-color: var(--accent);
    }

    .app-body {
      flex: 1;
      display: flex;
      min-height: 0;
    }

    .left-panel {
      width: 290px;
      min-width: 290px;
      background: var(--panel);
      border-right: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    .panel-header {
      padding: 10px 14px 8px;
      border-bottom: 1px solid var(--border);
      font-size: 0.7rem;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      font-weight: 700;
      color: var(--accent);
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .pulse-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: var(--good);
      box-shadow: 0 0 8px var(--good);
      animation: pulse 1.3s infinite;
      flex-shrink: 0;
    }

    @keyframes pulse {
      0%, 100% {
        opacity: 1;
        transform: scale(1);
      }

      50% {
        opacity: 0.45;
        transform: scale(0.84);
      }
    }

    .panel-section {
      border-bottom: 1px solid var(--border);
      display: flex;
      flex-direction: column;
    }

    .panel-section-title {
      font-size: 0.67rem;
      letter-spacing: 0.07em;
      text-transform: uppercase;
      font-weight: 700;
      color: var(--muted);
      padding: 8px 14px 4px;
    }

    .summary-grid {
      padding: 8px 14px 12px;
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 8px;
    }

    .metric {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 10px;
      box-shadow: var(--shadow);
    }

    .metric-label {
      font-size: 0.64rem;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: 4px;
    }

    .metric-value {
      font-family: 'Fira Code', monospace;
      font-size: 0.9rem;
      color: var(--text);
    }

    .step-box {
      padding: 8px 14px 12px;
      display: flex;
      flex-direction: column;
      gap: 5px;
      min-height: 128px;
    }

    .step-item {
      display: flex;
      gap: 8px;
      line-height: 1.45;
      font-size: 0.77rem;
    }

    .step-num {
      min-width: 18px;
      font-family: 'Fira Code', monospace;
      color: var(--accent-2);
      font-size: 0.66rem;
      padding-top: 1px;
    }

    .step-item.done .step-num {
      color: var(--good);
    }

    .step-item.done .step-text {
      color: var(--muted);
      text-decoration: line-through;
    }

    .step-item.active .step-text {
      color: var(--warn);
      font-weight: 600;
    }

    .step-item.pending .step-text {
      color: #42506d;
    }

    .log-scroll {
      flex: 1;
      overflow-y: auto;
      padding: 6px 14px 12px;
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .log-scroll::-webkit-scrollbar {
      width: 4px;
    }

    .log-scroll::-webkit-scrollbar-thumb {
      background: var(--border);
      border-radius: 999px;
    }

    .log-entry {
      font-family: 'Fira Code', monospace;
      font-size: 0.71rem;
      line-height: 1.55;
      border-left: 2px solid transparent;
      padding: 4px 8px;
      border-radius: 6px;
    }

    .log-entry.info {
      color: var(--accent-2);
      border-color: var(--accent);
      background: rgba(6, 182, 212, 0.08);
    }

    .log-entry.warn {
      color: #fcd34d;
      border-color: var(--warn);
      background: rgba(245, 158, 11, 0.08);
    }

    .log-entry.success {
      color: #86efac;
      border-color: var(--good);
      background: rgba(34, 197, 94, 0.08);
    }

    .log-entry .ts {
      color: #46516d;
      margin-right: 6px;
    }

    .workspace {
      flex: 1;
      min-width: 0;
      min-height: 0;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      background:
        radial-gradient(circle at top right, rgba(6, 182, 212, 0.09), transparent 28%),
        radial-gradient(circle at bottom left, rgba(103, 232, 249, 0.07), transparent 30%),
        var(--bg);
    }

    .tabs {
      display: flex;
      gap: 2px;
      padding: 10px 18px 0;
      border-bottom: 1px solid var(--border);
      background: var(--panel);
      flex-shrink: 0;
      overflow-x: auto;
    }

    .tabs::-webkit-scrollbar {
      height: 5px;
    }

    .tabs::-webkit-scrollbar-thumb {
      background: var(--border);
      border-radius: 999px;
    }

    .tab-btn {
      padding: 9px 18px;
      border: none;
      background: transparent;
      color: var(--muted);
      border-radius: 8px 8px 0 0;
      cursor: pointer;
      font-size: 0.82rem;
      font-family: 'Inter', sans-serif;
      font-weight: 600;
      transition: all 0.2s ease;
      white-space: nowrap;
      border-bottom: 2px solid transparent;
    }

    .tab-btn:hover {
      color: var(--text);
      background: var(--surface);
      transform: none;
    }

    .tab-btn.active {
      color: var(--accent);
      border-bottom-color: var(--accent);
      background: var(--card);
    }

    .tab-panel {
      display: none;
      flex: 1;
      min-height: 0;
      overflow-y: auto;
    }

    .tab-panel.active {
      display: block;
    }

    .tab-panel.visualizer-panel.active {
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    .tab-panel::-webkit-scrollbar {
      width: 5px;
    }

    .tab-panel::-webkit-scrollbar-thumb {
      background: var(--border);
      border-radius: 999px;
    }

    .control-row {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      padding: 16px 18px 12px;
      border-bottom: 1px solid var(--border);
      align-items: center;
    }

    .field {
      display: flex;
      align-items: center;
      gap: 8px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 10px 12px;
    }

    .field span {
      font-size: 0.72rem;
      letter-spacing: 0.07em;
      text-transform: uppercase;
      color: var(--muted);
      font-weight: 700;
    }

    .field input {
      width: 240px;
      background: transparent;
      border: none;
      color: var(--text);
      font-family: 'Fira Code', monospace;
      font-size: 0.8rem;
      outline: none;
    }

    .button-row {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-left: auto;
    }

    button {
      border: 1px solid var(--border);
      background: var(--surface);
      color: var(--text);
      border-radius: 10px;
      padding: 10px 14px;
      font-size: 0.8rem;
      font-weight: 600;
      font-family: 'Inter', sans-serif;
      cursor: pointer;
      transition: transform 0.16s ease, border-color 0.16s ease, background 0.16s ease;
    }

    button:hover {
      transform: translateY(-1px);
      border-color: var(--accent);
    }

    button.primary {
      background: linear-gradient(135deg, var(--accent), #67e8f9);
      color: #06222b;
      border-color: transparent;
    }

    button.ghost {
      background: transparent;
    }

    .viz-area {
      min-height: 0;
      display: grid;
      grid-template-columns: 1.4fr 0.8fr;
      gap: 16px;
      padding: 16px 18px;
    }

    .canvas-card,
    .code-card,
    .footer-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 18px;
      box-shadow: var(--shadow);
    }

    .canvas-card {
      display: flex;
      flex-direction: column;
      min-height: 0;
      overflow: hidden;
    }

    .card-head {
      padding: 14px 16px 10px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
    }

    .card-title {
      font-size: 0.9rem;
      font-weight: 700;
    }

    .card-subtitle {
      font-size: 0.75rem;
      color: var(--muted);
    }

    .legend {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      font-size: 0.72rem;
      color: var(--muted);
    }

    .legend-item {
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .legend-swatch {
      width: 10px;
      height: 10px;
      border-radius: 999px;
    }

    .bars-wrap {
      flex: 1;
      min-height: 260px;
      height: clamp(260px, 38vh, 340px);
      padding: 22px 18px 18px;
      display: flex;
      align-items: flex-end;
      gap: 10px;
      overflow-y: hidden;
      overflow-x: auto;
    }

    .bars-wrap::-webkit-scrollbar,
    .buffer-wrap::-webkit-scrollbar {
      height: 6px;
    }

    .bars-wrap::-webkit-scrollbar-thumb,
    .buffer-wrap::-webkit-scrollbar-thumb {
      background: var(--border);
      border-radius: 999px;
    }

    .bar-col {
      flex: 1 0 52px;
      min-width: 52px;
      max-width: 72px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-end;
      gap: 10px;
      height: calc(clamp(260px, 38vh, 340px) - 40px);
    }

    .bar {
      width: 100%;
      border-radius: 12px 12px 4px 4px;
      background: linear-gradient(180deg, #2563eb, #0f172a);
      position: relative;
      transition: height 0.35s ease, transform 0.25s ease, background 0.25s ease, box-shadow 0.25s ease, opacity 0.25s ease;
      border: 1px solid rgba(255, 255, 255, 0.06);
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    .bar.active-range {
      background: linear-gradient(180deg, #1d4ed8, var(--accent));
      box-shadow: 0 0 14px rgba(6, 182, 212, 0.16);
    }

    .bar.left-run {
      background: linear-gradient(180deg, #38bdf8, #0ea5e9);
      box-shadow: 0 0 16px rgba(56, 189, 248, 0.18);
      transform: translateY(-4px);
    }

    .bar.right-run {
      background: linear-gradient(180deg, #2dd4bf, #0f766e);
      box-shadow: 0 0 16px rgba(45, 212, 191, 0.18);
      transform: translateY(-4px);
    }

    .bar.compare {
      background: linear-gradient(180deg, #f97316, var(--warn));
      transform: translateY(-8px);
      box-shadow: 0 0 18px rgba(245, 158, 11, 0.2);
    }

    .bar.write-target {
      background: linear-gradient(180deg, #fb7185, var(--danger));
      transform: translateY(-10px);
      box-shadow: 0 0 18px rgba(244, 63, 94, 0.22);
    }

    .bar.merged {
      background: linear-gradient(180deg, #34d399, #16a34a);
      box-shadow: 0 0 18px rgba(34, 197, 94, 0.2);
    }

    .bar.sorted {
      background: linear-gradient(180deg, #4ade80, var(--good));
      box-shadow: 0 0 18px rgba(74, 222, 128, 0.22);
    }

    .bar-label {
      font-family: 'Fira Code', monospace;
      font-size: 0.74rem;
      color: var(--text);
      text-align: center;
    }

    .index-badge {
      font-size: 0.64rem;
      color: var(--muted);
      font-family: 'Fira Code', monospace;
    }

    .buffer-panel {
      border-top: 1px solid var(--border);
      padding: 12px 16px 14px;
      background: var(--card);
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .buffer-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      flex-wrap: wrap;
    }

    .buffer-title {
      font-size: 0.76rem;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--accent-2);
      font-weight: 700;
    }

    .buffer-subtitle {
      font-size: 0.74rem;
      color: var(--muted);
    }

    .buffer-wrap {
      display: flex;
      gap: 8px;
      overflow-x: auto;
      min-height: 58px;
      padding-bottom: 2px;
    }

    .buffer-empty {
      min-height: 58px;
      width: 100%;
      border-radius: 14px;
      border: 1px dashed var(--border);
      background: var(--surface);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.78rem;
      color: var(--muted);
    }

    .buffer-cell {
      min-width: 58px;
      border-radius: 14px;
      border: 1px dashed var(--border);
      background: var(--surface);
      padding: 8px 10px;
      display: flex;
      flex-direction: column;
      gap: 6px;
      align-items: center;
      justify-content: center;
      transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease;
    }

    .buffer-cell.filled {
      border-style: solid;
      border-color: rgba(103, 232, 249, 0.28);
      background: rgba(6, 182, 212, 0.12);
    }

    .buffer-cell.active {
      transform: translateY(-4px);
      border-color: var(--accent);
      box-shadow: 0 0 16px rgba(6, 182, 212, 0.16);
    }

    .buffer-index {
      font-size: 0.64rem;
      color: var(--muted);
      font-family: 'Fira Code', monospace;
    }

    .buffer-value {
      font-size: 0.86rem;
      color: var(--text);
      font-family: 'Fira Code', monospace;
    }

    .code-card {
      display: flex;
      flex-direction: column;
      min-height: 0;
    }

    .code-wrap {
      padding: 14px 16px 16px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      min-height: 0;
      overflow-y: auto;
    }

    .code-wrap::-webkit-scrollbar {
      width: 5px;
    }

    .code-wrap::-webkit-scrollbar-thumb {
      background: var(--border);
      border-radius: 999px;
    }

    .code-line {
      display: grid;
      grid-template-columns: 28px 1fr;
      gap: 10px;
      font-family: 'Fira Code', monospace;
      font-size: 0.78rem;
      line-height: 1.55;
      color: #cbd5e1;
      padding: 7px 8px;
      border-radius: 10px;
      border: 1px solid transparent;
    }

    .code-line.active {
      background: rgba(6, 182, 212, 0.08);
      border-color: rgba(6, 182, 212, 0.28);
      color: #f8fafc;
    }

    .line-no {
      color: #4f5d79;
      text-align: right;
      user-select: none;
    }

    .explain-box {
      margin-top: auto;
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 14px;
    }

    .explain-box h3 {
      font-size: 0.76rem;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--accent-2);
      margin-bottom: 8px;
    }

    .explain-box p {
      font-size: 0.8rem;
      color: var(--text);
      line-height: 1.6;
    }

    .bottom-row {
      padding: 0 18px 18px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      flex-shrink: 0;
    }

    .theory-grid {
      padding: 18px;
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 16px;
    }

    .theory-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 18px;
      box-shadow: var(--shadow);
      padding: 18px;
    }

    .theory-card h3 {
      font-size: 0.95rem;
      margin-bottom: 10px;
      color: var(--accent-2);
    }

    .theory-card p,
    .theory-card li {
      font-size: 0.82rem;
      line-height: 1.65;
      color: var(--text);
    }

    .theory-card ul {
      padding-left: 18px;
      display: grid;
      gap: 8px;
    }

    .theory-card code {
      font-family: 'Fira Code', monospace;
      color: var(--accent-2);
    }

    .footer-card {
      padding: 14px 16px;
    }

    .footer-card h3 {
      font-size: 0.76rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--accent-2);
      margin-bottom: 10px;
    }

    .badge-row {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    .badge {
      padding: 7px 10px;
      border-radius: 999px;
      background: var(--card);
      border: 1px solid var(--border);
      font-size: 0.74rem;
      color: var(--text);
    }

    .status-line {
      font-size: 0.84rem;
      line-height: 1.6;
      color: var(--text);
    }

    .status-line strong {
      color: var(--accent-2);
    }

    @media (max-width: 1180px) {
      .viz-area {
        grid-template-columns: 1fr;
      }

      .bottom-row {
        grid-template-columns: 1fr;
      }

      .theory-grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 900px) {
      body {
        overflow: auto;
      }

      .app-body {
        flex-direction: column;
      }

      .left-panel {
        width: 100%;
        min-width: 0;
        max-height: none;
        border-right: none;
        border-bottom: 1px solid var(--border);
      }

      .workspace {
        display: block;
      }

      .tab-panel.visualizer-panel.active {
        display: block;
        overflow-y: auto;
      }

      .field input {
        width: 180px;
      }
    }

    @media (max-width: 640px) {
      header {
        flex-wrap: wrap;
      }

      .speed-label {
        margin-left: 0;
      }

      .control-row {
        padding: 14px;
      }

      .field {
        width: 100%;
      }

      .field input {
        width: 100%;
      }

      .button-row {
        margin-left: 0;
      }

      .viz-area,
      .bottom-row {
        padding-left: 14px;
        padding-right: 14px;
      }
    }
  
    .dsa-theme-toggle {
      position: fixed;
      bottom: 18px;
      right: 18px;
      z-index: 9999;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 14px;
      border-radius: 999px;
      border: 1px solid var(--border);
      background: var(--panel, #fff);
      color: var(--text);
      font-family: inherit;
      font-size: 0.80rem;
      font-weight: 600;
      cursor: pointer;
      box-shadow: 0 4px 14px rgba(0,0,0,0.25);
      transition: transform 0.18s ease, border-color 0.18s ease, background 0.18s ease;
    }
    .dsa-theme-toggle:hover {
      transform: translateY(-2px);
      border-color: var(--accent, #7dd3fc);
      background: var(--surface, #f0f4ff);
    }

  </style>

<script>if(window.self !== window.top) { document.write('<style>header, .left-panel { display: none !important; }</style>'); }</script>
</head>
<body>
<a href="../dashboard.php?view=02_Sorting_Algorithms/index.php" style="position:fixed; top:20px; left:20px; z-index:9999999; padding:10px 20px; background:#ef4444; color:white; font-family:'Inter', sans-serif; font-size:14px; text-decoration:none; border-radius:8px; box-shadow:0 8px 15px rgba(0,0,0,0.2); font-weight:600; display:flex; align-items:center; gap:8px; transition:transform 0.2s;">&larr; Back to Dashboard</a>

  <header>
    <div>
      <h1><span>Merge Sort</span> Visualizer</h1>
      <p>Watch recursive splits, temporary buffers, and stable merges rebuild the array.</p>
    </div>
    <label class="speed-label">
      Speed
      <input id="speedRange" type="range" min="1" max="100" value="55" />
    </label>
  </header>

  <div class="app-body">
    <aside class="left-panel">
      <div class="panel-header">
        <span class="pulse-dot"></span>
        Merge Runtime Feed
      </div>

      <section class="panel-section">
        <div class="panel-section-title">Live Metrics</div>
        <div class="summary-grid">
          <div class="metric">
            <div class="metric-label">Split</div>
            <div class="metric-value" id="splitCount">0</div>
          </div>
          <div class="metric">
            <div class="metric-label">Merge</div>
            <div class="metric-value" id="mergeCount">0</div>
          </div>
          <div class="metric">
            <div class="metric-label">Compare</div>
            <div class="metric-value" id="compareCount">0</div>
          </div>
          <div class="metric">
            <div class="metric-label">Write</div>
            <div class="metric-value" id="writeCount">0</div>
          </div>
        </div>
      </section>

      <section class="panel-section">
        <div class="panel-section-title">Algorithm Flow</div>
        <div class="step-box" id="stepBox"></div>
      </section>

      <section class="panel-section" style="flex:1; min-height: 180px;">
        <div class="panel-section-title">Execution Log</div>
        <div class="log-scroll" id="logBox"></div>
      </section>
    </aside>

    <main class="workspace">
      <nav class="tabs">
        <button class="tab-btn active" onclick="showTab('tab-visualizer', this)">Animation</button>
        <button class="tab-btn" onclick="showTab('tab-theory', this)">Theory</button>
        <button class="tab-btn" onclick="showTab('tab-comparison', this)">Comparison</button>
      </nav>

      <section id="tab-visualizer" class="tab-panel visualizer-panel active">
        <section class="control-row">
          <label class="field">
            <span>Input</span>
            <input id="inputArray" type="text" value="38, 27, 43, 3, 9, 82, 10, 14"
              aria-label="Enter comma separated numbers" />
          </label>

          <div class="button-row">
            <button class="ghost" id="randomBtn">Randomize</button>
            <button id="loadBtn">Load Array</button>
            <button class="primary" id="playBtn">Start Sort</button>
            <button id="stepBtn">Next Step</button>
            <button id="resetBtn">Reset</button>
          </div>
        </section>

        <section class="viz-area">
          <article class="canvas-card">
            <div class="card-head">
              <div>
                <div class="card-title">Bar Animation</div>
                <div class="card-subtitle" id="statusText">Ready to simulate Merge Sort.</div>
              </div>
              <div class="legend">
                <span class="legend-item"><span class="legend-swatch" style="background:#38bdf8;"></span> Left half</span>
                <span class="legend-item"><span class="legend-swatch" style="background:#2dd4bf;"></span> Right half</span>
                <span class="legend-item"><span class="legend-swatch" style="background:#f59e0b;"></span> Comparing</span>
                <span class="legend-item"><span class="legend-swatch" style="background:#f43f5e;"></span> Buffer write</span>
                <span class="legend-item"><span class="legend-swatch" style="background:#22c55e;"></span> Merged back</span>
              </div>
            </div>
            <div class="bars-wrap" id="barsWrap"></div>
            <div class="buffer-panel">
              <div class="buffer-head">
                <div class="buffer-title">Merge Buffer</div>
                <div class="buffer-subtitle" id="bufferStatus">Waiting for the first merge.</div>
              </div>
              <div class="buffer-wrap" id="bufferWrap"></div>
            </div>
          </article>

          <article class="code-card">
            <div class="card-head">
              <div>
                <div class="card-title">Pseudo Code</div>
                <div class="card-subtitle">Current line follows the active animation step.</div>
              </div>
            </div>
            <div class="code-wrap" id="codeWrap"></div>
          </article>
        </section>

        <section class="bottom-row">
          <article class="footer-card">
            <h3>Complexity</h3>
            <div class="badge-row">
              <span class="badge">Best: O(n log n)</span>
              <span class="badge">Average: O(n log n)</span>
              <span class="badge">Worst: O(n log n)</span>
              <span class="badge">Space: O(n)</span>
              <span class="badge">Stable: Yes</span>
            </div>
          </article>

          <article class="footer-card">
            <h3>Current Insight</h3>
            <p class="status-line" id="insightText">
              <strong>Merge Sort</strong> keeps dividing the array into smaller sorted pieces, then merges them back in
              order.
            </p>
          </article>
        </section>
      </section>

      <section id="tab-theory" class="tab-panel">
        <div class="theory-grid">
          <article class="theory-card">
            <h3>How Merge Sort Works</h3>
            <ul>
              <li>Split the array into two halves until each range contains only one element.</li>
              <li>Those single elements are already sorted, so merge sort works upward from the bottom.</li>
              <li>During each merge, compare the front values of the left and right halves.</li>
              <li>Take the smaller value into a temporary buffer, then copy the merged result back.</li>
              <li>Because equal values keep their relative order, merge sort is stable.</li>
            </ul>
          </article>

          <article class="theory-card">
            <h3>Pseudo Logic</h3>
            <ul>
              <li><code>mergeSort(left, right)</code></li>
              <li><code>if left &gt;= right return</code></li>
              <li><code>mid = floor((left + right) / 2)</code></li>
              <li><code>mergeSort(left, mid)</code></li>
              <li><code>mergeSort(mid + 1, right)</code></li>
              <li><code>merge(left, mid, right)</code></li>
            </ul>
          </article>

          <article class="theory-card">
            <h3>Merge Pseudo Code</h3>
            <ul>
              <li><code>merge(left, mid, right)</code></li>
              <li><code>copy left and right halves</code></li>
              <li><code>while both halves still have values</code></li>
              <li><code>take the smaller front value into temp</code></li>
              <li><code>append the remaining values</code></li>
              <li><code>write temp back into a[left..right]</code></li>
            </ul>
          </article>

          <article class="theory-card">
            <h3>When It Is Useful</h3>
            <ul>
              <li>Reliable <code>O(n log n)</code> performance makes it a strong default when worst-case guarantees matter.</li>
              <li>Its stability is useful when equal values should keep their original order.</li>
              <li>It works especially well for linked lists and external sorting with large datasets.</li>
            </ul>
          </article>

          <article class="theory-card">
            <h3>Key Tradeoffs</h3>
            <ul>
              <li>Merge sort needs extra memory for temporary arrays, so it is not in-place in the usual array version.</li>
              <li>It performs more copying than in-place algorithms like quick sort.</li>
              <li>In return, its runtime stays predictably <code>O(n log n)</code> in every case.</li>
            </ul>
          </article>
        </div>
      </section>

      <section id="tab-comparison" class="tab-panel">
        <div class="theory-grid">
          <article class="theory-card">
            <h3>Merge Sort At a Glance</h3>
            <ul>
              <li>Merge Sort is <code>stable</code> and gives consistent <code>O(n log n)</code> time in all major cases.</li>
              <li>Its main cost is the extra <code>O(n)</code> memory used during merging.</li>
              <li>It is one of the most reliable comparison sorts when predictable performance matters.</li>
            </ul>
          </article>

          <article class="theory-card">
            <h3>Compared With Quick Sort</h3>
            <ul>
              <li><strong>Quick Sort</strong> often wins in average in-memory speed and uses less extra space.</li>
              <li><strong>Merge Sort</strong> gives stronger worst-case guarantees and keeps equal values in order.</li>
              <li>If stability matters, Merge Sort is usually the better choice.</li>
            </ul>
          </article>

          <article class="theory-card">
            <h3>Compared With Bubble, Insertion, and Selection</h3>
            <ul>
              <li>Merge Sort scales far better once arrays become moderate or large.</li>
              <li>The simple quadratic sorts can still be useful for tiny arrays or teaching, but they do not compete on big inputs.</li>
              <li>Merge Sort trades implementation simplicity for much stronger growth behavior.</li>
            </ul>
          </article>

          <article class="theory-card">
            <h3>Best Fit</h3>
            <ul>
              <li>Choose Merge Sort when you need stable output and dependable <code>O(n log n)</code> performance.</li>
              <li>It is especially attractive for linked lists, external sorting, and large datasets.</li>
              <li>If memory is very tight, an in-place alternative like Quick Sort may be more appealing.</li>
            </ul>
          </article>
        </div>
      </section>
    </main>
  </div>

  <script>
    const defaultArray = [38, 27, 43, 3, 9, 82, 10, 14];
    const maxBars = 14;
    const minValue = 5;
    const maxValue = 99;

    const stepsTemplate = [
      'Divide the current range into left and right halves.',
      'Stop when a range has one element.',
      'Prepare the two sorted halves for merging.',
      'Take the smaller front value into the merge buffer.',
      'Copy the merged range back into the array.'
    ];

    const pseudoCode = [
      'mergeSort(left, right)',
      '  if left >= right return',
      '  mid = floor((left + right) / 2)',
      '  mergeSort(left, mid)',
      '  mergeSort(mid + 1, right)',
      '  merge(left, mid, right)',
      '',
      'merge(left, mid, right)',
      '  copy left and right halves',
      '  while both halves have values',
      '    if leftValue <= rightValue take leftValue',
      '    else take rightValue',
      '  append remaining values',
      '  write merged values back to a[left..right]'
    ];

    const ui = {
      barsWrap: document.getElementById('barsWrap'),
      bufferWrap: document.getElementById('bufferWrap'),
      bufferStatus: document.getElementById('bufferStatus'),
      inputArray: document.getElementById('inputArray'),
      playBtn: document.getElementById('playBtn'),
      stepBtn: document.getElementById('stepBtn'),
      resetBtn: document.getElementById('resetBtn'),
      randomBtn: document.getElementById('randomBtn'),
      loadBtn: document.getElementById('loadBtn'),
      speedRange: document.getElementById('speedRange'),
      splitCount: document.getElementById('splitCount'),
      mergeCount: document.getElementById('mergeCount'),
      compareCount: document.getElementById('compareCount'),
      writeCount: document.getElementById('writeCount'),
      stepBox: document.getElementById('stepBox'),
      logBox: document.getElementById('logBox'),
      codeWrap: document.getElementById('codeWrap'),
      statusText: document.getElementById('statusText'),
      insightText: document.getElementById('insightText')
    };

    let state = {
      original: [...defaultArray],
      arr: [...defaultArray],
      actions: [],
      actionIndex: 0,
      running: false,
      timer: null,
      splitCount: 0,
      mergeCount: 0,
      compareCount: 0,
      writeCount: 0,
      activeLow: null,
      activeHigh: null,
      mid: null,
      leftLow: null,
      leftHigh: null,
      rightLow: null,
      rightHigh: null,
      compareLeftIndex: null,
      compareRightIndex: null,
      writeIndex: null,
      mergedIndices: [],
      sortedIndices: [],
      bufferValues: [],
      bufferWriteIndex: null,
      currentLine: -1,
      currentStep: 0,
      lastInsight: 'Ready to sort.'
    };

    function init() {
      renderSteps();
      renderPseudoCode();
      loadArray(defaultArray, 'Loaded default Merge Sort sample.');
      bindEvents();
    }

    function bindEvents() {
      ui.randomBtn.addEventListener('click', randomizeArray);
      ui.loadBtn.addEventListener('click', () => {
        const parsed = parseInput(ui.inputArray.value);
        if (!parsed.length) {
          pushLog('warn', 'Invalid input. Enter up to 14 comma separated integers.');
          return;
        }
        loadArray(parsed, 'Loaded custom input array.');
      });
      ui.playBtn.addEventListener('click', togglePlay);
      ui.stepBtn.addEventListener('click', stepForward);
      ui.resetBtn.addEventListener('click', () => loadArray(state.original, 'Reset to the currently loaded array.'));
    }

    function showTab(id, btn) {
      document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.remove('active'));
      document.querySelectorAll('.tab-btn').forEach(button => button.classList.remove('active'));
      document.getElementById(id).classList.add('active');
      btn.classList.add('active');

      if (id === 'tab-visualizer') {
        requestAnimationFrame(() => renderAll());
      }
    }

    function parseInput(raw) {
      const parts = raw.split(',').map(part => part.trim()).filter(Boolean);
      if (!parts.length || parts.length > maxBars) {
        return [];
      }
      const nums = parts.map(Number);
      if (nums.some(num => Number.isNaN(num) || !Number.isFinite(num))) {
        return [];
      }
      return nums.map(num => Math.max(minValue, Math.min(maxValue, Math.round(num))));
    }

    function randomizeArray() {
      const size = 8 + Math.floor(Math.random() * 4);
      const random = Array.from({ length: size }, () => Math.floor(Math.random() * 90) + 10);
      ui.inputArray.value = random.join(', ');
      loadArray(random, 'Generated a fresh random array.');
    }

    function loadArray(arr, message) {
      stopRun();
      state.original = [...arr];
      state.arr = [...arr];
      state.actions = buildActions(arr);
      state.actionIndex = 0;
      state.running = false;
      state.splitCount = 0;
      state.mergeCount = 0;
      state.compareCount = 0;
      state.writeCount = 0;
      state.activeLow = arr.length ? 0 : null;
      state.activeHigh = arr.length ? arr.length - 1 : null;
      state.mid = null;
      state.leftLow = null;
      state.leftHigh = null;
      state.rightLow = null;
      state.rightHigh = null;
      state.compareLeftIndex = null;
      state.compareRightIndex = null;
      state.writeIndex = null;
      state.mergedIndices = [];
      state.sortedIndices = [];
      state.bufferValues = [];
      state.bufferWriteIndex = null;
      state.currentLine = -1;
      state.currentStep = 0;
      state.lastInsight = 'Ready to sort.';
      ui.inputArray.value = arr.join(', ');
      ui.playBtn.textContent = 'Start Sort';
      clearLogs();
      pushLog('info', message);
      pushLog('info', `Prepared ${state.actions.length} animation steps for ${arr.length} elements.`);
      updateStatus('Ready to simulate Merge Sort.');
      updateInsight('keeps dividing the array into smaller sorted pieces, then merges them back in order.');
      updateBufferStatus('Waiting for the first merge.');
      renderAll();
    }

    function buildActions(source) {
      const arr = [...source];
      const actions = [];
      const n = arr.length;

      function emptyBuffer(size) {
        return Array.from({ length: size }, () => null);
      }

      function buildBuffer(values, size) {
        return Array.from({ length: size }, (_, index) => (index < values.length ? values[index] : null));
      }

      function rangeIndices(low, high) {
        return Array.from({ length: high - low + 1 }, (_, offset) => low + offset);
      }

      function mergeSort(low, high, depth) {
        actions.push({
          type: 'enterRange',
          low,
          high,
          depth,
          line: 1,
          step: 0
        });

        if (low >= high) {
          actions.push({
            type: 'baseCase',
            low,
            high,
            index: low,
            value: arr[low],
            line: 2,
            step: 1
          });
          return;
        }

        const mid = Math.floor((low + high) / 2);

        actions.push({
          type: 'splitRange',
          low,
          high,
          mid,
          line: 3,
          step: 0
        });

        actions.push({
          type: 'callLeft',
          low,
          high,
          mid,
          targetLow: low,
          targetHigh: mid,
          line: 4,
          step: 0
        });

        mergeSort(low, mid, depth + 1);

        actions.push({
          type: 'callRight',
          low,
          high,
          mid,
          targetLow: mid + 1,
          targetHigh: high,
          line: 5,
          step: 0
        });

        mergeSort(mid + 1, high, depth + 1);

        const leftValues = arr.slice(low, mid + 1);
        const rightValues = arr.slice(mid + 1, high + 1);
        const totalSize = high - low + 1;
        const merged = [];

        actions.push({
          type: 'startMerge',
          low,
          high,
          mid,
          leftValues: [...leftValues],
          rightValues: [...rightValues],
          bufferSnapshot: emptyBuffer(totalSize),
          line: 6,
          step: 2
        });

        actions.push({
          type: 'prepareBuffer',
          low,
          high,
          mid,
          leftValues: [...leftValues],
          rightValues: [...rightValues],
          bufferSnapshot: emptyBuffer(totalSize),
          line: 9,
          step: 2
        });

        let i = 0;
        let j = 0;

        while (i < leftValues.length && j < rightValues.length) {
          actions.push({
            type: 'compare',
            low,
            high,
            mid,
            leftIndex: low + i,
            rightIndex: mid + 1 + j,
            leftValue: leftValues[i],
            rightValue: rightValues[j],
            writeIndex: low + merged.length,
            bufferSnapshot: buildBuffer(merged, totalSize),
            line: 10,
            step: 2
          });

          if (leftValues[i] <= rightValues[j]) {
            merged.push(leftValues[i]);
            actions.push({
              type: 'takeLeft',
              low,
              high,
              mid,
              sourceIndex: low + i,
              value: leftValues[i],
              writeIndex: low + merged.length - 1,
              bufferIndex: merged.length - 1,
              bufferSnapshot: buildBuffer(merged, totalSize),
              line: 11,
              step: 3
            });
            i += 1;
          } else {
            merged.push(rightValues[j]);
            actions.push({
              type: 'takeRight',
              low,
              high,
              mid,
              sourceIndex: mid + 1 + j,
              value: rightValues[j],
              writeIndex: low + merged.length - 1,
              bufferIndex: merged.length - 1,
              bufferSnapshot: buildBuffer(merged, totalSize),
              line: 12,
              step: 3
            });
            j += 1;
          }
        }

        while (i < leftValues.length) {
          merged.push(leftValues[i]);
          actions.push({
            type: 'drainLeft',
            low,
            high,
            mid,
            sourceIndex: low + i,
            value: leftValues[i],
            writeIndex: low + merged.length - 1,
            bufferIndex: merged.length - 1,
            bufferSnapshot: buildBuffer(merged, totalSize),
            line: 13,
            step: 3
          });
          i += 1;
        }

        while (j < rightValues.length) {
          merged.push(rightValues[j]);
          actions.push({
            type: 'drainRight',
            low,
            high,
            mid,
            sourceIndex: mid + 1 + j,
            value: rightValues[j],
            writeIndex: low + merged.length - 1,
            bufferIndex: merged.length - 1,
            bufferSnapshot: buildBuffer(merged, totalSize),
            line: 13,
            step: 3
          });
          j += 1;
        }

        for (let offset = 0; offset < merged.length; offset++) {
          arr[low + offset] = merged[offset];
        }

        actions.push({
          type: 'copyBack',
          low,
          high,
          mid,
          mergedValues: [...merged],
          snapshot: [...arr],
          bufferSnapshot: [...merged],
          mergedIndices: rangeIndices(low, high),
          writeAmount: merged.length,
          line: 14,
          step: 4
        });
      }

      if (n > 0) {
        mergeSort(0, n - 1, 0);
      }

      actions.push({
        type: 'complete',
        line: 1,
        step: 4,
        finalArray: [...arr],
        sorted: arr.map((_, index) => index)
      });

      return actions;
    }

    function togglePlay() {
      if (state.running) {
        stopRun();
        ui.playBtn.textContent = 'Resume';
        pushLog('warn', 'Animation paused.');
        return;
      }

      if (state.actionIndex >= state.actions.length) {
        loadArray(state.original, 'Restarted the loaded array.');
      }

      state.running = true;
      ui.playBtn.textContent = 'Pause';
      pushLog('info', 'Auto-play started.');
      runLoop();
    }

    function stopRun() {
      state.running = false;
      if (state.timer) {
        clearTimeout(state.timer);
        state.timer = null;
      }
    }

    function stepForward() {
      if (state.running) {
        stopRun();
        ui.playBtn.textContent = 'Resume';
      }
      if (state.actionIndex >= state.actions.length) {
        pushLog('success', 'All steps have already completed.');
        return;
      }
      applyAction(state.actions[state.actionIndex++]);
    }

    function runLoop() {
      if (!state.running) {
        return;
      }
      if (state.actionIndex >= state.actions.length) {
        stopRun();
        ui.playBtn.textContent = 'Replay';
        return;
      }

      applyAction(state.actions[state.actionIndex++]);
      state.timer = setTimeout(runLoop, getSpeedDelay());
    }

    function getSpeedDelay() {
      const value = Number(ui.speedRange.value);
      return 1100 - value * 9;
    }

    function resetHighlights() {
      state.compareLeftIndex = null;
      state.compareRightIndex = null;
      state.writeIndex = null;
      state.mergedIndices = [];
      state.bufferWriteIndex = null;
    }

    function applyAction(action) {
      resetHighlights();
      state.currentLine = action.line ?? -1;
      state.currentStep = action.step ?? 0;

      if (action.sorted) {
        state.sortedIndices = [...action.sorted];
      }

      switch (action.type) {
        case 'enterRange':
          state.activeLow = action.low;
          state.activeHigh = action.high;
          state.mid = null;
          state.leftLow = null;
          state.leftHigh = null;
          state.rightLow = null;
          state.rightHigh = null;
          if (action.low < action.high) {
            updateStatus(`Work on range ${formatRange(action.low, action.high)}.`);
            updateInsight('first breaks the array into smaller ranges before any merging starts.');
            pushLog('info', `Entered range ${formatRange(action.low, action.high)} at depth ${action.depth}.`);
          }
          break;

        case 'baseCase':
          state.activeLow = action.low;
          state.activeHigh = action.high;
          state.mid = null;
          state.leftLow = null;
          state.leftHigh = null;
          state.rightLow = null;
          state.rightHigh = null;
          updateStatus(`Base case: index ${action.index} already forms a sorted range.`);
          updateInsight('stops dividing when a subarray has one element, because a single value is already sorted.');
          updateBufferStatus('Single-element ranges do not need a merge buffer.');
          pushLog('success', `Base case reached at index ${action.index} with value ${action.value}.`);
          break;

        case 'splitRange':
          state.activeLow = action.low;
          state.activeHigh = action.high;
          state.mid = action.mid;
          state.leftLow = action.low;
          state.leftHigh = action.mid;
          state.rightLow = action.mid + 1;
          state.rightHigh = action.high;
          state.splitCount += 1;
          updateStatus(`Split ${formatRange(action.low, action.high)} at mid ${action.mid}.`);
          updateInsight('divides the active range around the midpoint so the two halves can be sorted independently.');
          updateBufferStatus(`Range ${formatRange(action.low, action.high)} will later merge from two halves.`);
          pushLog('info', `Split ${formatRange(action.low, action.high)} into ${formatRange(action.low, action.mid)} and ${formatRange(action.mid + 1, action.high)}.`);
          break;

        case 'callLeft':
          state.activeLow = action.low;
          state.activeHigh = action.high;
          state.mid = action.mid;
          state.leftLow = action.low;
          state.leftHigh = action.mid;
          state.rightLow = action.mid + 1;
          state.rightHigh = action.high;
          updateStatus(`Recurse into left half ${formatRange(action.targetLow, action.targetHigh)}.`);
          updateInsight('always sorts the left half completely before coming back for the right half.');
          pushLog('info', `Descending into left half ${formatRange(action.targetLow, action.targetHigh)}.`);
          break;

        case 'callRight':
          state.activeLow = action.low;
          state.activeHigh = action.high;
          state.mid = action.mid;
          state.leftLow = action.low;
          state.leftHigh = action.mid;
          state.rightLow = action.mid + 1;
          state.rightHigh = action.high;
          updateStatus(`Recurse into right half ${formatRange(action.targetLow, action.targetHigh)}.`);
          updateInsight('after the left half is sorted, merge sort processes the right half before merging them together.');
          pushLog('info', `Descending into right half ${formatRange(action.targetLow, action.targetHigh)}.`);
          break;

        case 'startMerge':
          state.activeLow = action.low;
          state.activeHigh = action.high;
          state.mid = action.mid;
          state.leftLow = action.low;
          state.leftHigh = action.mid;
          state.rightLow = action.mid + 1;
          state.rightHigh = action.high;
          state.bufferValues = [...action.bufferSnapshot];
          state.mergeCount += 1;
          updateStatus(`Start merge ${state.mergeCount} for range ${formatRange(action.low, action.high)}.`);
          updateInsight('begins merging only after both halves are already sorted on their own.');
          updateBufferStatus(`Preparing buffer for ${formatRange(action.low, action.high)}.`);
          pushLog('info', `Started merge ${state.mergeCount} for ${formatRange(action.low, action.high)}.`);
          break;

        case 'prepareBuffer':
          state.activeLow = action.low;
          state.activeHigh = action.high;
          state.mid = action.mid;
          state.leftLow = action.low;
          state.leftHigh = action.mid;
          state.rightLow = action.mid + 1;
          state.rightHigh = action.high;
          state.bufferValues = [...action.bufferSnapshot];
          updateStatus(`Copy both halves into working runs for ${formatRange(action.low, action.high)}.`);
          updateInsight('uses a temporary buffer so the merge can stay stable while reading from both halves in order.');
          updateBufferStatus(`Left: [${action.leftValues.join(', ')}] | Right: [${action.rightValues.join(', ')}]`);
          pushLog('info', `Prepared left [${action.leftValues.join(', ')}] and right [${action.rightValues.join(', ')}] runs.`);
          break;

        case 'compare':
          state.activeLow = action.low;
          state.activeHigh = action.high;
          state.mid = action.mid;
          state.leftLow = action.low;
          state.leftHigh = action.mid;
          state.rightLow = action.mid + 1;
          state.rightHigh = action.high;
          state.compareLeftIndex = action.leftIndex;
          state.compareRightIndex = action.rightIndex;
          state.writeIndex = action.writeIndex;
          state.bufferValues = [...action.bufferSnapshot];
          state.compareCount += 1;
          updateStatus(`Compare ${action.leftValue} and ${action.rightValue} for buffer slot ${action.writeIndex}.`);
          updateInsight('looks only at the front of each half, because everything behind those fronts is already in sorted order.');
          updateBufferStatus(`Comparing left ${action.leftValue} and right ${action.rightValue}.`);
          pushLog('info', `Compared ${action.leftValue} at index ${action.leftIndex} with ${action.rightValue} at index ${action.rightIndex}.`);
          break;

        case 'takeLeft':
          state.activeLow = action.low;
          state.activeHigh = action.high;
          state.mid = action.mid;
          state.leftLow = action.low;
          state.leftHigh = action.mid;
          state.rightLow = action.mid + 1;
          state.rightHigh = action.high;
          state.compareLeftIndex = action.sourceIndex;
          state.writeIndex = action.writeIndex;
          state.bufferValues = [...action.bufferSnapshot];
          state.bufferWriteIndex = action.bufferIndex;
          state.writeCount += 1;
          updateStatus(`Take left value ${action.value} into buffer slot ${action.bufferIndex}.`);
          updateInsight('keeps merge sort stable, because equal values from the left side are written before equal values from the right.');
          updateBufferStatus(`Buffered ${action.value} from the left half.`);
          pushLog('warn', `Wrote ${action.value} from index ${action.sourceIndex} into buffer slot ${action.bufferIndex}.`);
          break;

        case 'takeRight':
          state.activeLow = action.low;
          state.activeHigh = action.high;
          state.mid = action.mid;
          state.leftLow = action.low;
          state.leftHigh = action.mid;
          state.rightLow = action.mid + 1;
          state.rightHigh = action.high;
          state.compareRightIndex = action.sourceIndex;
          state.writeIndex = action.writeIndex;
          state.bufferValues = [...action.bufferSnapshot];
          state.bufferWriteIndex = action.bufferIndex;
          state.writeCount += 1;
          updateStatus(`Take right value ${action.value} into buffer slot ${action.bufferIndex}.`);
          updateInsight('pulls the smaller front value into the buffer, so the merged run stays sorted from left to right.');
          updateBufferStatus(`Buffered ${action.value} from the right half.`);
          pushLog('warn', `Wrote ${action.value} from index ${action.sourceIndex} into buffer slot ${action.bufferIndex}.`);
          break;

        case 'drainLeft':
          state.activeLow = action.low;
          state.activeHigh = action.high;
          state.mid = action.mid;
          state.leftLow = action.low;
          state.leftHigh = action.mid;
          state.rightLow = action.mid + 1;
          state.rightHigh = action.high;
          state.compareLeftIndex = action.sourceIndex;
          state.writeIndex = action.writeIndex;
          state.bufferValues = [...action.bufferSnapshot];
          state.bufferWriteIndex = action.bufferIndex;
          state.writeCount += 1;
          updateStatus(`Append remaining left value ${action.value} to the buffer.`);
          updateInsight('once one half is exhausted, the remaining values from the other half can be appended directly.');
          updateBufferStatus(`Appended leftover left value ${action.value}.`);
          pushLog('success', `Appended leftover left value ${action.value} from index ${action.sourceIndex}.`);
          break;

        case 'drainRight':
          state.activeLow = action.low;
          state.activeHigh = action.high;
          state.mid = action.mid;
          state.leftLow = action.low;
          state.leftHigh = action.mid;
          state.rightLow = action.mid + 1;
          state.rightHigh = action.high;
          state.compareRightIndex = action.sourceIndex;
          state.writeIndex = action.writeIndex;
          state.bufferValues = [...action.bufferSnapshot];
          state.bufferWriteIndex = action.bufferIndex;
          state.writeCount += 1;
          updateStatus(`Append remaining right value ${action.value} to the buffer.`);
          updateInsight('after comparisons finish, the untouched tail is already sorted and can be copied straight over.');
          updateBufferStatus(`Appended leftover right value ${action.value}.`);
          pushLog('success', `Appended leftover right value ${action.value} from index ${action.sourceIndex}.`);
          break;

        case 'copyBack':
          state.arr = [...action.snapshot];
          state.activeLow = action.low;
          state.activeHigh = action.high;
          state.mid = action.mid;
          state.leftLow = action.low;
          state.leftHigh = action.mid;
          state.rightLow = action.mid + 1;
          state.rightHigh = action.high;
          state.bufferValues = [...action.bufferSnapshot];
          state.mergedIndices = [...action.mergedIndices];
          state.writeCount += action.writeAmount;
          updateStatus(`Copy merged values back into ${formatRange(action.low, action.high)}.`);
          updateInsight('writes the sorted temporary run back into the main array, producing a larger sorted segment.');
          updateBufferStatus(`Merged run ready: [${action.mergedValues.join(', ')}]`);
          pushLog('success', `Copied [${action.mergedValues.join(', ')}] back to ${formatRange(action.low, action.high)}.`);
          break;

        case 'complete':
          state.arr = [...action.finalArray];
          state.sortedIndices = [...action.sorted];
          state.activeLow = null;
          state.activeHigh = null;
          state.mid = null;
          state.leftLow = null;
          state.leftHigh = null;
          state.rightLow = null;
          state.rightHigh = null;
          state.mergedIndices = [];
          state.bufferValues = [];
          state.bufferWriteIndex = null;
          updateStatus('Sorting complete.');
          updateInsight(`finished. Final sorted array: [${state.arr.join(', ')}].`);
          updateBufferStatus('All merges complete. Buffer is idle.');
          pushLog('success', `Sorting complete: [${state.arr.join(', ')}].`);
          stopRun();
          ui.playBtn.textContent = 'Replay';
          break;
      }

      renderAll();
    }

    function renderAll() {
      renderBars();
      renderBuffer();
      renderMetrics();
      renderSteps();
      renderPseudoCode();
    }

    function renderBars() {
      ui.barsWrap.innerHTML = '';
      const max = Math.max(...state.arr, 1);

      state.arr.forEach((value, index) => {
        const col = document.createElement('div');
        col.className = 'bar-col';

        const label = document.createElement('div');
        label.className = 'bar-label';
        label.textContent = value;

        const bar = document.createElement('div');
        bar.className = 'bar';
        bar.style.height = `${Math.max(40, (value / max) * 240)}px`;
        bar.style.opacity = state.activeLow !== null && state.activeHigh !== null && (index < state.activeLow || index > state.activeHigh) && !state.sortedIndices.includes(index)
          ? '0.3'
          : '1';

        if (state.sortedIndices.includes(index)) {
          bar.classList.add('sorted');
        } else if (state.mergedIndices.includes(index)) {
          bar.classList.add('merged');
        } else if (index === state.writeIndex) {
          bar.classList.add('write-target');
        } else if (index === state.compareLeftIndex || index === state.compareRightIndex) {
          bar.classList.add('compare');
        } else if (inRange(index, state.leftLow, state.leftHigh)) {
          bar.classList.add('left-run');
        } else if (inRange(index, state.rightLow, state.rightHigh)) {
          bar.classList.add('right-run');
        } else if (inRange(index, state.activeLow, state.activeHigh)) {
          bar.classList.add('active-range');
        }

        const badge = document.createElement('div');
        badge.className = 'index-badge';
        badge.textContent = `i:${index}`;

        col.appendChild(label);
        col.appendChild(bar);
        col.appendChild(badge);
        ui.barsWrap.appendChild(col);
      });
    }

    function renderBuffer() {
      ui.bufferWrap.innerHTML = '';

      if (!state.bufferValues.length) {
        const empty = document.createElement('div');
        empty.className = 'buffer-empty';
        empty.textContent = 'No active merge buffer yet.';
        ui.bufferWrap.appendChild(empty);
        return;
      }

      state.bufferValues.forEach((value, index) => {
        const cell = document.createElement('div');
        cell.className = `buffer-cell ${value !== null ? 'filled' : ''} ${state.bufferWriteIndex === index ? 'active' : ''}`.trim();

        const slot = document.createElement('div');
        slot.className = 'buffer-index';
        slot.textContent = `b:${index}`;

        const val = document.createElement('div');
        val.className = 'buffer-value';
        val.textContent = value === null ? '...' : value;

        cell.appendChild(slot);
        cell.appendChild(val);
        ui.bufferWrap.appendChild(cell);
      });
    }

    function renderMetrics() {
      ui.splitCount.textContent = state.splitCount;
      ui.mergeCount.textContent = state.mergeCount;
      ui.compareCount.textContent = state.compareCount;
      ui.writeCount.textContent = state.writeCount;
    }

    function renderSteps() {
      ui.stepBox.innerHTML = '';
      stepsTemplate.forEach((text, index) => {
        const item = document.createElement('div');
        let cls = 'pending';
        if (index < state.currentStep) cls = 'done';
        if (index === state.currentStep) cls = 'active';
        item.className = `step-item ${cls}`;
        item.innerHTML = `
          <div class="step-num">0${index + 1}</div>
          <div class="step-text">${text}</div>
        `;
        ui.stepBox.appendChild(item);
      });
    }

    function renderPseudoCode() {
      ui.codeWrap.innerHTML = '';
      pseudoCode.forEach((text, index) => {
        const line = document.createElement('div');
        line.className = `code-line ${state.currentLine === index + 1 ? 'active' : ''}`;
        line.innerHTML = `
          <div class="line-no">${index + 1}</div>
          <div>${text}</div>
        `;
        ui.codeWrap.appendChild(line);
      });

      const explain = document.createElement('div');
      explain.className = 'explain-box';
      explain.innerHTML = `
        <h3>Why It Matters</h3>
        <p>${state.lastInsight}</p>
      `;
      ui.codeWrap.appendChild(explain);
    }

    function updateStatus(text) {
      ui.statusText.textContent = text;
    }

    function updateInsight(text) {
      state.lastInsight = text;
      ui.insightText.innerHTML = `<strong>Merge Sort</strong> ${text}`;
    }

    function updateBufferStatus(text) {
      ui.bufferStatus.textContent = text;
    }

    function formatRange(low, high) {
      return low <= high ? `[${low}, ${high}]` : 'empty range';
    }

    function inRange(index, low, high) {
      return low !== null && high !== null && index >= low && index <= high;
    }

    function clearLogs() {
      ui.logBox.innerHTML = '';
    }

    function pushLog(type, message) {
      const entry = document.createElement('div');
      entry.className = `log-entry ${type}`;
      const ts = new Date().toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      });
      entry.innerHTML = `<span class="ts">${ts}</span>${message}`;
      ui.logBox.prepend(entry);
    }

    init();
  </script>
  <button class="dsa-theme-toggle" id="dsaThemeToggle" aria-label="Switch theme">
    <span id="dsaToggleIcon">☀️</span>
    <span id="dsaToggleLabel">Light</span>
  </button>
  <script>
    (function () {
      var btn = document.getElementById('dsaThemeToggle');
      var icon = document.getElementById('dsaToggleIcon');
      var label = document.getElementById('dsaToggleLabel');
      var body = document.body;
      var KEY = 'dsa-theme';
      function apply(mode) {
        if (mode === 'light') {
          body.classList.add('light-mode');
          icon.textContent = '🌙';
          label.textContent = 'Dark';
        } else {
          body.classList.remove('light-mode');
          icon.textContent = '☀️';
          label.textContent = 'Light';
        }
      }
      var saved = localStorage.getItem(KEY);
      if (saved) apply(saved);
      btn.addEventListener('click', function () {
        var next = body.classList.contains('light-mode') ? 'dark' : 'light';
        apply(next);
        localStorage.setItem(KEY, next);
      });
    })();
  </script>
</body>
</html>
