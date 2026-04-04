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
  <title>Recursion and Dynamic Programming</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Fira+Code:wght@400;500&display=swap"
    rel="stylesheet" />
  <style>
    :root {
      --bg: #0a1018;
      --panel: #101824;
      --surface: #162231;
      --card: #1b2b3d;
      --border: #33485f;
      --text: #ebf5ff;
      --muted: #91a6bb;
      --accent: #22d3ee;
      --accent2: #fb923c;
      --accent3: #a7f3d0;
      --shadow: 0 22px 48px rgba(0, 0, 0, .3)
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
      --accent: #0891b2;
      --accent2: #c2410c;
      --accent3: #059669;
    }
    body.light-mode header {
      background: var(--panel);
      border-bottom-color: var(--border);
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
    body.light-mode .box {
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
    body.light-mode .mini,
    body.light-mode .cmp,
    body.light-mode .ex {
      background: var(--card);
    }
    body.light-mode pre {
      background: var(--surface);
      border-color: var(--border);
      color: var(--text);
    }
    body.light-mode code {
      color: var(--text);
    }
    body.light-mode .sim-btn {
      background: var(--surface);
    }
    body.light-mode .editor-field input,
    body.light-mode .editor-field textarea {
      background: var(--surface);
      border-color: var(--border);
    }




    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      background: radial-gradient(circle at top left, rgba(34, 211, 238, .1), transparent 24%), radial-gradient(circle at bottom right, rgba(251, 146, 60, .08), transparent 28%), var(--bg);
      color: var(--text);
      font-family: Inter, sans-serif
    }

    header {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 14px 24px;
      border-bottom: 1px solid var(--border);
      background: linear-gradient(90deg, #0d2231, #0a1018)
    }

    header h1 {
      font-size: 1.2rem;
      font-weight: 800
    }

    header h1 span {
      color: var(--accent)
    }

    header p {
      color: var(--muted);
      font-size: .8rem;
      line-height: 1.6
    }

    .actions {
      margin-left: auto;
      display: flex;
      flex-wrap: wrap;
      gap: 10px
    }

    .actions a {
      text-decoration: none;
      padding: 8px 12px;
      border-radius: 999px;
      border: 1px solid var(--border);
      background: var(--surface);
      color: var(--muted);
      font-size: .78rem;
      transition: transform .18s ease, border-color .18s ease, background .18s ease, color .18s ease
    }

    .actions a:hover {
      transform: translateY(-1px);
      border-color: rgba(34, 211, 238, .36);
      color: var(--text)
    }

    .actions a.primary {
      background: linear-gradient(135deg, #06b6d4, var(--accent2));
      border-color: transparent;
      color: #1a1309;
      font-weight: 800
    }

    .app {
      flex: 1;
      display: flex;
      min-height: calc(100vh - 80px)
    }

    .left {
      width: 330px;
      min-width: 330px;
      background: var(--panel);
      border-right: 1px solid var(--border)
    }

    .panel-head {
      padding: 9px 12px 7px;
      border-bottom: 1px solid var(--border);
      font-size: .7rem;
      letter-spacing: .08em;
      text-transform: uppercase;
      font-weight: 800;
      color: var(--accent);
      display: flex;
      align-items: center;
      gap: 8px
    }

    .pulse {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--accent2);
      box-shadow: 0 0 10px rgba(251, 146, 60, .7);
      animation: pulse 1.3s infinite;
      flex-shrink: 0
    }

    @keyframes pulse {

      0%,
      100% {
        transform: scale(1);
        opacity: 1
      }

      50% {
        transform: scale(.84);
        opacity: .5
      }
    }

    .sec {
      border-bottom: 1px solid var(--border)
    }

    .sec-title {
      padding: 8px 12px 4px;
      color: var(--muted);
      font-size: .67rem;
      font-weight: 800;
      letter-spacing: .08em;
      text-transform: uppercase
    }

    .box {
      margin: 0 12px 10px;
      padding: 12px;
      border-radius: 14px;
      border: 1px solid var(--border);
      background: var(--card);
      color: var(--muted);
      font-size: .8rem;
      line-height: 1.65
    }

    .focus {
      color: var(--text);
      font-size: .94rem;
      font-weight: 800;
      line-height: 1.5;
      margin-bottom: 6px
    }

    .formula {
      color: var(--accent3);
      font-family: "Fira Code", monospace
    }

    .metrics {
      padding: 0 12px 12px;
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 8px
    }

    .metric {
      border: 1px solid var(--border);
      border-radius: 12px;
      background: var(--card);
      padding: 10px;
      box-shadow: var(--shadow)
    }

    .metric-label {
      color: var(--muted);
      font-size: .64rem;
      font-weight: 800;
      letter-spacing: .08em;
      text-transform: uppercase;
      margin-bottom: 5px
    }

    .metric-value {
      font-size: .8rem;
      line-height: 1.45;
      font-family: "Fira Code", monospace
    }

    .list {
      padding: 0 12px 12px;
      display: grid;
      gap: 8px
    }

    .list div {
      position: relative;
      padding-left: 14px;
      color: var(--muted);
      font-size: .8rem;
      line-height: 1.58
    }

    .list div:before {
      content: "";
      position: absolute;
      left: 0;
      top: .55rem;
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: var(--accent)
    }

    .chips {
      padding: 0 12px 12px;
      display: flex;
      flex-wrap: wrap;
      gap: 8px
    }

    .chip,
    .badge {
      display: inline-flex;
      align-items: center;
      padding: 6px 10px;
      border-radius: 999px;
      font-size: .72rem;
      font-weight: 800
    }

    .chip {
      color: #d7fbff;
      background: rgba(34, 211, 238, .09);
      border: 1px solid rgba(34, 211, 238, .22)
    }

    .badge {
      color: #ffedd5;
      background: rgba(251, 146, 60, .09);
      border: 1px solid rgba(251, 146, 60, .22)
    }

    .work {
      flex: 1;
      min-width: 0;
      display: flex;
      flex-direction: column
    }

    .tabs {
      display: flex;
      gap: 2px;
      padding: 8px 16px 0;
      border-bottom: 1px solid var(--border);
      background: var(--surface);
      overflow-x: auto
    }

    .tabs::-webkit-scrollbar {
      height: 5px
    }

    .tabs::-webkit-scrollbar-thumb {
      background: var(--border);
      border-radius: 999px
    }

    .tab {
      padding: 9px 18px;
      border: none;
      background: transparent;
      color: var(--muted);
      border-radius: 8px 8px 0 0;
      cursor: pointer;
      font-size: .82rem;
      font-weight: 700;
      border-bottom: 2px solid transparent;
      transition: color .18s ease, background .18s ease;
      white-space: nowrap
    }

    .tab:hover {
      color: var(--text);
      background: var(--surface)
    }

    .tab.active {
      color: var(--accent);
      border-bottom-color: var(--accent);
      background: var(--card)
    }

    .controls {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      padding: 12px 16px 10px;
      border-bottom: 1px solid var(--border);
      align-items: center
    }

    .field {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 10px 12px;
      border: 1px solid var(--border);
      border-radius: 14px;
      background: var(--card)
    }

    .field span {
      color: var(--muted);
      font-size: .72rem;
      font-weight: 800;
      letter-spacing: .08em;
      text-transform: uppercase
    }

    .field select,
    .field input {
      border: none;
      background: transparent;
      color: var(--text);
      outline: none;
      font: .8rem "Fira Code", monospace
    }

    .range {
      display: flex;
      align-items: center;
      gap: 10px;
      min-width: 220px
    }

    .range input {
      width: 100%;
      accent-color: var(--accent)
    }

    .value {
      color: var(--accent2);
      font: .8rem "Fira Code", monospace;
      letter-spacing: 0;
      text-transform: none
    }

    .panel {
      display: none;
      padding: 12px 16px 16px;
      overflow-y: auto
    }

    .panel.active {
      display: block
    }

    .status {
      padding: 0 0 12px;
      color: var(--text);
      line-height: 1.7;
      font-size: .84rem
    }

    .grid {
      display: grid;
      grid-template-columns: 1.04fr .96fr;
      gap: 12px;
      align-items: start
    }

    .card {
      display: grid;
      gap: 14px;
      padding: 18px;
      border-radius: 20px;
      border: 1px solid var(--border);
      background: var(--card);
      box-shadow: var(--shadow)
    }

    .focus-card {
      border-color: rgba(34, 211, 238, .28);
      box-shadow: 0 0 0 1px rgba(34, 211, 238, .12), 0 20px 42px rgba(0, 0, 0, .28)
    }

    .kick {
      color: var(--muted);
      font-size: .72rem;
      font-weight: 800;
      letter-spacing: .08em;
      text-transform: uppercase
    }

    .card h2 {
      font-size: 1.1rem;
      line-height: 1.45
    }

    .sub {
      color: var(--muted);
      line-height: 1.7;
      font-size: .9rem
    }

    .stage,
    .info,
    .examples,
    .compare {
      display: grid;
      gap: 10px
    }

    .stage {
      grid-template-columns: repeat(4, minmax(0, 1fr))
    }

    .info,
    .compare {
      grid-template-columns: repeat(2, minmax(0, 1fr))
    }

    .examples {
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr))
    }

    .mini,
    .cmp,
    .ex {
      display: grid;
      gap: 6px;
      padding: 12px;
      border-radius: 14px;
      border: 1px solid rgba(148, 163, 184, .12);
      background: var(--surface)
    }

    .mini h3,
    .cmp h3,
    .ex h3 {
      font-size: .92rem
    }

    .mini p,
    .cmp p,
    .ex p,
    .cmp li {
      color: var(--muted);
      font-size: .82rem;
      line-height: 1.62
    }

    .cmp ul {
      padding-left: 18px
    }

    .step {
      color: var(--accent3);
      font-size: .66rem;
      font-weight: 800;
      letter-spacing: .08em;
      text-transform: uppercase
    }

    pre {
      padding: 14px;
      border-radius: 16px;
      border: 1px solid var(--border);
      background: var(--card);
      color: var(--text);
      font: .82rem "Fira Code", monospace;
      line-height: 1.72;
      white-space: pre-wrap;
      overflow-x: auto
    }

    .bars {
      display: grid;
      gap: 10px
    }

    .bar {
      display: grid;
      grid-template-columns: 160px minmax(0, 1fr) auto;
      gap: 10px;
      align-items: center
    }

    .bar strong {
      font-size: .78rem
    }

    .bar span {
      color: var(--muted);
      font-size: .72rem;
      line-height: 1.5
    }

    .track {
      height: 10px;
      border-radius: 999px;
      overflow: hidden;
      border: 1px solid rgba(148, 163, 184, .12);
      background: rgba(148, 163, 184, .08)
    }

    .fill {
      height: 100%;
      border-radius: 999px;
      background: linear-gradient(90deg, var(--accent), var(--accent2))
    }

    .barv {
      color: var(--accent2);
      font: .76rem "Fira Code", monospace
    }

    .ex.active {
      border-color: rgba(34, 211, 238, .34);
      box-shadow: 0 0 0 1px rgba(34, 211, 238, .14)
    }

    .note {
      color: var(--muted);
      font-size: .82rem;
      line-height: 1.7
    }

    .sim-controls {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      align-items: center
    }

    .sim-btn {
      padding: 9px 14px;
      border-radius: 12px;
      border: 1px solid var(--border);
      background: var(--card);
      color: var(--text);
      font: 600 .78rem "Inter", sans-serif;
      cursor: pointer
    }

    .sim-btn:hover {
      border-color: rgba(34, 211, 238, .34)
    }

    .sim-stack,
    .sim-cache,
    .sim-log {
      display: grid;
      gap: 10px
    }

    .sim-frame,
    .sim-cache-chip,
    .sim-log-line {
      padding: 10px 12px;
      border-radius: 12px;
      border: 1px solid rgba(148, 163, 184, .12);
      background: var(--surface)
    }

    .sim-frame {
      font: .8rem "Fira Code", monospace;
      color: var(--text)
    }

    .sim-frame.active {
      border-color: rgba(34, 211, 238, .34);
      box-shadow: 0 0 0 1px rgba(34, 211, 238, .14)
    }

    .sim-cache {
      grid-template-columns: repeat(auto-fit, minmax(130px, 1fr))
    }

    .sim-cache-chip {
      display: grid;
      gap: 4px
    }

    .sim-cache-chip strong {
      font-size: .72rem;
      color: var(--muted)
    }

    .sim-cache-chip span {
      font: .86rem "Fira Code", monospace;
      color: var(--text)
    }

    .sim-log-line.active {
      border-color: rgba(251, 146, 60, .3);
      color: var(--text)
    }

    .mono {
      font-family: "Fira Code", monospace
    }

    .editor-grid {
      display: grid;
      gap: 12px;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr))
    }

    .editor-field {
      display: grid;
      gap: 6px
    }

    .editor-field strong {
      font-size: .74rem;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: var(--muted)
    }

    .editor-field input,
    .editor-field textarea {
      width: 100%;
      border: 1px solid var(--border);
      border-radius: 12px;
      background: var(--card);
      color: var(--text);
      padding: 10px 12px;
      font: 0.8rem "Fira Code", monospace;
      outline: none
    }

    .editor-field textarea {
      min-height: 110px;
      resize: vertical
    }

    .hidden {
      display: none !important
    }

    code {
      padding: 2px 6px;
      border-radius: 8px;
      background: rgba(34, 211, 238, .08);
      color: var(--text);
      font-family: "Fira Code", monospace;
      font-size: .9em
    }

    @media (max-width:1120px) {
      .app {
        flex-direction: column
      }

      .left {
        width: 100%;
        min-width: 0;
        border-right: none;
        border-bottom: 1px solid var(--border)
      }

      .grid,
      .stage,
      .info,
      .compare {
        grid-template-columns: 1fr
      }
    }

    @media (max-width:760px) {
      header {
        padding: 12px 16px;
        flex-wrap: wrap
      }

      .actions {
        margin-left: 0
      }

      .metrics,
      .info,
      .compare {
        grid-template-columns: 1fr
      }

      .bar {
        grid-template-columns: 1fr
      }

      .range {
        min-width: 0
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
      <h1><span>Recursion</span> and Dynamic Programming</h1>
      <p>See how a recursive definition becomes memoization, then becomes tabulation once the repeated subproblems are
        made explicit.</p>
    </div>
    <div class="actions">
      <a class="primary" href="../index.php">Home</a>
      <a href="dynamic_programming_introduction.php">DP Intro</a>
      <a href="zero_one_knapsack_problem.php">0/1 Knapsack</a>
      <a href="zero_one_knapsack_recursion.php">Knapsack Recursion</a>
      <a href="zero_one_knapsack_using_set_method.php">Knapsack Set Method</a>
      <a href="../Divide%20and%20Conquer/divide_and_conquer_introduction.php">Divide and Conquer</a>
    </div>
  </header>

  <div class="app">
    <aside class="left">
      <div class="panel-head"><span class="pulse"></span>Recursion to DP Snapshot</div>

      <section class="sec">
        <div class="sec-title">Core Bridge</div>
        <div class="box">
          <div class="focus" id="focusTitle"></div>
          <div id="focusSummary"></div>
        </div>
        <div class="box formula" id="flowFormula"></div>
      </section>

      <section class="sec">
        <div class="sec-title">Quick Metrics</div>
        <div class="metrics">
          <div class="metric">
            <div class="metric-label">Example</div>
            <div class="metric-value" id="metricExample"></div>
          </div>
          <div class="metric">
            <div class="metric-label">State</div>
            <div class="metric-value" id="metricState"></div>
          </div>
          <div class="metric">
            <div class="metric-label">Recursion</div>
            <div class="metric-value" id="metricRecursion"></div>
          </div>
          <div class="metric">
            <div class="metric-label">DP</div>
            <div class="metric-value" id="metricDP"></div>
          </div>
        </div>
      </section>

      <section class="sec">
        <div class="sec-title">Recognition Signs</div>
        <div class="list" id="signList"></div>
      </section>

      <section class="sec">
        <div class="sec-title">When Recursion Helps</div>
        <div class="box" id="recursionHelps"></div>
        <div class="sec-title">When DP Takes Over</div>
        <div class="box" id="dpHelps"></div>
      </section>

      <section class="sec">
        <div class="sec-title">Starter Examples</div>
        <div class="chips" id="exampleChips"></div>
      </section>
    </aside>

    <main class="work">
      <div class="tabs">
        <button class="tab active" data-tab="overview">Overview</button>
        <button class="tab" data-tab="conversion">Conversion</button>
        <button class="tab" data-tab="examples">Examples</button>
        <button class="tab" data-tab="simulation">Simulation</button>
        <button class="tab" data-tab="pitfalls">Pitfalls</button>
      </div>

      <div class="controls">
        <div class="field"><span>Example</span><select id="exampleSelect"></select></div>
        <div class="field"><span>Scale</span>
          <div class="range"><input id="sizeRange" type="range" min="4" max="9" step="1" value="6" /><span class="value"
              id="sizeValue"></span></div>
        </div>
      </div>

      <section class="panel active" id="tab-overview">
        <div class="status" id="statusText"></div>
        <div class="grid">
          <article class="card focus-card">
            <div class="kick">The Bridge</div>
            <h2 id="heroTitle"></h2>
            <p class="sub" id="heroSummary"></p>
            <div class="chips" style="padding:0" id="heroBadges"></div>
            <div class="stage">
              <div class="mini">
                <div class="step">1. Recurrence</div>
                <h3>Write it recursively</h3>
                <p id="stageRecurrence"></p>
              </div>
              <div class="mini">
                <div class="step">2. Overlap</div>
                <h3>Spot repeated work</h3>
                <p id="stageOverlap"></p>
              </div>
              <div class="mini">
                <div class="step">3. Memoize</div>
                <h3>Cache each state</h3>
                <p id="stageMemo"></p>
              </div>
              <div class="mini">
                <div class="step">4. Tabulate</div>
                <h3>Fill in dependency order</h3>
                <p id="stageTable"></p>
              </div>
            </div>
          </article>

          <article class="card">
            <div class="kick">Why the Transition Works</div>
            <div class="info">
              <div class="cmp">
                <h3>Recursive structure</h3>
                <p id="recursiveStructure"></p>
              </div>
              <div class="cmp">
                <h3>Overlap signal</h3>
                <p id="overlapText"></p>
              </div>
              <div class="cmp">
                <h3>Memoization payoff</h3>
                <p id="memoText"></p>
              </div>
              <div class="cmp">
                <h3>Tabulation payoff</h3>
                <p id="tabText"></p>
              </div>
            </div>
          </article>

          <article class="card" style="grid-column:1/span 2">
            <div class="kick">Cost Shift Preview</div>
            <div class="bars" id="barList"></div>
            <p class="note" id="previewNote"></p>
          </article>
        </div>
      </section>

      <section class="panel" id="tab-conversion">
        <div class="card">
          <div class="kick">Conversion Workflow</div>
          <div class="examples">
            <div class="cmp">
              <div class="step">Step 1</div>
              <h3>Name the state</h3>
              <p id="workflowState"></p>
            </div>
            <div class="cmp">
              <div class="step">Step 2</div>
              <h3>Choose base cases</h3>
              <p id="workflowBase"></p>
            </div>
            <div class="cmp">
              <div class="step">Step 3</div>
              <h3>Write the recursive transition</h3>
              <p id="workflowTransition"></p>
            </div>
            <div class="cmp">
              <div class="step">Step 4</div>
              <h3>Pick memo or table order</h3>
              <p id="workflowOrder"></p>
            </div>
            <div class="cmp">
              <div class="step">Step 5</div>
              <h3>Count states and transitions</h3>
              <p id="workflowVerify"></p>
            </div>
          </div>
        </div>

        <div class="examples" style="margin-top:12px">
          <article class="card">
            <div class="kick">Recursive Sketch</div>
            <pre id="recursiveCode"></pre>
          </article>
          <article class="card">
            <div class="kick">Memoized Version</div>
            <pre id="memoCode"></pre>
          </article>
          <article class="card">
            <div class="kick">Tabulation Version</div>
            <pre id="tabCode"></pre>
          </article>
        </div>
      </section>

      <section class="panel" id="tab-examples">
        <div class="card">
          <div class="kick">Representative Problems</div>
          <div class="examples" id="exampleGrid"></div>
        </div>
        <div class="card" style="margin-top:12px">
          <div class="kick">What Actually Changes</div>
          <div class="compare">
            <div class="cmp">
              <h3>Recursion only</h3>
              <p>You keep the problem definition elegant, but the call tree may revisit the same state many times.</p>
            </div>
            <div class="cmp">
              <h3>Memoization</h3>
              <p>You preserve the recursive idea and add a cache so each state is solved at most once.</p>
            </div>
            <div class="cmp">
              <h3>Tabulation</h3>
              <p>You remove call-stack overhead and compute states in an order that already satisfies every dependency.
              </p>
            </div>
            <div class="cmp">
              <h3>Space compression</h3>
              <p>Once the table dependencies are clear, you can often keep only the previous row, column, or few states.
              </p>
            </div>
          </div>
        </div>
      </section>

      <section class="panel" id="tab-simulation">
        <div class="card">
          <div class="kick">Memoized Recursion Trace</div>
          <div class="sim-controls">
            <button class="sim-btn" id="tracePlayBtn">Play</button>
            <button class="sim-btn" id="traceStepBtn">Step</button>
            <button class="sim-btn" id="traceResetBtn">Reset</button>
            <span class="badge" id="traceProgress"></span>
          </div>
          <h2 id="traceTitle"></h2>
          <p class="sub" id="traceSummary"></p>
          <div class="compare">
            <div class="cmp">
              <h3>Current Event</h3>
              <p id="traceExplain"></p>
            </div>
            <div class="cmp">
              <h3>Memo Stats</h3>
              <p id="traceStats"></p>
            </div>
          </div>
          <div class="card" style="padding:14px">
            <div class="kick">Custom Trace Input</div>
            <div class="editor-grid">
              <label class="editor-field" id="traceFieldFibN">
                <strong>Fibonacci n</strong>
                <input id="traceCustomFibN" type="number" min="2" max="12" value="6" />
              </label>
              <label class="editor-field" id="traceFieldCoinAmount">
                <strong>Coin Amount</strong>
                <input id="traceCustomCoinAmount" type="number" min="1" max="20" value="6" />
              </label>
              <label class="editor-field" id="traceFieldCoinList">
                <strong>Coin List</strong>
                <input id="traceCustomCoinList" type="text" value="1,3,4" />
              </label>
              <label class="editor-field" id="traceFieldLcsA">
                <strong>LCS String A</strong>
                <input id="traceCustomLcsA" type="text" value="ABCB" />
              </label>
              <label class="editor-field" id="traceFieldLcsB">
                <strong>LCS String B</strong>
                <input id="traceCustomLcsB" type="text" value="BDCA" />
              </label>
            </div>
            <div class="sim-controls" style="margin-top:12px">
              <button class="sim-btn" id="traceApplyCustomBtn">Apply Input</button>
              <button class="sim-btn" id="traceResetCustomBtn">Reset Input</button>
              <span class="badge" id="traceInputMode"></span>
            </div>
            <p class="note" id="traceInputHelp"></p>
          </div>
          <div class="examples">
            <article class="card">
              <div class="kick">Call Stack</div>
              <div class="sim-stack" id="traceStack"></div>
            </article>
            <article class="card">
              <div class="kick">Memo Cache</div>
              <div class="sim-cache" id="traceCache"></div>
            </article>
            <article class="card">
              <div class="kick">Recent Events</div>
              <div class="sim-log" id="traceLog"></div>
            </article>
          </div>
          <p class="note">This trace uses a compact demo size so the recursive call flow stays visible while still
            showing overlap, cache hits, and stored answers.</p>
        </div>
      </section>

      <section class="panel" id="tab-pitfalls">
        <div class="card">
          <div class="kick">Common Mistakes</div>
          <div class="compare">
            <div class="cmp">
              <h3>Stopping at recursion</h3>
              <p>A correct recursive formula is only the start. It does not automatically guarantee an efficient
                implementation.</p>
            </div>
            <div class="cmp">
              <h3>Caching the wrong thing</h3>
              <p>If two different situations map to the same cache key, memoization will return the wrong answer with
                confidence.</p>
            </div>
            <div class="cmp">
              <h3>Missing base cases</h3>
              <p>Memoization and tabulation both depend on the smallest solvable cases being clearly defined.</p>
            </div>
            <div class="cmp">
              <h3>Wrong table order</h3>
              <p>Bottom-up DP fails when a state is filled before the states it depends on are ready.</p>
            </div>
            <div class="cmp">
              <h3>Ignoring recursion depth</h3>
              <p>Even when memoization is asymptotically fine, deep recursion can still be awkward or unsafe in
                practice.</p>
            </div>
            <div class="cmp">
              <h3>Overbuilding DP</h3>
              <p>If there is little overlap, full dynamic programming may add more complexity than value.</p>
            </div>
          </div>
        </div>

        <div class="card" style="margin-top:12px">
          <div class="kick">Quick Checks</div>
          <div class="compare">
            <div class="cmp">
              <h3>Can I name a state?</h3>
              <p>If you cannot describe the subproblem cleanly, the DP will stay fuzzy too.</p>
            </div>
            <div class="cmp">
              <h3>Do calls repeat?</h3>
              <p>If the same arguments appear in many recursive branches, memoization is likely worth trying.</p>
            </div>
            <div class="cmp">
              <h3>Is the order obvious?</h3>
              <p>If you can list dependencies clearly, tabulation becomes easier to trust and optimize.</p>
            </div>
            <div class="cmp">
              <h3>Can I compress memory?</h3>
              <p>Only compress after confirming which earlier states are truly needed by future transitions.</p>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>

  <script>
    const fmt = (value) => new Intl.NumberFormat("en-US", { notation: value >= 1000000 ? "compact" : "standard", maximumFractionDigits: value >= 1000 ? 1 : 0 }).format(value);

    function fibPreview(scale) {
      const n = scale * 4, calls = Math.round(Math.pow(1.62, n)), states = n + 1, writes = n + 1, depth = n;
      return { label: `n = ${n}`, summary: `For Fibonacci at ${n}, plain recursion grows a large call tree, while memoization and tabulation both reduce the work to about ${fmt(states)} meaningful states.`, bars: [{ label: "Recursive calls", value: calls, note: "Many branches recompute the same smaller Fibonacci values." }, { label: "Unique states", value: states, note: "The true state space is only one value per index." }, { label: "Table writes", value: writes, note: "Bottom-up DP fills each Fibonacci state once." }, { label: "Stack depth", value: depth, note: "The recursive chain can still be deep even after memoization." }] }
    }

    function coinPreview(scale) {
      const amount = scale * 6, calls = Math.round(Math.pow(1.46, amount)), states = amount + 1, writes = states * 4, depth = amount;
      return { label: `amount = ${amount}`, summary: `For minimum coin change on amount ${amount}, recursion branches over coin choices. Dynamic programming reuses the best answer for each remaining amount instead of rebuilding those branches.`, bars: [{ label: "Recursive branches", value: calls, note: "Each amount can try several coin choices and revisit the same remainder." }, { label: "Unique states", value: states, note: "One natural memo key is the remaining amount." }, { label: "Transition checks", value: writes, note: "Tabulation tests each coin against each amount." }, { label: "Stack depth", value: depth, note: "A recursive version may descend one coin at a time." }] }
    }

    function lcsPreview(scale) {
      const a = scale * 3, b = scale * 3 - 1, calls = Math.round(Math.pow(1.63, a + b)), states = (a + 1) * (b + 1), writes = states, depth = a + b;
      return { label: `|A| = ${a}, |B| = ${b}`, summary: `For LCS with prefix lengths ${a} and ${b}, recursive branching revisits the same prefix pairs. DP turns the problem into a fixed ${fmt(states)}-cell table.`, bars: [{ label: "Recursive branches", value: calls, note: "Skipping left or right characters creates heavily overlapping branches." }, { label: "Unique states", value: states, note: "Each prefix pair is a distinct state." }, { label: "Table cells", value: writes, note: "Tabulation computes one answer per prefix pair." }, { label: "Stack depth", value: depth, note: "A top-down recursive path can shrink one prefix at a time." }] }
    }

    const data = [
      { id: "fib", name: "Fibonacci", family: "1D recurrence", summary: "Fibonacci is the classic example where a clean recursive definition hides massive repeated work until caching is introduced.", state: "f(n)", recurrence: "f(n)=f(n-1)+f(n-2)", recursiveTime: "O(2^n)", dpTime: "O(n)", signs: ["A recursive definition appears immediately from the math.", "Calls with the same n reappear in multiple branches.", "The subproblem can be named with one small argument."], recursionHelps: "Recursion is the fastest way to express the relationship. It makes the structure of the problem obvious before optimization starts.", dpHelps: "Once you notice that f(7), f(6), and smaller values are recomputed repeatedly, dynamic programming removes that duplication.", recursiveStructure: "The answer at n depends only on answers at n-1 and n-2, so recursion captures the idea naturally.", overlapText: "The call tree for f(n) contains the same smaller Fibonacci calls again and again.", memoText: "Caching by n keeps the recursive style but guarantees each value is solved once.", tabText: "Tabulation turns the recursion tree into a straight left-to-right fill with optional O(1) space.", stageRecurrence: "State the formula directly: ask for smaller Fibonacci values until you hit n = 0 or n = 1.", stageOverlap: "The left and right branches both keep asking for the same smaller indices.", stageMemo: "Store each computed value in a cache indexed by n before returning it.", stageTable: "Start from 0 and 1, then build upward until the target index is reached.", workflowState: "Use the index n itself as the state because the subproblem is simply 'what is Fibonacci at n?'", workflowBase: "f(0)=0 and f(1)=1 are enough to anchor the recurrence.", workflowTransition: "Translate the math literally: f(n) uses f(n-1) and f(n-2).", workflowOrder: "For tabulation, fill from 2 upward because each new state depends on two earlier states.", workflowVerify: "There are n+1 states and constant work per state after caching, so the total becomes linear.", recursiveCode: "fib(n):\n  if n <= 1:\n    return n\n  return fib(n - 1) + fib(n - 2)", memoCode: "fib(n):\n  if n <= 1:\n    return n\n  if memo[n] exists:\n    return memo[n]\n  memo[n] = fib(n - 1) + fib(n - 2)\n  return memo[n]", tabCode: "fib(n):\n  if n <= 1:\n    return n\n  dp[0] = 0\n  dp[1] = 1\n  for i from 2 to n:\n    dp[i] = dp[i - 1] + dp[i - 2]\n  return dp[n]", preview: fibPreview },
      { id: "coin", name: "Minimum Coin Change", family: "Choice recursion", summary: "Minimum coin change starts as a branching recursive search and becomes efficient once each remaining amount is treated as a reusable state.", state: "best(amount)", recurrence: "1 + min(best(amount-c))", recursiveTime: "Exponential", dpTime: "O(amount * coins)", signs: ["The recursive step tries multiple choices from the same state.", "Different choice orders can lead back to the same remaining amount.", "The natural state is small enough to cache directly."], recursionHelps: "Recursion makes the decision process readable: for a given amount, try each coin and ask for the best smaller remainder.", dpHelps: "Dynamic programming matters because amounts like 11, 10, or 9 are reached from many different branches.", recursiveStructure: "Each call solves 'best answer for this remaining amount' by exploring smaller remaining amounts.", overlapText: "Different first-coin choices often converge to the same remaining amount later in the tree.", memoText: "Memoization stores the best answer for each amount once, which collapses a large search tree into a linear state space.", tabText: "Tabulation fills answers from amount 0 upward so every smaller remainder is already available when needed.", stageRecurrence: "Define the answer for an amount in terms of one coin choice plus a smaller amount.", stageOverlap: "Amounts recur across many branches because different paths can subtract to the same remainder.", stageMemo: "Cache the best answer for each amount after checking all coin options.", stageTable: "Compute dp[0], dp[1], dp[2], and so on until the target amount is filled.", workflowState: "Use the remaining amount as the state because it completely determines the future choices.", workflowBase: "best(0)=0, and invalid negative amounts should be treated as impossible.", workflowTransition: "Try each coin and take the minimum valid result after adding one coin to the smaller answer.", workflowOrder: "Bottom-up order is natural: iterate amounts from 1 to target, and inside each amount test all coins.", workflowVerify: "There are amount+1 states, and each state scans the available coin values.", recursiveCode: "minCoins(amount):\n  if amount == 0:\n    return 0\n  answer = INF\n  for coin in coins:\n    if amount - coin >= 0:\n      answer = min(answer, 1 + minCoins(amount - coin))\n  return answer", memoCode: "minCoins(amount):\n  if amount == 0:\n    return 0\n  if memo[amount] exists:\n    return memo[amount]\n  answer = INF\n  for coin in coins:\n    if amount - coin >= 0:\n      answer = min(answer, 1 + minCoins(amount - coin))\n  memo[amount] = answer\n  return answer", tabCode: "minCoins(target):\n  dp[0] = 0\n  for amount from 1 to target:\n    dp[amount] = INF\n    for coin in coins:\n      if amount >= coin:\n        dp[amount] = min(dp[amount], 1 + dp[amount - coin])\n  return dp[target]", preview: coinPreview },
      { id: "lcs", name: "Longest Common Subsequence", family: "2D prefix recursion", summary: "LCS is a strong recursion-to-DP example because the recursive cases are natural, but the same prefix pairs are revisited constantly.", state: "lcs(i, j)", recurrence: "match ? 1 + lcs(i-1,j-1) : max(...)", recursiveTime: "Exponential", dpTime: "O(mn)", signs: ["The subproblem is defined on two shrinking prefixes.", "Recursive branches skip characters and revisit the same prefix pairs.", "The state space can be tabulated as a grid."], recursionHelps: "Recursion mirrors the reasoning: compare the last characters of two prefixes and reduce the problem based on whether they match.", dpHelps: "Dynamic programming becomes essential because prefix pairs like (i, j) are reached from many skip-left and skip-right branches.", recursiveStructure: "The answer for two prefixes depends on slightly smaller prefix pairs, so recursion exposes the dependency graph clearly.", overlapText: "Calls such as lcs(i-1, j) and lcs(i, j-1) often both ask for lcs(i-1, j-1) and nearby states.", memoText: "Caching by the pair (i, j) preserves the recursive logic while eliminating repeated prefix work.", tabText: "Tabulation turns the recursive branching into a stable 2D table filled from smaller prefixes to larger prefixes.", stageRecurrence: "Ask how the answer changes when the current last characters match or do not match.", stageOverlap: "The same pairs of prefix lengths appear in many recursive branches.", stageMemo: "Use a 2D cache keyed by the two indices or prefix lengths.", stageTable: "Fill the grid row by row or column by column so top, left, and diagonal cells are ready.", workflowState: "Use two indices or prefix lengths because one string position alone is not enough.", workflowBase: "Any state with i=0 or j=0 is 0 because one prefix is empty.", workflowTransition: "Match extends the diagonal state; mismatch keeps the better of top and left.", workflowOrder: "A row-major or column-major table works because dependencies sit above, left, and diagonal.", workflowVerify: "There are (m+1)(n+1) states, and each one takes constant work once the neighbors exist.", recursiveCode: "lcs(i, j):\n  if i == 0 or j == 0:\n    return 0\n  if A[i - 1] == B[j - 1]:\n    return 1 + lcs(i - 1, j - 1)\n  return max(lcs(i - 1, j), lcs(i, j - 1))", memoCode: "lcs(i, j):\n  if i == 0 or j == 0:\n    return 0\n  if memo[i][j] exists:\n    return memo[i][j]\n  if A[i - 1] == B[j - 1]:\n    memo[i][j] = 1 + lcs(i - 1, j - 1)\n  else:\n    memo[i][j] = max(lcs(i - 1, j), lcs(i, j - 1))\n  return memo[i][j]", tabCode: "lcs(A, B):\n  create dp[0..m][0..n] = 0\n  for i from 1 to m:\n    for j from 1 to n:\n      if A[i - 1] == B[j - 1]:\n        dp[i][j] = 1 + dp[i - 1][j - 1]\n      else:\n        dp[i][j] = max(dp[i - 1][j], dp[i][j - 1])\n  return dp[m][n]", preview: lcsPreview }
    ];

    const tabs = [...document.querySelectorAll(".tab")], panels = [...document.querySelectorAll(".panel")], select = document.getElementById("exampleSelect"), range = document.getElementById("sizeRange");
    const tracePlayBtn = document.getElementById("tracePlayBtn"), traceStepBtn = document.getElementById("traceStepBtn"), traceResetBtn = document.getElementById("traceResetBtn");
    const traceApplyCustomBtn = document.getElementById("traceApplyCustomBtn"), traceResetCustomBtn = document.getElementById("traceResetCustomBtn");
    const traceCustomFibN = document.getElementById("traceCustomFibN"), traceCustomCoinAmount = document.getElementById("traceCustomCoinAmount"), traceCustomCoinList = document.getElementById("traceCustomCoinList"), traceCustomLcsA = document.getElementById("traceCustomLcsA"), traceCustomLcsB = document.getElementById("traceCustomLcsB");
    const current = () => data.find((item) => item.id === select.value) || data[0];
    let traceSimulation = { events: [], index: 0, timer: null, title: "", summary: "" };
    let traceCustomState = { exampleId: null, enabled: false, fibN: null, coinAmount: null, coinList: null, lcsA: null, lcsB: null };

    function stopTraceSimulation() {
      if (traceSimulation.timer) {
        clearInterval(traceSimulation.timer);
        traceSimulation.timer = null;
      }
      tracePlayBtn.textContent = "Play";
    }

    function pushTraceEvent(events, message, stack, memo, stats) {
      events.push({ message, stack: [...stack], memo: [...memo.entries()].sort((a, b) => String(a[0]).localeCompare(String(b[0]))), calls: stats.calls, hits: stats.hits, stores: stats.stores });
    }

    function buildFibTrace(scale, customN) {
      const n = customN ?? Math.min(7, Math.max(5, Number(scale) - 1)), memo = new Map(), stack = [], events = [], stats = { calls: 0, hits: 0, stores: 0 };
      function solve(k) {
        stats.calls += 1; stack.push(`f(${k})`); pushTraceEvent(events, `Call f(${k}).`, stack, memo, stats);
        if (k <= 1) { pushTraceEvent(events, `Base case returns ${k}.`, stack, memo, stats); stack.pop(); return k; }
        if (memo.has(k)) { stats.hits += 1; pushTraceEvent(events, `Cache hit: f(${k}) = ${memo.get(k)}.`, stack, memo, stats); stack.pop(); return memo.get(k); }
        const left = solve(k - 1), right = solve(k - 2), value = left + right;
        memo.set(k, value); stats.stores += 1; pushTraceEvent(events, `Store f(${k}) = ${value} after combining ${left} and ${right}.`, stack, memo, stats); stack.pop(); return value;
      }
      solve(n);
      return { title: `Memoized Fibonacci trace for n = ${n}`, summary: "The recursive definition stays intact, but repeated calls become cache hits once a state has been stored.", events };
    }

    function buildCoinTrace(scale, customAmount, customCoins) {
      const amount = customAmount ?? Math.min(8, Math.max(5, Number(scale))), coins = customCoins ?? [1, 3, 4], memo = new Map(), stack = [], events = [], stats = { calls: 0, hits: 0, stores: 0 };
      function solve(rem) {
        stats.calls += 1; stack.push(`best(${rem})`); pushTraceEvent(events, `Call best(${rem}).`, stack, memo, stats);
        if (rem === 0) { pushTraceEvent(events, "Reached amount 0, so the answer is 0 coins.", stack, memo, stats); stack.pop(); return 0; }
        if (memo.has(rem)) { stats.hits += 1; pushTraceEvent(events, `Cache hit for amount ${rem}: ${memo.get(rem)}.`, stack, memo, stats); stack.pop(); return memo.get(rem); }
        let best = Infinity, bestCoin = null;
        for (const coin of coins) {
          if (rem - coin >= 0) {
            const candidate = solve(rem - coin);
            if (candidate !== Infinity && candidate + 1 < best) {
              best = candidate + 1; bestCoin = coin;
              pushTraceEvent(events, `Coin ${coin} improves best(${rem}) to ${best}.`, stack, memo, stats);
            }
          }
        }
        memo.set(rem, best); stats.stores += 1; pushTraceEvent(events, `Store best(${rem}) = ${best}${bestCoin === null ? "" : " using coin " + bestCoin}.`, stack, memo, stats); stack.pop(); return best;
      }
      solve(amount);
      return { title: `Memoized coin-change trace for amount = ${amount}`, summary: "Multiple recursive branches reach the same remaining amount, so caching by amount collapses the search tree into reusable states.", events };
    }

    function buildLcsTrace(scale, customA, customB) {
      const A = (customA ?? "ABCBDAB".slice(0, Math.min(4, Math.max(3, Number(scale) - 4)))).slice(0, 8) || "A", B = (customB ?? "BDCABA".slice(0, Math.min(4, Math.max(3, Number(scale) - 5)))).slice(0, 8) || "B", memo = new Map(), stack = [], events = [], stats = { calls: 0, hits: 0, stores: 0 };
      function solve(i, j) {
        const key = `(${i},${j})`; stats.calls += 1; stack.push(`lcs${key}`); pushTraceEvent(events, `Call lcs${key}.`, stack, memo, stats);
        if (i === 0 || j === 0) { pushTraceEvent(events, `One prefix is empty at ${key}, so return 0.`, stack, memo, stats); stack.pop(); return 0; }
        if (memo.has(key)) { stats.hits += 1; pushTraceEvent(events, `Cache hit for ${key}: ${memo.get(key)}.`, stack, memo, stats); stack.pop(); return memo.get(key); }
        let value;
        if (A[i - 1] === B[j - 1]) {
          value = 1 + solve(i - 1, j - 1);
          pushTraceEvent(events, `Characters '${A[i - 1]}' and '${B[j - 1]}' match, so extend the diagonal state.`, stack, memo, stats);
        } else {
          value = Math.max(solve(i - 1, j), solve(i, j - 1));
          pushTraceEvent(events, `Mismatch at ${key}, so compare top and left recursive branches.`, stack, memo, stats);
        }
        memo.set(key, value); stats.stores += 1; pushTraceEvent(events, `Store ${key} = ${value}.`, stack, memo, stats); stack.pop(); return value;
      }
      solve(A.length, B.length);
      return { title: `Memoized LCS trace for "${A}" and "${B}"`, summary: "Recursive prefix comparison becomes efficient once each pair of prefix lengths is memoized after its first full evaluation.", events };
    }

    function tracePreset(example, scale) {
      if (example.id === "fib") return { fibN: Math.min(7, Math.max(5, Number(scale) - 1)) };
      if (example.id === "coin") return { coinAmount: Math.min(8, Math.max(5, Number(scale))), coinList: [1, 3, 4] };
      return { lcsA: "ABCBDAB".slice(0, Math.min(4, Math.max(3, Number(scale) - 4))), lcsB: "BDCABA".slice(0, Math.min(4, Math.max(3, Number(scale) - 5))) };
    }

    function usingTraceCustom(example) {
      return traceCustomState.enabled && traceCustomState.exampleId === example.id;
    }

    function fillTraceInputs(example, scale) {
      const preset = tracePreset(example, scale);
      if (example.id === "fib") {
        traceCustomFibN.value = String(preset.fibN);
      } else if (example.id === "coin") {
        traceCustomCoinAmount.value = String(preset.coinAmount);
        traceCustomCoinList.value = preset.coinList.join(",");
      } else {
        traceCustomLcsA.value = preset.lcsA;
        traceCustomLcsB.value = preset.lcsB;
      }
    }

    function syncTraceInputEditor(example, scale) {
      document.getElementById("traceFieldFibN").classList.toggle("hidden", example.id !== "fib");
      document.getElementById("traceFieldCoinAmount").classList.toggle("hidden", example.id !== "coin");
      document.getElementById("traceFieldCoinList").classList.toggle("hidden", example.id !== "coin");
      document.getElementById("traceFieldLcsA").classList.toggle("hidden", example.id !== "lcs");
      document.getElementById("traceFieldLcsB").classList.toggle("hidden", example.id !== "lcs");
      if (!usingTraceCustom(example)) fillTraceInputs(example, scale);
      document.getElementById("traceInputMode").textContent = usingTraceCustom(example) ? "Custom input active" : "Preset input active";
      document.getElementById("traceInputHelp").textContent = example.id === "fib" ? "Edit n to change the memoized recursion trace depth." : "";
      if (example.id === "coin") document.getElementById("traceInputHelp").textContent = "Enter the target amount and a comma-separated coin list to rebuild the recursive memoization trace.";
      if (example.id === "lcs") document.getElementById("traceInputHelp").textContent = "Edit the two strings to trace how recursive prefix calls turn into memoized states.";
    }

    function parseCoinList(text) {
      const values = text.split(",").map((part) => Number(part.trim())).filter((num) => Number.isFinite(num) && num > 0);
      if (!values.length) throw new Error("Enter at least one positive coin value.");
      return [...new Set(values)].sort((a, b) => a - b);
    }

    function buildTraceSimulation(example, scale) {
      if (example.id === "fib") return buildFibTrace(scale, usingTraceCustom(example) ? traceCustomState.fibN : null);
      if (example.id === "coin") return buildCoinTrace(scale, usingTraceCustom(example) ? traceCustomState.coinAmount : null, usingTraceCustom(example) ? traceCustomState.coinList : null);
      return buildLcsTrace(scale, usingTraceCustom(example) ? traceCustomState.lcsA : null, usingTraceCustom(example) ? traceCustomState.lcsB : null);
    }

    function renderTraceSimulation() {
      const event = traceSimulation.events[traceSimulation.index] || traceSimulation.events[0];
      if (!event) return;
      document.getElementById("traceTitle").textContent = traceSimulation.title;
      document.getElementById("traceSummary").textContent = traceSimulation.summary;
      document.getElementById("traceExplain").textContent = event.message;
      document.getElementById("traceStats").textContent = `Calls: ${event.calls}. Cache hits: ${event.hits}. Stored states: ${event.stores}.`;
      document.getElementById("traceProgress").textContent = `Step ${traceSimulation.index + 1} / ${traceSimulation.events.length}`;
      document.getElementById("traceStack").innerHTML = event.stack.length ? event.stack.map((frame, index) => `<div class="sim-frame ${index === event.stack.length - 1 ? "active" : ""}">${frame}</div>`).join("") : `<div class="sim-frame active">Call stack empty</div>`;
      document.getElementById("traceCache").innerHTML = event.memo.length ? event.memo.map(([key, value]) => `<div class="sim-cache-chip"><strong>${key}</strong><span>${value}</span></div>`).join("") : `<div class="sim-cache-chip"><strong>memo</strong><span>empty</span></div>`;
      const start = Math.max(0, traceSimulation.index - 5);
      document.getElementById("traceLog").innerHTML = traceSimulation.events.slice(start, traceSimulation.index + 1).map((item, index, arr) => `<div class="sim-log-line ${index === arr.length - 1 ? "active" : ""}">${item.message}</div>`).join("");
    }

    function resetTraceSimulation(example, scale) {
      stopTraceSimulation();
      traceSimulation = { ...buildTraceSimulation(example, scale), index: 0, timer: null };
      renderTraceSimulation();
    }

    function render() {
      const example = current(), preview = example.preview(Number(range.value)), maxBar = Math.max(...preview.bars.map((item) => item.value), 1);
      if (traceCustomState.exampleId !== example.id) {
        traceCustomState = { exampleId: example.id, enabled: false, fibN: null, coinAmount: null, coinList: null, lcsA: null, lcsB: null };
      }
      document.getElementById("sizeValue").textContent = preview.label;
      document.getElementById("focusTitle").textContent = `Recursion to DP through ${example.name}`;
      document.getElementById("focusSummary").textContent = example.summary;
      document.getElementById("flowFormula").textContent = `${example.state} -> repeated recursive states -> cache -> ordered table`;
      document.getElementById("metricExample").textContent = example.name;
      document.getElementById("metricState").textContent = example.state;
      document.getElementById("metricRecursion").textContent = example.recursiveTime;
      document.getElementById("metricDP").textContent = example.dpTime;
      document.getElementById("signList").innerHTML = example.signs.map((item) => `<div>${item}</div>`).join("");
      document.getElementById("recursionHelps").textContent = example.recursionHelps;
      document.getElementById("dpHelps").textContent = example.dpHelps;
      document.getElementById("exampleChips").innerHTML = data.map((item) => `<span class="chip">${item.name}</span>`).join("");
      document.getElementById("statusText").textContent = preview.summary;
      document.getElementById("heroTitle").textContent = `${example.name} shows why recursion is the entry point and dynamic programming is the optimization.`;
      document.getElementById("heroSummary").textContent = example.summary;
      document.getElementById("heroBadges").innerHTML = [example.family, example.recurrence, example.recursiveTime, example.dpTime].map((item) => `<span class="badge">${item}</span>`).join("");
      document.getElementById("stageRecurrence").textContent = example.stageRecurrence;
      document.getElementById("stageOverlap").textContent = example.stageOverlap;
      document.getElementById("stageMemo").textContent = example.stageMemo;
      document.getElementById("stageTable").textContent = example.stageTable;
      document.getElementById("recursiveStructure").textContent = example.recursiveStructure;
      document.getElementById("overlapText").textContent = example.overlapText;
      document.getElementById("memoText").textContent = example.memoText;
      document.getElementById("tabText").textContent = example.tabText;
      document.getElementById("barList").innerHTML = preview.bars.map((item) => `<div class="bar"><div><strong>${item.label}</strong><br /><span>${item.note}</span></div><div class="track"><div class="fill" style="width:${Math.max(5, Math.round(item.value / maxBar * 100))}%"></div></div><div class="barv">${fmt(item.value)}</div></div>`).join("");
      document.getElementById("previewNote").textContent = "The exact branch counts depend on implementation details, but the lesson is stable: recursion describes the problem, and DP bounds the repeated work by the real state space.";
      document.getElementById("workflowState").textContent = example.workflowState;
      document.getElementById("workflowBase").textContent = example.workflowBase;
      document.getElementById("workflowTransition").textContent = example.workflowTransition;
      document.getElementById("workflowOrder").textContent = example.workflowOrder;
      document.getElementById("workflowVerify").textContent = example.workflowVerify;
      document.getElementById("recursiveCode").textContent = example.recursiveCode;
      document.getElementById("memoCode").textContent = example.memoCode;
      document.getElementById("tabCode").textContent = example.tabCode;
      document.getElementById("exampleGrid").innerHTML = data.map((item) => `<div class="ex ${item.id === example.id ? "active" : ""}"><h3>${item.name}</h3><p>${item.summary}</p><div class="chips" style="padding:0"><span class="badge">${item.state}</span><span class="badge">${item.dpTime}</span></div></div>`).join("");
      syncTraceInputEditor(example, Number(range.value));
      resetTraceSimulation(example, Number(range.value));
    }

    tabs.forEach((button) => button.addEventListener("click", () => { const target = button.dataset.tab; if (target !== "simulation") stopTraceSimulation(); tabs.forEach((item) => item.classList.toggle("active", item === button)); panels.forEach((panel) => panel.classList.toggle("active", panel.id === `tab-${target}`)) }));
    select.innerHTML = data.map((item) => `<option value="${item.id}">${item.name}</option>`).join("");
    select.value = "fib";
    select.addEventListener("change", render);
    range.addEventListener("input", render);
    traceApplyCustomBtn.addEventListener("click", () => {
      const example = current();
      try {
        traceCustomState.exampleId = example.id;
        if (example.id === "fib") {
          const fibN = Math.max(2, Math.min(12, Number(traceCustomFibN.value) || 6));
          traceCustomState = { ...traceCustomState, enabled: true, fibN };
        } else if (example.id === "coin") {
          const coinAmount = Math.max(1, Math.min(20, Number(traceCustomCoinAmount.value) || 6));
          const coinList = parseCoinList(traceCustomCoinList.value);
          traceCustomState = { ...traceCustomState, enabled: true, coinAmount, coinList };
        } else {
          const lcsA = (traceCustomLcsA.value || "A").trim().slice(0, 8) || "A";
          const lcsB = (traceCustomLcsB.value || "B").trim().slice(0, 8) || "B";
          traceCustomState = { ...traceCustomState, enabled: true, lcsA, lcsB };
        }
        render();
      } catch (error) {
        alert(error.message);
      }
    });
    traceResetCustomBtn.addEventListener("click", () => {
      const example = current();
      traceCustomState = { exampleId: example.id, enabled: false, fibN: null, coinAmount: null, coinList: null, lcsA: null, lcsB: null };
      render();
    });
    tracePlayBtn.addEventListener("click", () => { if (traceSimulation.timer) { stopTraceSimulation(); return; } tracePlayBtn.textContent = "Pause"; traceSimulation.timer = setInterval(() => { if (traceSimulation.index >= traceSimulation.events.length - 1) { stopTraceSimulation(); return; } traceSimulation.index += 1; renderTraceSimulation(); }, 650); });
    traceStepBtn.addEventListener("click", () => { stopTraceSimulation(); if (traceSimulation.index < traceSimulation.events.length - 1) { traceSimulation.index += 1; renderTraceSimulation(); } });
    traceResetBtn.addEventListener("click", () => { stopTraceSimulation(); traceSimulation.index = 0; renderTraceSimulation(); });
    render();
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