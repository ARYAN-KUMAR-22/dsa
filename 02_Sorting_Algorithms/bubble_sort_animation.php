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
  <title>Bubble Sort - Interactive Visualizer</title>
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
      --accent: #38bdf8;
      --accent-2: #7dd3fc;
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
      --accent: #0369a1;
      --accent-2: #0284c7;
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
      background: linear-gradient(90deg, #07131d, #0d1117);
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
      background: rgba(56, 189, 248, 0.08);
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
        radial-gradient(circle at top right, rgba(56, 189, 248, 0.08), transparent 28%),
        radial-gradient(circle at bottom left, rgba(125, 211, 252, 0.06), transparent 30%),
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
      background: linear-gradient(135deg, var(--accent), #0ea5e9);
      color: #07131d;
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
      min-height: 280px;
      height: clamp(280px, 44vh, 380px);
      padding: 22px 18px 18px;
      display: flex;
      align-items: flex-end;
      gap: 10px;
      overflow-y: hidden;
      overflow-x: auto;
    }

    .bars-wrap::-webkit-scrollbar {
      height: 6px;
    }

    .bars-wrap::-webkit-scrollbar-thumb {
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
      height: calc(clamp(280px, 44vh, 380px) - 40px);
    }

    .bar {
      width: 100%;
      border-radius: 12px 12px 4px 4px;
      background: linear-gradient(180deg, #1d4ed8, var(--accent));
      position: relative;
      transition: height 0.35s ease, transform 0.25s ease, background 0.25s ease, box-shadow 0.25s ease;
      border: 1px solid rgba(255, 255, 255, 0.06);
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    .bar.compare {
      background: linear-gradient(180deg, #f97316, var(--warn));
      transform: translateY(-6px);
    }

    .bar.swap {
      background: linear-gradient(180deg, #f43f5e, var(--danger));
      box-shadow: 0 0 18px rgba(239, 68, 68, 0.25);
      transform: translateY(-10px);
    }

    .bar.sorted {
      background: linear-gradient(180deg, #16a34a, var(--good));
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
      background: rgba(56, 189, 248, 0.08);
      border-color: rgba(56, 189, 248, 0.28);
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
      <h1><span>Bubble Sort</span> Visualizer</h1>
      <p>Watch adjacent comparisons, swaps, and each sorted pass lock into place.</p>
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
        Sort Runtime Feed
      </div>

      <section class="panel-section">
        <div class="panel-section-title">Live Metrics</div>
        <div class="summary-grid">
          <div class="metric">
            <div class="metric-label">Pass</div>
            <div class="metric-value" id="passCount">0</div>
          </div>
          <div class="metric">
            <div class="metric-label">Compare</div>
            <div class="metric-value" id="compareCount">0</div>
          </div>
          <div class="metric">
            <div class="metric-label">Swap</div>
            <div class="metric-value" id="swapCount">0</div>
          </div>
          <div class="metric">
            <div class="metric-label">Sorted</div>
            <div class="metric-value" id="sortedCount">0</div>
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
            <input id="inputArray" type="text" value="45, 12, 78, 34, 23, 89, 11, 56"
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
                <div class="card-subtitle" id="statusText">Ready to simulate Bubble Sort.</div>
              </div>
              <div class="legend">
                <span class="legend-item"><span class="legend-swatch" style="background:#38bdf8;"></span> Unsorted</span>
                <span class="legend-item"><span class="legend-swatch" style="background:#f59e0b;"></span> Comparing</span>
                <span class="legend-item"><span class="legend-swatch" style="background:#ef4444;"></span> Swapping</span>
                <span class="legend-item"><span class="legend-swatch" style="background:#22c55e;"></span> Sorted</span>
              </div>
            </div>
            <div class="bars-wrap" id="barsWrap"></div>
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
              <span class="badge">Best: O(n)</span>
              <span class="badge">Average: O(n^2)</span>
              <span class="badge">Worst: O(n^2)</span>
              <span class="badge">Space: O(1)</span>
              <span class="badge">Stable: Yes</span>
            </div>
          </article>

          <article class="footer-card">
            <h3>Current Insight</h3>
            <p class="status-line" id="insightText">
              <strong>Bubble Sort</strong> repeatedly compares adjacent values and pushes the largest remaining element
              to the end on every pass.
            </p>
          </article>
        </section>
      </section>

      <section id="tab-theory" class="tab-panel">
        <div class="theory-grid">
          <article class="theory-card">
            <h3>How Bubble Sort Works</h3>
            <ul>
              <li>It compares adjacent elements and swaps them when they are out of order.</li>
              <li>After every pass, the largest unsorted element bubbles to the far right.</li>
              <li>If one full pass makes no swaps, the algorithm stops early.</li>
            </ul>
          </article>

          <article class="theory-card">
            <h3>Pseudo Logic</h3>
            <ul>
              <li><code>for i = 0 to n - 2</code></li>
              <li><code>swapped = false</code></li>
              <li><code>for j = 0 to n - i - 2</code></li>
              <li><code>if a[j] > a[j + 1] then swap</code></li>
              <li><code>if swapped is false, break</code></li>
            </ul>
          </article>

          <article class="theory-card">
            <h3>When It Is Useful</h3>
            <ul>
              <li>Best for learning sorting because the data movement is easy to see.</li>
              <li>Reasonable only for very small or almost-sorted arrays.</li>
              <li>Its early-exit optimization makes the best case linear, <code>O(n)</code>.</li>
            </ul>
          </article>

          <article class="theory-card">
            <h3>Key Tradeoffs</h3>
            <ul>
              <li>Average and worst-case time are <code>O(n^2)</code>, so it scales poorly.</li>
              <li>It is stable, so equal elements keep their relative order.</li>
              <li>It sorts in place, needing only <code>O(1)</code> extra space.</li>
            </ul>
          </article>
        </div>
      </section>

      <section id="tab-comparison" class="tab-panel">
        <div class="theory-grid">
          <article class="theory-card">
            <h3>Bubble Sort At a Glance</h3>
            <ul>
              <li>Bubble Sort is <code>stable</code> and sorts <code>in place</code>, but its average and worst-case time are <code>O(n^2)</code>.</li>
              <li>Its early-exit check gives it a best case of <code>O(n)</code> on already sorted data.</li>
              <li>It is easiest to understand visually, which is why it is great for learning but weak for large inputs.</li>
            </ul>
          </article>

          <article class="theory-card">
            <h3>Compared With Insertion and Selection</h3>
            <ul>
              <li><strong>Insertion Sort</strong> is usually better on nearly sorted arrays because it does not repeat full adjacent passes.</li>
              <li><strong>Selection Sort</strong> makes fewer swaps, so it can be better when writes are expensive.</li>
              <li>Bubble Sort does the most obvious adjacent swapping, but that simplicity usually costs extra work.</li>
            </ul>
          </article>

          <article class="theory-card">
            <h3>Compared With Merge and Quick</h3>
            <ul>
              <li><strong>Merge Sort</strong> and <strong>Quick Sort</strong> both scale much better for big arrays with around <code>O(n log n)</code> behavior.</li>
              <li>Merge Sort is stable but needs extra memory, while Quick Sort is usually faster in practice but not stable.</li>
              <li>Bubble Sort is mainly a teaching tool once input sizes stop being tiny.</li>
            </ul>
          </article>

          <article class="theory-card">
            <h3>Best Fit</h3>
            <ul>
              <li>Choose Bubble Sort when you want the clearest possible swap-by-swap demonstration.</li>
              <li>It is acceptable for very small or almost-sorted arrays where clarity matters more than speed.</li>
              <li>For real workloads, other algorithms are usually the better choice.</li>
            </ul>
          </article>
        </div>
      </section>
    </main>
  </div>

  <script>
    const defaultArray = [45, 12, 78, 34, 23, 89, 11, 56];
    const maxBars = 14;
    const minValue = 5;
    const maxValue = 99;

    const stepsTemplate = [
      'Start a left-to-right pass through the unsorted section.',
      'Compare adjacent elements a[j] and a[j + 1].',
      'Swap them if they are in the wrong order.',
      'Continue until the largest element settles at the end.',
      'Repeat passes until no swaps are needed.'
    ];

    const pseudoCode = [
      'for i = 0 to n - 2',
      '  swapped = false',
      '  for j = 0 to n - i - 2',
      '    if a[j] > a[j + 1]',
      '      swap(a[j], a[j + 1])',
      '      swapped = true',
      '  if swapped == false',
      '    break'
    ];

    const ui = {
      barsWrap: document.getElementById('barsWrap'),
      inputArray: document.getElementById('inputArray'),
      playBtn: document.getElementById('playBtn'),
      stepBtn: document.getElementById('stepBtn'),
      resetBtn: document.getElementById('resetBtn'),
      randomBtn: document.getElementById('randomBtn'),
      loadBtn: document.getElementById('loadBtn'),
      speedRange: document.getElementById('speedRange'),
      passCount: document.getElementById('passCount'),
      compareCount: document.getElementById('compareCount'),
      swapCount: document.getElementById('swapCount'),
      sortedCount: document.getElementById('sortedCount'),
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
      compareCount: 0,
      swapCount: 0,
      passCount: 0,
      sortedIndices: [],
      highlight: [],
      swapPair: [],
      currentLine: -1,
      currentStep: 0,
      lastInsight: 'Ready to sort.'
    };

    function init() {
      renderSteps();
      renderPseudoCode();
      loadArray(defaultArray, 'Loaded default Bubble Sort sample.');
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
      state.compareCount = 0;
      state.swapCount = 0;
      state.passCount = 0;
      state.sortedIndices = [];
      state.highlight = [];
      state.swapPair = [];
      state.currentLine = -1;
      state.currentStep = 0;
      state.lastInsight = 'Ready to sort.';
      ui.inputArray.value = arr.join(', ');
      ui.playBtn.textContent = 'Start Sort';
      clearLogs();
      pushLog('info', message);
      pushLog('info', `Prepared ${state.actions.length} animation steps for ${arr.length} elements.`);
      updateStatus('Ready to simulate Bubble Sort.');
      updateInsight('Bubble Sort repeatedly compares adjacent values and pushes the largest remaining element to the end on every pass.');
      renderAll();
    }

    function buildActions(source) {
      const arr = [...source];
      const actions = [];
      const n = arr.length;

      for (let i = 0; i < n - 1; i++) {
        let swapped = false;
        actions.push({ type: 'passStart', pass: i + 1, line: 1, step: 0 });
        actions.push({ type: 'markStep', line: 2, step: 1 });

        for (let j = 0; j < n - i - 1; j++) {
          actions.push({ type: 'compare', indices: [j, j + 1], values: [arr[j], arr[j + 1]], line: 3, step: 1 });
          actions.push({ type: 'decision', indices: [j, j + 1], swap: arr[j] > arr[j + 1], line: 4, step: 2 });

          if (arr[j] > arr[j + 1]) {
            [arr[j], arr[j + 1]] = [arr[j + 1], arr[j]];
            swapped = true;
            actions.push({
              type: 'swap',
              indices: [j, j + 1],
              snapshot: [...arr],
              values: [arr[j], arr[j + 1]],
              line: 5,
              step: 2
            });
            actions.push({ type: 'markSwapped', line: 6, step: 2 });
          }
        }

        actions.push({
          type: 'sortedLock',
          index: n - i - 1,
          value: arr[n - i - 1],
          line: 1,
          step: 3
        });

        if (!swapped) {
          actions.push({ type: 'earlyExit', line: 7, step: 4, finalArray: [...arr] });
          break;
        }
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

    function applyAction(action) {
      state.highlight = [];
      state.swapPair = [];
      state.currentLine = action.line ?? -1;
      state.currentStep = action.step ?? 0;

      switch (action.type) {
        case 'passStart':
          state.passCount = action.pass;
          updateStatus(`Pass ${action.pass} started over the unsorted portion.`);
          updateInsight(`Pass ${action.pass} begins. Bubble Sort now scans from left to right to bubble the largest remaining value to the end.`);
          pushLog('info', `Pass ${action.pass} started.`);
          break;

        case 'markStep':
          updateStatus('Reset swapped flag for the new pass.');
          updateInsight('Each pass starts with the assumption that the array might already be sorted.');
          break;

        case 'compare':
          state.highlight = action.indices;
          state.compareCount += 1;
          updateStatus(`Comparing ${action.values[0]} and ${action.values[1]}.`);
          updateInsight(`Adjacent comparison ${state.compareCount}: Bubble Sort only looks at neighbors, which is why it is simple but not very fast on large arrays.`);
          pushLog('info', `Compare indices ${action.indices[0]} and ${action.indices[1]} (${action.values[0]} vs ${action.values[1]}).`);
          break;

        case 'decision':
          state.highlight = action.indices;
          if (action.swap) {
            updateStatus(`Out of order. Prepare to swap ${state.arr[action.indices[0]]} and ${state.arr[action.indices[1]]}.`);
            updateInsight('The left value is larger than the right one, so they must be swapped to move the bigger value rightward.');
          } else {
            updateStatus('Already in correct order. No swap needed.');
            updateInsight('When neighbors are already ordered, Bubble Sort simply moves on to the next adjacent pair.');
            pushLog('warn', `No swap needed for indices ${action.indices[0]} and ${action.indices[1]}.`);
          }
          break;

        case 'swap':
          state.arr = [...action.snapshot];
          state.swapPair = action.indices;
          state.highlight = action.indices;
          state.swapCount += 1;
          updateStatus(`Swapped positions ${action.indices[0]} and ${action.indices[1]}.`);
          updateInsight(`Swap ${state.swapCount}: the larger value keeps drifting right until it reaches its final position for this pass.`);
          pushLog('warn', `Swapped indices ${action.indices[0]} and ${action.indices[1]}. Array is now [${state.arr.join(', ')}].`);
          break;

        case 'markSwapped':
          updateStatus('Swapped flag marked true for this pass.');
          break;

        case 'sortedLock':
          if (!state.sortedIndices.includes(action.index)) {
            state.sortedIndices.push(action.index);
          }
          updateStatus(`Index ${action.index} is now fixed with value ${action.value}.`);
          updateInsight('At the end of each pass, the largest remaining unsorted value is guaranteed to be locked at the far right.');
          pushLog('success', `Value ${action.value} settled at index ${action.index}.`);
          break;

        case 'earlyExit':
          state.sortedIndices = state.arr.map((_, index) => index);
          updateStatus('No swaps in this pass. Array is already sorted.');
          updateInsight('This optimized Bubble Sort stops early when a pass makes no swaps, giving a best-case time of O(n).');
          pushLog('success', 'Early exit triggered because the array is already sorted.');
          break;

        case 'complete':
          state.arr = [...action.finalArray];
          state.sortedIndices = [...action.sorted];
          updateStatus('Sorting complete.');
          updateInsight(`Finished. Final sorted array: [${state.arr.join(', ')}].`);
          pushLog('success', `Sorting complete: [${state.arr.join(', ')}].`);
          stopRun();
          ui.playBtn.textContent = 'Replay';
          break;
      }

      renderAll();
    }

    function renderAll() {
      renderBars();
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
        bar.style.height = `${Math.max(40, (value / max) * 260)}px`;

        if (state.sortedIndices.includes(index)) {
          bar.classList.add('sorted');
        } else if (state.swapPair.includes(index)) {
          bar.classList.add('swap');
        } else if (state.highlight.includes(index)) {
          bar.classList.add('compare');
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

    function renderMetrics() {
      ui.passCount.textContent = state.passCount;
      ui.compareCount.textContent = state.compareCount;
      ui.swapCount.textContent = state.swapCount;
      ui.sortedCount.textContent = state.sortedIndices.length;
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
      ui.insightText.innerHTML = `<strong>Bubble Sort</strong> ${text}`;
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
