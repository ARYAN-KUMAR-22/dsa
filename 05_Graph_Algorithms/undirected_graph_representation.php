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
  <title>Undirected Graph Representation</title>
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
      --text: #e2e8f0;
      --muted: #8b98b6;
      --accent: #38bdf8;
      --accent-2: #7dd3fc;
      --good: #22c55e;
      --warn: #f59e0b;
      --focus-ring: rgba(125, 211, 252, .42);
      --focus-glow: rgba(56, 189, 248, .14);
      --shadow: 0 18px 45px rgba(0, 0, 0, .28);
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
    }
    body.light-mode pre {
      background: var(--surface);
      border-color: var(--border);
    }

    body.light-mode code {
      color: var(--text);
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
      font-family: Inter, sans-serif;
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
      background: linear-gradient(90deg, #081521, #0d1117);
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
      color: var(--muted);
      font-size: .78rem;
    }

    .header-actions {
      margin-left: auto;
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }

    .header-link {
      padding: 8px 12px;
      border-radius: 999px;
      border: 1px solid var(--border);
      background: var(--surface);
      color: var(--text);
      text-decoration: none;
      font-weight: 600;
      transition: transform .16s ease, border-color .16s ease, background .16s ease;
    }

    .header-link.primary {
      background: linear-gradient(135deg, #0ea5e9, var(--accent));
      color: #041522;
      border-color: transparent;
    }

    .header-link:hover {
      transform: translateY(-1px);
      border-color: var(--accent);
    }

    .speed-wrap {
      display: flex;
      align-items: center;
      gap: 10px;
      color: var(--muted);
      font-size: .78rem;
      padding-left: 6px;
    }

    .speed-wrap input {
      accent-color: var(--accent);
    }

    .app-body {
      flex: 1;
      display: flex;
      min-height: 0;
      overflow: hidden;
    }

    .left-panel {
      width: 360px;
      min-width: 360px;
      background: var(--panel);
      border-right: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    .panel-header {
      padding: 10px 12px 8px;
      display: flex;
      align-items: center;
      gap: 8px;
      border-bottom: 1px solid var(--border);
      color: var(--accent);
      font-weight: 700;
      letter-spacing: .06em;
      text-transform: uppercase;
    }

    .pulse-dot {
      width: 8px;
      height: 8px;
      border-radius: 999px;
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
        opacity: .45;
        transform: scale(.84);
      }
    }

    .panel-section {
      border-bottom: 1px solid var(--border);
      display: flex;
      flex-direction: column;
    }

    .panel-section-title {
      font-size: .67rem;
      letter-spacing: .07em;
      text-transform: uppercase;
      font-weight: 700;
      color: var(--muted);
      padding: 7px 12px 4px;
    }

    .summary-grid {
      padding: 1px 9px 5px;
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 6px;
    }

    .metric {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 6px;
      box-shadow: var(--shadow);
    }

    .metric-label {
      font-size: .64rem;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: 4px;
    }

    .metric-value {
      font-family: "Fira Code", monospace;
      font-size: .78rem;
      color: var(--text);
      word-break: break-word;
    }

    .flow-merge {
      padding: 0 8px 8px;
      display: grid;
      gap: 8px;
    }

    .flow-block {
      display: grid;
      gap: 6px;
      min-height: 0;
    }

    .panel-mini-title {
      padding: 0 4px;
      font-size: .62rem;
      letter-spacing: .08em;
      text-transform: uppercase;
      font-weight: 700;
      color: #64748b;
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
      padding: 8px 16px 0;
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
      font-size: .82rem;
      font-family: Inter, sans-serif;
      font-weight: 600;
      transition: all .2s ease;
      white-space: nowrap;
      border-bottom: 2px solid transparent;
    }

    .tab-btn:hover {
      color: var(--text);
      background: var(--surface);
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
      overflow: auto;
    }

    .tab-panel.active {
      display: block;
    }

    .tab-panel.visualizer-panel.active {
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    .control-row {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      padding: 12px 16px 10px;
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
      font-size: .72rem;
      letter-spacing: .07em;
      text-transform: uppercase;
      color: var(--muted);
      font-weight: 700;
    }

    .field select {
      background: transparent;
      border: none;
      color: var(--text);
      font-family: "Fira Code", monospace;
      font-size: .8rem;
      outline: none;
      min-width: 170px;
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
      padding: 9px 12px;
      font-size: .8rem;
      font-weight: 600;
      font-family: Inter, sans-serif;
      cursor: pointer;
      transition: transform .16s ease, border-color .16s ease, background .16s ease;
    }

    button:hover {
      transform: translateY(-1px);
      border-color: var(--accent);
    }

    button.primary {
      background: linear-gradient(135deg, #0ea5e9, var(--accent));
      color: #041522;
      border-color: transparent;
    }

    .viz-area {
      display: grid;
      grid-template-columns: 1.07fr .93fr;
      grid-template-rows: 338px 244px;
      gap: 12px;
      padding: 12px 16px 16px;
      align-items: start;
    }

    .canvas-card,
    .stack-card,
    .footer-card,
    .theory-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 18px;
      box-shadow: var(--shadow);
      transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease;
    }

    .stack-card,
    .canvas-card {
      overflow: hidden;
    }

    .focus-card {
      border-color: var(--focus-ring);
      box-shadow: 0 0 0 1px var(--focus-ring), 0 20px 40px var(--focus-glow);
      transform: translateY(-1px);
    }

    .canvas-card.focus-card {
      transform: none;
    }

    .canvas-card {
      display: flex;
      flex-direction: column;
      grid-column: 1;
      grid-row: 1;
      height: 100%;
      min-height: 0;
    }

    .matrix-card {
      grid-column: 1;
      grid-row: 2;
      height: 100%;
      min-height: 0;
    }

    .stack {
      display: grid;
      gap: 12px;
      grid-template-rows: 246px 188px 136px;
      grid-column: 2;
      grid-row: 1 / span 2;
      height: 594px;
      min-height: 594px;
      align-self: stretch;
    }

    .card-head {
      padding: 12px 14px 10px;
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 12px;
    }

    .card-title {
      font-size: .9rem;
      font-weight: 700;
    }

    .card-subtitle {
      font-size: .75rem;
      color: var(--muted);
      line-height: 1.55;
      max-width: 560px;
    }

    .graph-stage {
      height: 270px;
      min-height: 270px;
      max-height: 270px;
      padding: 0 14px 14px;
      margin-top: 2px;
      overflow: hidden;
      flex-shrink: 0;
    }

    .graph-stage svg {
      width: 100%;
      height: 100%;
      display: block;
    }

    .legend {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      font-size: .72rem;
      color: var(--muted);
      justify-content: flex-end;
    }

    .legend-item {
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .legend-line {
      width: 20px;
      height: 0;
      border-top: 3px solid currentColor;
      border-radius: 999px;
      flex-shrink: 0;
    }

    .stack-card-body,
    .footer-card {
      height: 100%;
      min-height: 0;
      padding: 10px;
    }

    .stack-card-body {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .stack-card-head {
      display: grid;
      gap: 4px;
      flex-shrink: 0;
    }

    .stack-card h2,
    .footer-card h2,
    .theory-card h3 {
      font-size: .9rem;
    }

    .stack-card-head p,
    .footer-card p,
    .theory-card p,
    .theory-card li {
      color: var(--muted);
      font-size: .78rem;
      line-height: 1.6;
    }

    .list-grid,
    .edge-list,
    .code-lines {
      display: grid;
      gap: 8px;
      min-height: 0;
      align-content: start;
      grid-auto-rows: max-content;
    }

    .list-grid,
    .edge-list,
    .matrix-wrap,
    .code-lines {
      overflow-y: auto;
      padding-right: 4px;
    }

    .list-grid::-webkit-scrollbar,
    .edge-list::-webkit-scrollbar,
    .matrix-wrap::-webkit-scrollbar,
    .code-lines::-webkit-scrollbar,
    .footer-card::-webkit-scrollbar {
      width: 5px;
      height: 5px;
    }

    .list-grid::-webkit-scrollbar-thumb,
    .edge-list::-webkit-scrollbar-thumb,
    .matrix-wrap::-webkit-scrollbar-thumb,
    .code-lines::-webkit-scrollbar-thumb,
    .footer-card::-webkit-scrollbar-thumb {
      background: var(--border);
      border-radius: 999px;
    }

    .list-row {
      display: grid;
      grid-template-columns: 72px 1fr;
      border: 1px solid var(--border);
      border-radius: 14px;
      overflow: hidden;
      background: var(--surface);
      transition: border-color .16s ease, background .16s ease;
    }

    .list-row.active {
      border-color: rgba(125, 211, 252, .45);
      background: rgba(56, 189, 248, .08);
    }

    .list-head {
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--surface);
      border-right: 1px solid var(--border);
      font-family: "Fira Code", monospace;
      color: var(--accent-2);
      font-size: .8rem;
    }

    .list-body {
      padding: 10px 12px;
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      align-items: center;
    }

    .chip,
    .empty {
      min-width: 40px;
      min-height: 32px;
      padding: 6px 8px;
      border-radius: 10px;
      border: 1px solid var(--border);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-family: "Fira Code", monospace;
      font-size: .78rem;
    }

    .chip {
      background: rgba(56, 189, 248, .12);
    }

    .chip.active {
      border-color: rgba(125, 211, 252, .45);
      background: rgba(56, 189, 248, .2);
    }

    .empty {
      background: var(--surface);
      color: #64748b;
    }

    .edge-item {
      display: grid;
      gap: 6px;
      padding: 8px 10px;
      border-radius: 12px;
      border: 1px solid var(--border);
      background: var(--surface);
      transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
    }

    .edge-item.done {
      border-color: rgba(34, 197, 94, .28);
    }

    .edge-item.active {
      border-color: rgba(56, 189, 248, .42);
      background: rgba(56, 189, 248, .09);
      box-shadow: 0 0 0 1px rgba(56, 189, 248, .14);
    }

    .edge-item.pending {
      opacity: .58;
    }

    .edge-head {
      display: flex;
      justify-content: space-between;
      gap: 8px;
      align-items: center;
    }

    .edge-type,
    .edge-state {
      font-family: "Fira Code", monospace;
      font-size: .69rem;
    }

    .edge-type {
      color: var(--accent-2);
    }

    .edge-state {
      color: #64748b;
    }

    .edge-msg {
      color: var(--text);
      font-size: .72rem;
      line-height: 1.45;
    }

    .matrix-wrap {
      flex: 1;
      min-height: 0;
    }

    table {
      border-collapse: separate;
      border-spacing: 8px;
      min-width: 100%;
    }

    th,
    td {
      min-width: 40px;
      height: 40px;
      border-radius: 10px;
      border: 1px solid var(--border);
      text-align: center;
      font-family: "Fira Code", monospace;
      font-size: .78rem;
    }

    th {
      background: var(--surface);
      color: var(--accent-2);
    }

    td {
      background: var(--surface);
    }

    td.one {
      background: rgba(34, 197, 94, .12);
      border-color: rgba(34, 197, 94, .34);
    }

    td.active {
      background: rgba(56, 189, 248, .16);
      border-color: rgba(125, 211, 252, .45);
    }

    .footer-card {
      display: grid;
      gap: 6px;
      align-content: start;
      overflow-y: auto;
    }

    #insightText {
      margin: 0;
      color: var(--text);
      font-size: .8rem;
      line-height: 1.55;
    }

    .step-list,
    .code-lines {
      display: grid;
      gap: 8px;
    }

    .step-item {
      display: flex;
      gap: 8px;
      padding: 4px 10px;
      border-radius: 12px;
      border: 1px solid var(--border);
      background: var(--surface);
      line-height: 1.55;
      font-size: .76rem;
    }

    .step-item.done {
      border-color: rgba(34, 197, 94, .3);
    }

    .step-item.active {
      border-color: rgba(56, 189, 248, .42);
      background: rgba(56, 189, 248, .08);
    }

    .step-index {
      min-width: 24px;
      font-family: "Fira Code", monospace;
      color: var(--accent-2);
    }

    .step-item.done .step-index {
      color: var(--good);
    }

    .flow-code-lines {
      margin-top: 0;
      min-height: 220px;
      max-height: 220px;
    }

    .code-line {
      display: grid;
      grid-template-columns: 26px 1fr;
      gap: 8px;
      padding: 7px 8px;
      border-radius: 12px;
      border: 1px solid var(--border);
      background: var(--surface);
      font-family: "Fira Code", monospace;
      font-size: .74rem;
      color: #cbd5e1;
    }

    .code-line.active {
      border-color: rgba(56, 189, 248, .38);
      background: rgba(56, 189, 248, .1);
    }

    .code-line-num {
      color: #64748b;
      text-align: right;
    }

    .code-text {
      white-space: pre;
    }

    .theory-grid {
      padding: 16px;
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 12px;
    }

    .theory-card {
      padding: 16px;
      display: grid;
      gap: 10px;
      align-content: start;
    }

    .theory-card ul {
      padding-left: 18px;
      display: grid;
      gap: 8px;
    }

    .theory-log-card {
      grid-column: 1 / -1;
    }

    .theory-log-list {
      max-height: 250px;
      min-height: 250px;
      overflow-y: auto;
      padding-right: 4px;
    }

    pre {
      padding: 14px;
      border-radius: 16px;
      border: 1px solid var(--border);
      background: #09101f;
      overflow: auto;
    }

    code {
      font-family: "Fira Code", monospace;
      color: #dbe4f3;
      white-space: pre;
    }

    @media (max-width: 960px) {
      body {
        height: auto;
        min-height: 100vh;
        overflow: auto;
      }

      .app-body {
        flex-direction: column;
        overflow: visible;
      }

      .left-panel {
        width: auto;
        min-width: 0;
      }

      .workspace {
        overflow: visible;
      }

      .viz-area,
      .theory-grid {
        grid-template-columns: 1fr;
        grid-template-rows: auto;
      }

      .canvas-card,
      .matrix-card,
      .stack {
        grid-column: auto;
        grid-row: auto;
        height: auto;
        min-height: 0;
      }

      .stack {
        grid-template-rows: none;
      }

      .graph-stage {
        height: 260px;
        min-height: 260px;
        max-height: 260px;
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
  <header>
    <div>
      <h1>Undirected Graph <span>Dashboard</span></h1>
      <p>Build the same graph as a drawing, adjacency list, adjacency matrix, and edge list side by side.</p>
    </div>
    <div class="header-actions">
      <a class="header-link primary" href="../index.php">Home</a>
      <a class="header-link" href="directed_graph_representation.php">Directed Graph</a>
      <label class="speed-wrap">Speed:
        <input id="speedSlider" type="range" min="1" max="10" value="6" />
      </label>
    </div>
  </header>

  <div class="app-body">
    <aside class="left-panel">
      <div class="panel-header"><div class="pulse-dot"></div>Undirected Graph Panel</div>
      <section class="panel-section">
        <div class="panel-section-title">Snapshot</div>
        <div class="summary-grid">
          <div class="metric"><div class="metric-label">Vertices</div><div class="metric-value" id="vertexCount">0</div></div>
          <div class="metric"><div class="metric-label">Edges Added</div><div class="metric-value" id="edgeCountMetric">0 / 0</div></div>
          <div class="metric"><div class="metric-label">Current Edge</div><div class="metric-value" id="currentEdgeMetric">-</div></div>
          <div class="metric"><div class="metric-label">Max Degree</div><div class="metric-value" id="maxDegreeMetric">0</div></div>
          <div class="metric"><div class="metric-label">Focus</div><div class="metric-value" id="focusMetric">-</div></div>
          <div class="metric"><div class="metric-label">Step</div><div class="metric-value" id="progressMetric">0 / 0</div></div>
        </div>
      </section>
      <section class="panel-section" style="flex:1; min-height:0;">
        <div class="panel-section-title">Algorithm Flow + Active Pseudocode</div>
        <div class="flow-merge">
          <div class="flow-block">
            <div class="panel-mini-title">Flow Steps</div>
            <div class="step-list" id="stepBox"></div>
          </div>
          <div class="flow-block">
            <div class="panel-mini-title">Live Pseudocode</div>
            <div class="code-lines flow-code-lines" id="codeLines"></div>
          </div>
        </div>
      </section>
    </aside>

    <main class="workspace">
      <nav class="tabs">
        <button class="tab-btn active" onclick="showTab('animation',this)">Animation</button>
        <button class="tab-btn" onclick="showTab('theory',this)">Theory</button>
        <button class="tab-btn" onclick="showTab('pseudocode',this)">Pseudocode</button>
      </nav>

      <section id="animation" class="tab-panel visualizer-panel active">
        <section class="control-row">
          <label class="field">
            <span>Sample</span>
            <select id="sampleSelect"></select>
          </label>
          <div class="button-row">
            <button id="loadBtn">Load Sample</button>
            <button id="nextBtn">Next Edge</button>
            <button class="primary" id="playBtn">Auto Play</button>
            <button id="resetBtn">Reset</button>
          </div>
        </section>

        <section class="viz-area">
          <article class="canvas-card" id="graphCard">
            <div class="card-head">
              <div>
                <div class="card-title">Graph Drawing</div>
                <div class="card-subtitle" id="statusText">Load a sample and add edges one by one to see all undirected representations update together.</div>
              </div>
              <div class="legend">
                <div class="legend-item"><span class="legend-line" style="color:#22c55e;"></span><span>Added</span></div>
                <div class="legend-item"><span class="legend-line" style="color:#38bdf8;"></span><span>Current</span></div>
                <div class="legend-item"><span class="legend-line" style="color:#f59e0b; border-top-style:dashed;"></span><span>Next</span></div>
              </div>
            </div>
            <div class="graph-stage"><svg id="graphSvg" viewBox="0 0 760 430" preserveAspectRatio="xMidYMid meet"></svg></div>
          </article>

          <article class="stack-card matrix-card" id="matrixCard">
            <div class="stack-card-body">
              <div class="stack-card-head">
                <h2>Adjacency Matrix</h2>
                <p>Undirected graphs mirror every edge in both symmetric matrix cells.</p>
              </div>
              <div class="matrix-wrap" id="matrixWrap"></div>
            </div>
          </article>

          <section class="stack">
            <article class="stack-card adjacency-card" id="adjacencyCard">
              <div class="stack-card-body">
                <div class="stack-card-head">
                  <h2>Adjacency List</h2>
                  <p>Each endpoint stores the other because there is no arrow direction.</p>
                </div>
                <div class="list-grid" id="adjList"></div>
              </div>
            </article>

            <article class="stack-card" id="edgeCard">
              <div class="stack-card-body">
                <div class="stack-card-head">
                  <h2>Edge List</h2>
                  <p>Each undirected pair is stored once as a single connection entry.</p>
                </div>
                <div class="edge-list" id="edgeList"></div>
              </div>
            </article>

            <article class="footer-card" id="insightCard">
              <h2>Current Insight</h2>
              <p id="insightText">An undirected edge updates both adjacency rows and two mirrored matrix cells.</p>
            </article>
          </section>
        </section>
      </section>

      <section id="theory" class="tab-panel">
        <section class="theory-grid">
          <article class="theory-card">
            <h3>What Undirected Means</h3>
            <ul>
              <li>An undirected edge has no arrow, so the connection works both ways.</li>
              <li>If <code>A</code> connects to <code>B</code>, then <code>B</code> also connects to <code>A</code>.</li>
              <li>That shared relationship is why the matrix stays symmetric.</li>
            </ul>
          </article>
          <article class="theory-card">
            <h3>Adjacency List Effect</h3>
            <ul>
              <li>Adding <code>(u, v)</code> inserts <code>v</code> in <code>u</code>'s list and <code>u</code> in <code>v</code>'s list.</li>
              <li>This form is usually best for sparse graphs.</li>
              <li>The active rows in the animation show exactly which two lists changed.</li>
            </ul>
          </article>
          <article class="theory-card">
            <h3>Adjacency Matrix Effect</h3>
            <ul>
              <li>The matrix uses <code>1</code> when an edge exists and <code>0</code> when it does not.</li>
              <li>For undirected graphs, <code>matrix[u][v]</code> and <code>matrix[v][u]</code> always match.</li>
              <li>Fast lookups come at the cost of <code>O(V^2)</code> space.</li>
            </ul>
          </article>
          <article class="theory-card">
            <h3>Why Keep an Edge List</h3>
            <ul>
              <li>The edge list keeps each connection once as a pair like <code>(u, v)</code>.</li>
              <li>It is simple to scan, sort, or feed into graph algorithms.</li>
              <li>The animation log shows when each pair becomes active, added, or still pending.</li>
            </ul>
          </article>
          <article class="theory-card theory-log-card">
            <h3>Execution Log</h3>
            <p>The same step-by-step build sequence shown in the animation is collected here with the representation changes spelled out.</p>
            <div class="edge-list theory-log-list" id="theoryActionList"></div>
          </article>
        </section>
      </section>

      <section id="pseudocode" class="tab-panel">
        <section class="theory-grid">
          <article class="theory-card">
            <h3>Pseudocode</h3>
            <pre><code>for each undirected edge (u, v):
    draw the line between u and v
    adjacency[u].append(v)
    adjacency[v].append(u)
    matrix[u][v] = 1
    matrix[v][u] = 1
    edgeList.append((u, v))</code></pre>
          </article>
          <article class="theory-card">
            <h3>C Code: Adjacency Matrix</h3>
            <pre><code>#define V 6

void addEdge(int matrix[V][V], int u, int v) {
    matrix[u][v] = 1;
    matrix[v][u] = 1;
}</code></pre>
          </article>
          <article class="theory-card">
            <h3>C Code: Adjacency List</h3>
            <pre><code>void addEdge(Node* graph[], int u, int v) {
    addNode(&graph[u], v);
    addNode(&graph[v], u);
}</code></pre>
          </article>
          <article class="theory-card">
            <h3>Storage Idea</h3>
            <p>All three structures describe the same undirected graph. The only difference is how the information is arranged for lookup, traversal, and algorithm use.</p>
          </article>
        </section>
      </section>
    </main>
  </div>
  <script>
    const FLOW_STEPS = [
      'Choose the next undirected edge from the sample order.',
      'Draw that edge between both vertices in the graph.',
      'Add each endpoint to the other vertex\'s adjacency list.',
      'Mirror the connection in two symmetric matrix cells.',
      'Store the pair once in the edge list.'
    ];

    const PSEUDO_LINES = [
      'edge = nextUndirectedEdge()',
      'draw(u, v)',
      'adj[u].append(v)',
      'adj[v].append(u)',
      'matrix[u][v] = 1',
      'matrix[v][u] = 1',
      'edgeList.append((u, v))'
    ];

    const samples = {
      classroom: {
        label: 'Classroom Graph',
        vertices: ['A', 'B', 'C', 'D', 'E', 'F'],
        positions: {
          A: [120, 100],
          B: [320, 70],
          C: [520, 110],
          D: [220, 260],
          E: [430, 260],
          F: [620, 240]
        },
        edges: [['A', 'B'], ['A', 'C'], ['B', 'D'], ['C', 'D'], ['C', 'E'], ['D', 'F'], ['E', 'F']]
      },
      bridge: {
        label: 'Bridge Graph',
        vertices: ['P', 'Q', 'R', 'S', 'T', 'U'],
        positions: {
          P: [110, 110],
          Q: [280, 70],
          R: [460, 110],
          S: [170, 280],
          T: [370, 280],
          U: [600, 190]
        },
        edges: [['P', 'Q'], ['Q', 'R'], ['P', 'S'], ['Q', 'S'], ['Q', 'T'], ['R', 'T'], ['T', 'U']]
      }
    };

    const ui = {
      sampleSelect: document.getElementById('sampleSelect'),
      loadBtn: document.getElementById('loadBtn'),
      nextBtn: document.getElementById('nextBtn'),
      playBtn: document.getElementById('playBtn'),
      resetBtn: document.getElementById('resetBtn'),
      speedSlider: document.getElementById('speedSlider'),
      graphSvg: document.getElementById('graphSvg'),
      adjList: document.getElementById('adjList'),
      edgeList: document.getElementById('edgeList'),
      matrixWrap: document.getElementById('matrixWrap'),
      theoryActionList: document.getElementById('theoryActionList'),
      vertexCount: document.getElementById('vertexCount'),
      edgeCountMetric: document.getElementById('edgeCountMetric'),
      currentEdgeMetric: document.getElementById('currentEdgeMetric'),
      maxDegreeMetric: document.getElementById('maxDegreeMetric'),
      focusMetric: document.getElementById('focusMetric'),
      progressMetric: document.getElementById('progressMetric'),
      statusText: document.getElementById('statusText'),
      insightText: document.getElementById('insightText'),
      stepBox: document.getElementById('stepBox'),
      codeLines: document.getElementById('codeLines'),
      graphCard: document.getElementById('graphCard'),
      adjacencyCard: document.getElementById('adjacencyCard'),
      edgeCard: document.getElementById('edgeCard'),
      matrixCard: document.getElementById('matrixCard'),
      insightCard: document.getElementById('insightCard')
    };

    const state = {
      sampleKey: 'classroom',
      sample: samples.classroom,
      stepIndex: 0,
      running: false,
      timer: null,
      delay: 620
    };

    function showTab(id, btn) {
      document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.remove('active'));
      document.querySelectorAll('.tab-btn').forEach(button => button.classList.remove('active'));
      document.getElementById(id).classList.add('active');
      btn.classList.add('active');
    }

    function populateSamples() {
      ui.sampleSelect.innerHTML = Object.entries(samples)
        .map(([key, sample]) => `<option value="${key}">${sample.label}</option>`)
        .join('');
      ui.sampleSelect.value = state.sampleKey;
    }

    function speedToDelay(value) {
      return 1260 - value * 95;
    }

    function formatEdge(edge) {
      return edge ? `(${edge[0]}, ${edge[1]})` : '-';
    }

    function adjacency() {
      const map = Object.fromEntries(state.sample.vertices.map(vertex => [vertex, []]));
      state.sample.edges.slice(0, state.stepIndex).forEach(([u, v]) => {
        map[u].push(v);
        map[v].push(u);
      });
      return map;
    }

    function buildMatrix(edges) {
      const vertices = state.sample.vertices;
      const matrix = Object.fromEntries(vertices.map(row => [row, Object.fromEntries(vertices.map(col => [col, 0]))]));
      edges.forEach(([u, v]) => {
        matrix[u][v] = 1;
        matrix[v][u] = 1;
      });
      return matrix;
    }

    function renderSteps(activeStep) {
      ui.stepBox.innerHTML = FLOW_STEPS.map((step, index) => {
        const done = state.stepIndex > 0 && index < activeStep;
        const active = index === activeStep;
        return `<div class="step-item ${done ? 'done' : ''} ${active ? 'active' : ''}"><span class="step-index">${index + 1}.</span><span>${step}</span></div>`;
      }).join('');
    }

    function renderCodeLines(activeLineSet) {
      ui.codeLines.innerHTML = PSEUDO_LINES.map((line, index) => `<div class="code-line ${activeLineSet.has(index) ? 'active' : ''}"><span class="code-line-num">${index + 1}</span><span class="code-text">${line}</span></div>`).join('');
    }

    function buildLogItems() {
      return state.sample.edges.map(([u, v], index) => {
        const done = index < state.stepIndex;
        const active = index === state.stepIndex - 1;
        const pending = index >= state.stepIndex;
        const message = done
          ? `Add ${v} to ${u}, add ${u} to ${v}, and mirror both matrix cells.`
          : `Wait to connect ${u} and ${v} in every undirected representation.`;
        const stateLabel = active ? 'current' : done ? 'added' : 'pending';
        return `<div class="edge-item ${done ? 'done' : ''} ${active ? 'active' : ''} ${pending ? 'pending' : ''}">
          <div class="edge-head">
            <span class="edge-type">edge ${index + 1}: (${u}, ${v})</span>
            <span class="edge-state">${stateLabel}</span>
          </div>
          <div class="edge-msg">${message}</div>
        </div>`;
      }).join('');
    }

    function toggleFocus(element, active) {
      element.classList.toggle('focus-card', active);
    }

    function render() {
      const adj = adjacency();
      const currentEdge = state.stepIndex > 0 ? state.sample.edges[state.stepIndex - 1] : null;
      const nextEdge = state.stepIndex < state.sample.edges.length ? state.sample.edges[state.stepIndex] : null;
      const edges = state.sample.edges.slice(0, state.stepIndex);
      const matrix = buildMatrix(edges);
      const currentSet = new Set(currentEdge || []);
      const activeStep = state.stepIndex === 0 ? 0 : FLOW_STEPS.length - 1;
      const activeLines = state.stepIndex === 0 ? new Set([0]) : new Set([1, 2, 3, 4, 5, 6]);

      ui.vertexCount.textContent = state.sample.vertices.length;
      ui.edgeCountMetric.textContent = `${state.stepIndex} / ${state.sample.edges.length}`;
      ui.currentEdgeMetric.textContent = formatEdge(currentEdge);
      ui.maxDegreeMetric.textContent = Math.max(...state.sample.vertices.map(vertex => adj[vertex].length), 0);
      ui.focusMetric.textContent = currentEdge ? `${currentEdge[0]} <-> ${currentEdge[1]}` : '-';
      ui.progressMetric.textContent = `${state.stepIndex} / ${state.sample.edges.length}`;
      ui.statusText.textContent = currentEdge
        ? `Edge ${formatEdge(currentEdge)} is now active. Both endpoints store each other, and the matrix mirrors the connection in both directions.${nextEdge ? ` Next: ${formatEdge(nextEdge)}.` : ' The sample is complete.'}`
        : `Start with the next sample edge. The same undirected pair will update the drawing, adjacency list, matrix, and edge list together.`;
      ui.insightText.textContent = currentEdge
        ? `Because ${currentEdge[0]} and ${currentEdge[1]} are connected without direction, both adjacency rows change and both matrix cells become 1.`
        : 'An undirected edge updates both adjacency rows and two mirrored matrix cells.';

      renderSteps(activeStep);
      renderCodeLines(activeLines);
      ui.edgeList.innerHTML = buildLogItems();
      ui.theoryActionList.innerHTML = buildLogItems();

      toggleFocus(ui.graphCard, Boolean(currentEdge));
      toggleFocus(ui.adjacencyCard, Boolean(currentEdge));
      toggleFocus(ui.edgeCard, Boolean(currentEdge));
      toggleFocus(ui.matrixCard, Boolean(currentEdge));
      toggleFocus(ui.insightCard, Boolean(currentEdge));

      const lines = edges.map(([u, v], index) => {
        const [x1, y1] = state.sample.positions[u];
        const [x2, y2] = state.sample.positions[v];
        const active = index === state.stepIndex - 1;
        return `<line x1="${x1}" y1="${y1}" x2="${x2}" y2="${y2}" stroke="${active ? '#38bdf8' : '#22c55e'}" stroke-width="${active ? 6 : 4}" stroke-linecap="round" opacity="${active ? 1 : .9}"/>`;
      }).join('');

      const preview = nextEdge ? (() => {
        const [u, v] = nextEdge;
        const [x1, y1] = state.sample.positions[u];
        const [x2, y2] = state.sample.positions[v];
        return `<line x1="${x1}" y1="${y1}" x2="${x2}" y2="${y2}" stroke="#f59e0b" stroke-width="4" stroke-dasharray="10 8" stroke-linecap="round" opacity=".85"/>`;
      })() : '';

      const nodes = state.sample.vertices.map(vertex => {
        const [x, y] = state.sample.positions[vertex];
        const active = currentSet.has(vertex);
        const fill = active ? '#38bdf8' : '#1a2333';
        const stroke = active ? '#7dd3fc' : '#4f6287';
        const text = active ? '#041522' : '#e2e8f0';
        return `<g>
          <circle cx="${x}" cy="${y}" r="30" fill="${fill}" stroke="${stroke}" stroke-width="3"/>
          <text x="${x}" y="${y + 5}" text-anchor="middle" font-family="Fira Code, monospace" font-size="18" fill="${text}">${vertex}</text>
          <text x="${x}" y="${y + 49}" text-anchor="middle" font-family="Inter, sans-serif" font-size="11" fill="#94a3b8">deg ${adj[vertex].length}</text>
        </g>`;
      }).join('');

      ui.graphSvg.innerHTML = `<rect x="8" y="8" width="744" height="414" rx="24" fill="#101826" stroke="#263247"/>${lines}${preview}${nodes}`;

      ui.adjList.innerHTML = state.sample.vertices.map(vertex => {
        const values = adj[vertex].length
          ? adj[vertex].map(neighbor => {
            const active = currentEdge && ((vertex === currentEdge[0] && neighbor === currentEdge[1]) || (vertex === currentEdge[1] && neighbor === currentEdge[0]));
            return `<div class="chip ${active ? 'active' : ''}">${neighbor}</div>`;
          }).join('')
          : '<div class="empty">empty</div>';
        return `<div class="list-row ${currentSet.has(vertex) ? 'active' : ''}"><div class="list-head">${vertex}</div><div class="list-body">${values}</div></div>`;
      }).join('');

      let matrixHtml = '<table><tr><th></th>' + state.sample.vertices.map(vertex => `<th>${vertex}</th>`).join('') + '</tr>';
      state.sample.vertices.forEach(row => {
        matrixHtml += `<tr><th>${row}</th>`;
        state.sample.vertices.forEach(col => {
          const on = matrix[row][col] === 1;
          const active = currentEdge && ((row === currentEdge[0] && col === currentEdge[1]) || (row === currentEdge[1] && col === currentEdge[0]));
          matrixHtml += `<td class="${on ? 'one' : ''} ${active ? 'active' : ''}">${matrix[row][col]}</td>`;
        });
        matrixHtml += '</tr>';
      });
      ui.matrixWrap.innerHTML = matrixHtml + '</table>';
    }

    function resetCurrent() {
      stop();
      state.stepIndex = 0;
      ui.playBtn.textContent = 'Auto Play';
      render();
    }

    function loadSample(key) {
      stop();
      state.sampleKey = key;
      state.sample = samples[key];
      state.stepIndex = 0;
      ui.sampleSelect.value = key;
      ui.playBtn.textContent = 'Auto Play';
      render();
    }

    function nextEdge() {
      if (state.stepIndex >= state.sample.edges.length) {
        stop();
        ui.playBtn.textContent = 'Replay';
        return;
      }
      state.stepIndex += 1;
      render();
    }

    function stop() {
      state.running = false;
      if (state.timer) {
        clearTimeout(state.timer);
        state.timer = null;
      }
    }

    function loop() {
      if (!state.running) return;
      if (state.stepIndex >= state.sample.edges.length) {
        stop();
        ui.playBtn.textContent = 'Replay';
        return;
      }
      nextEdge();
      state.timer = setTimeout(loop, state.delay);
    }

    ui.loadBtn.addEventListener('click', () => loadSample(ui.sampleSelect.value));
    ui.nextBtn.addEventListener('click', () => {
      stop();
      ui.playBtn.textContent = 'Auto Play';
      nextEdge();
    });
    ui.playBtn.addEventListener('click', () => {
      if (state.running) {
        stop();
        ui.playBtn.textContent = 'Resume';
        return;
      }
      if (state.stepIndex >= state.sample.edges.length) {
        resetCurrent();
      }
      state.running = true;
      ui.playBtn.textContent = 'Pause';
      loop();
    });
    ui.resetBtn.addEventListener('click', resetCurrent);
    ui.speedSlider.addEventListener('input', () => {
      state.delay = speedToDelay(Number(ui.speedSlider.value));
    });

    populateSamples();
    state.delay = speedToDelay(Number(ui.speedSlider.value));
    loadSample('classroom');
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
