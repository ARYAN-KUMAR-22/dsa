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
  <title>Dynamic Programming - Introduction</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet" />
  <style>
    :root{--bg:#08111a;--panel:#0d1724;--surface:#111f2f;--card:#18283a;--border:#294055;--text:#e7f3ff;--muted:#8ea5bb;--accent:#38bdf8;--accent2:#2dd4bf;--accent3:#facc15;--shadow:0 22px 48px rgba(0,0,0,.28)}

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
      --accent2: #0d9488;
      --accent3: #92400e;
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


    *{box-sizing:border-box;margin:0;padding:0}
    body{min-height:100vh;display:flex;flex-direction:column;background:radial-gradient(circle at top right,rgba(56,189,248,.1),transparent 26%),radial-gradient(circle at bottom left,rgba(45,212,191,.08),transparent 28%),var(--bg);color:var(--text);font-family:Inter,sans-serif}
    header{display:flex;align-items:center;gap:16px;padding:14px 24px;border-bottom:1px solid var(--border);background:linear-gradient(90deg,#07212e,#08111a)}
    header h1{font-size:1.2rem;font-weight:800} header h1 span{color:var(--accent)} header p{color:var(--muted);font-size:.8rem;line-height:1.6}
    .actions{margin-left:auto;display:flex;flex-wrap:wrap;gap:10px}.actions a{text-decoration:none;padding:8px 12px;border-radius:999px;border:1px solid var(--border);background:var(--surface);color:var(--muted);font-size:.78rem;transition:transform .18s ease,border-color .18s ease,background .18s ease,color .18s ease}.actions a:hover{transform:translateY(-1px);border-color:rgba(56,189,248,.36);color:var(--text)}.actions a.primary{background:linear-gradient(135deg,#0ea5e9,var(--accent2));border-color:transparent;color:#05202a;font-weight:800}
    .app{flex:1;display:flex;min-height:calc(100vh - 80px)} .left{width:330px;min-width:330px;background:var(--panel);border-right:1px solid var(--border)} .panel-head{padding:9px 12px 7px;border-bottom:1px solid var(--border);font-size:.7rem;letter-spacing:.08em;text-transform:uppercase;font-weight:800;color:var(--accent);display:flex;align-items:center;gap:8px}
    .pulse{width:8px;height:8px;border-radius:50%;background:var(--accent2);box-shadow:0 0 10px rgba(45,212,191,.7);animation:pulse 1.3s infinite;flex-shrink:0} @keyframes pulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(.84);opacity:.5}}
    .sec{border-bottom:1px solid var(--border)} .sec-title{padding:8px 12px 4px;color:var(--muted);font-size:.67rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase}
    .box{margin:0 12px 10px;padding:12px;border-radius:14px;border:1px solid var(--border);background: var(--card);color:var(--muted);font-size:.8rem;line-height:1.65} .focus{color:var(--text);font-size:.94rem;font-weight:800;line-height:1.5;margin-bottom:6px}.formula{color:#9be7db;font-family:"Fira Code",monospace}
    .metrics{padding:0 12px 12px;display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px}.metric{border:1px solid var(--border);border-radius:12px;background: var(--card);padding:10px;box-shadow:var(--shadow)} .metric-label{color:var(--muted);font-size:.64rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:5px} .metric-value{font-size:.8rem;line-height:1.45;font-family:"Fira Code",monospace}
    .list{padding:0 12px 12px;display:grid;gap:8px}.list div{position:relative;padding-left:14px;color:var(--muted);font-size:.8rem;line-height:1.58}.list div:before{content:"";position:absolute;left:0;top:.55rem;width:6px;height:6px;border-radius:50%;background:var(--accent)}
    .chips{padding:0 12px 12px;display:flex;flex-wrap:wrap;gap:8px}.chip,.badge{display:inline-flex;align-items:center;padding:6px 10px;border-radius:999px;font-size:.72rem;font-weight:800}.chip{color:#cbfbf1;background:rgba(45,212,191,.09);border:1px solid rgba(45,212,191,.22)}.badge{color:#d9f3ff;background:rgba(56,189,248,.09);border:1px solid rgba(56,189,248,.22)}
    .work{flex:1;min-width:0;display:flex;flex-direction:column}.tabs{display:flex;gap:2px;padding:8px 16px 0;border-bottom:1px solid var(--border);background: var(--surface);overflow-x:auto}.tabs::-webkit-scrollbar{height:5px}.tabs::-webkit-scrollbar-thumb{background:var(--border);border-radius:999px}
    .tab{padding:9px 18px;border:none;background:transparent;color:var(--muted);border-radius:8px 8px 0 0;cursor:pointer;font-size:.82rem;font-weight:700;border-bottom:2px solid transparent;transition:color .18s ease,background .18s ease;white-space:nowrap}.tab:hover{color:var(--text);background: var(--surface)}.tab.active{color:var(--accent);border-bottom-color:var(--accent);background: var(--card)}
    .controls{display:flex;flex-wrap:wrap;gap:8px;padding:12px 16px 10px;border-bottom:1px solid var(--border);align-items:center}.field{display:flex;align-items:center;gap:8px;padding:10px 12px;border:1px solid var(--border);border-radius:14px;background: var(--card)} .field span{color:var(--muted);font-size:.72rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase} .field select,.field input{border:none;background:transparent;color:var(--text);outline:none;font:.8rem "Fira Code",monospace}
    .range{display:flex;align-items:center;gap:10px;min-width:220px}.range input{width:100%;accent-color:var(--accent)} .value{color:var(--accent2);font:.8rem "Fira Code",monospace;letter-spacing:0;text-transform:none}
    .panel{display:none;padding:12px 16px 16px;overflow-y:auto}.panel.active{display:block}.status{padding:0 0 12px;color:var(--text);line-height:1.7;font-size:.84rem}
    .grid{display:grid;grid-template-columns:1.04fr .96fr;gap:12px;align-items:start}.card{display:grid;gap:14px;padding:18px;border-radius:20px;border:1px solid var(--border);background: var(--card);box-shadow:var(--shadow)}.focus-card{border-color:rgba(45,212,191,.3);box-shadow:0 0 0 1px rgba(45,212,191,.12),0 20px 42px rgba(0,0,0,.28)}
    .kick{color:var(--muted);font-size:.72rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.card h2{font-size:1.1rem;line-height:1.45}.sub{color:var(--muted);line-height:1.7;font-size:.9rem}
    .stage,.info,.examples,.compare,.pattern-grid{display:grid;gap:10px}.stage{grid-template-columns:repeat(4,minmax(0,1fr))}.info,.compare{grid-template-columns:repeat(2,minmax(0,1fr))}.examples,.pattern-grid{grid-template-columns:repeat(auto-fit,minmax(220px,1fr))}
    .mini,.cmp,.ex,.pattern{display:grid;gap:6px;padding:12px;border-radius:14px;border:1px solid rgba(148,163,184,.12);background: var(--surface)} .mini h3,.cmp h3,.ex h3,.pattern h3{font-size:.92rem} .mini p,.cmp p,.ex p,.pattern p,.cmp li{color:var(--muted);font-size:.82rem;line-height:1.62}.cmp ul{padding-left:18px}
    .step{color:#93e8dc;font-size:.66rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase} pre{padding:14px;border-radius:16px;border:1px solid var(--border);background: var(--card);color:var(--text);font:.82rem "Fira Code",monospace;line-height:1.72;white-space:pre-wrap;overflow-x:auto}
    .bars{display:grid;gap:10px}.bar{display:grid;grid-template-columns:148px minmax(0,1fr) auto;gap:10px;align-items:center}.bar strong{font-size:.78rem}.bar span{color:var(--muted);font-size:.72rem;line-height:1.5}.track{height:10px;border-radius:999px;overflow:hidden;border:1px solid rgba(148,163,184,.12);background:rgba(148,163,184,.08)}.fill{height:100%;border-radius:999px;background:linear-gradient(90deg,var(--accent),var(--accent2))}.barv{color:var(--accent2);font:.76rem "Fira Code",monospace}.ex.active{border-color:rgba(56,189,248,.34);box-shadow:0 0 0 1px rgba(56,189,248,.14)}.note{color:var(--muted);font-size:.82rem;line-height:1.7}
    .sim-controls{display:flex;flex-wrap:wrap;gap:10px;align-items:center}.sim-btn{padding:9px 14px;border-radius:12px;border:1px solid var(--border);background: var(--card);color:var(--text);font:600 .78rem "Inter",sans-serif;cursor:pointer}.sim-btn:hover{border-color:rgba(56,189,248,.34)}.sim-board{display:grid;gap:12px}.sim-array{display:grid;grid-template-columns:repeat(auto-fit,minmax(86px,1fr));gap:10px}.sim-cell{display:grid;gap:4px;padding:12px;border-radius:14px;border:1px solid rgba(148,163,184,.12);background: var(--surface);text-align:center}.sim-cell strong{font-size:.74rem;color:var(--muted)}.sim-cell span{font:700 1rem "Fira Code",monospace}.sim-cell.done{border-color:rgba(45,212,191,.22)}.sim-cell.current{border-color:rgba(56,189,248,.5);box-shadow:0 0 0 1px rgba(56,189,248,.18);background:rgba(56,189,248,.1)}.sim-table{overflow:auto;border:1px solid var(--border);border-radius:16px;background: var(--card)}.sim-table table{width:100%;border-collapse:collapse;font-size:.78rem;min-width:520px}.sim-table th,.sim-table td{padding:8px 10px;text-align:center;border-bottom:1px solid rgba(148,163,184,.12);border-right:1px solid rgba(148,163,184,.08)}.sim-table th{background:rgba(56,189,248,.08);color:#d9f3ff}.sim-table td.label,.sim-table th.label{text-align:left;background: var(--card)}.sim-table td.pending{color:rgba(142,165,187,.42)}.sim-table td.done{color:var(--text)}.sim-table td.current{background:rgba(56,189,248,.14);color:var(--text);font-weight:800}.sim-strings{display:flex;flex-wrap:wrap;gap:8px}.sim-strings span{padding:6px 10px;border-radius:999px;border:1px solid rgba(148,163,184,.16);background: var(--surface);font:.74rem "Fira Code",monospace;color:var(--muted)}.sim-strings span.active{border-color:rgba(56,189,248,.34);color:var(--text)}.mono{font-family:"Fira Code",monospace}.editor-grid{display:grid;gap:12px;grid-template-columns:repeat(auto-fit,minmax(220px,1fr))}.editor-field{display:grid;gap:6px}.editor-field strong{font-size:.74rem;letter-spacing:.08em;text-transform:uppercase;color:var(--muted)}.editor-field input,.editor-field textarea{width:100%;border:1px solid var(--border);border-radius:12px;background: var(--card);color:var(--text);padding:10px 12px;font:0.8rem "Fira Code",monospace;outline:none}.editor-field textarea{min-height:120px;resize:vertical}.hidden{display:none !important}
    code{padding:2px 6px;border-radius:8px;background:rgba(56,189,248,.08);color:var(--text);font-family:"Fira Code",monospace;font-size:.9em}
    @media (max-width:1120px){.app{flex-direction:column}.left{width:100%;min-width:0;border-right:none;border-bottom:1px solid var(--border)}.grid,.stage,.info,.compare{grid-template-columns:1fr}}
    @media (max-width:760px){header{padding:12px 16px;flex-wrap:wrap}.actions{margin-left:0}.metrics,.info,.compare{grid-template-columns:1fr}.bar{grid-template-columns:1fr}.range{min-width:0}}
  
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
      <h1><span>Dynamic Programming</span> - Introduction</h1>
      <p>Learn how states, transitions, memoization, and tabulation turn repeated work into reusable progress.</p>
    </div>
    <div class="actions">
      <a class="primary" href="../index.php">Home</a>
      <a href="recursion_and_dynamic_programming.php">Recursion + DP</a>
      <a href="zero_one_knapsack_problem.php">0/1 Knapsack</a>
      <a href="zero_one_knapsack_recursion.php">Knapsack Recursion</a>
      <a href="zero_one_knapsack_using_set_method.php">Knapsack Set Method</a>
      <a href="../Greedy%20Algorithm/greedy_method_introduction.php">Greedy Intro</a>
      <a href="../Divide%20and%20Conquer/divide_and_conquer_introduction.php">Divide and Conquer</a>
    </div>
  </header>

  <div class="app">
    <aside class="left">
      <div class="panel-head"><span class="pulse"></span>Dynamic Programming Snapshot</div>

      <section class="sec">
        <div class="sec-title">Core Idea</div>
        <div class="box"><div class="focus" id="focusTitle"></div><div id="focusSummary"></div></div>
        <div class="box formula" id="flowFormula"></div>
      </section>

      <section class="sec">
        <div class="sec-title">Quick Metrics</div>
        <div class="metrics">
          <div class="metric"><div class="metric-label">Example</div><div class="metric-value" id="metricExample"></div></div>
          <div class="metric"><div class="metric-label">State</div><div class="metric-value" id="metricState"></div></div>
          <div class="metric"><div class="metric-label">Time</div><div class="metric-value" id="metricTime"></div></div>
          <div class="metric"><div class="metric-label">Space</div><div class="metric-value" id="metricSpace"></div></div>
        </div>
      </section>

      <section class="sec">
        <div class="sec-title">Recognition Signs</div>
        <div class="list" id="signList"></div>
      </section>

      <section class="sec">
        <div class="sec-title">When It Helps</div>
        <div class="box" id="helpsText"></div>
        <div class="sec-title">Paradigm Contrast</div>
        <div class="box" id="compareText"></div>
      </section>

      <section class="sec">
        <div class="sec-title">Starter Examples</div>
        <div class="chips" id="exampleChips"></div>
      </section>
    </aside>

    <main class="work">
      <div class="tabs">
        <button class="tab active" data-tab="overview">Overview</button>
        <button class="tab" data-tab="workflow">Workflow</button>
        <button class="tab" data-tab="patterns">Patterns</button>
        <button class="tab" data-tab="simulation">Simulation</button>
        <button class="tab" data-tab="pitfalls">Pitfalls</button>
      </div>

      <div class="controls">
        <div class="field"><span>Example</span><select id="exampleSelect"></select></div>
        <div class="field"><span>Scale</span><div class="range"><input id="sizeRange" type="range" min="4" max="9" step="1" value="6" /><span class="value" id="sizeValue"></span></div></div>
      </div>

      <section class="panel active" id="tab-overview">
        <div class="status" id="statusText"></div>
        <div class="grid">
          <article class="card focus-card">
            <div class="kick">What Dynamic Programming Means</div>
            <h2 id="heroTitle"></h2>
            <p class="sub" id="heroSummary"></p>
            <div class="chips" style="padding:0" id="heroBadges"></div>
            <div class="stage">
              <div class="mini"><div class="step">1. State</div><h3>Define subproblems</h3><p id="stateText"></p></div>
              <div class="mini"><div class="step">2. Transition</div><h3>Reuse smaller answers</h3><p id="transitionText"></p></div>
              <div class="mini"><div class="step">3. Base</div><h3>Anchor the table</h3><p id="baseText"></p></div>
              <div class="mini"><div class="step">4. Order</div><h3>Compute safely</h3><p id="orderText"></p></div>
            </div>
          </article>

          <article class="card">
            <div class="kick">Why It Works</div>
            <div class="info">
              <div class="cmp"><h3>Optimal substructure</h3><p id="optimalText"></p></div>
              <div class="cmp"><h3>Overlapping subproblems</h3><p id="overlapText"></p></div>
              <div class="cmp"><h3>Memoization vs tabulation</h3><p id="memoText"></p></div>
              <div class="cmp"><h3>Practical payoff</h3><p id="payoffText"></p></div>
            </div>
          </article>

          <article class="card" style="grid-column:1/span 2">
            <div class="kick">Work Savings Preview</div>
            <div class="bars" id="barList"></div>
            <p class="note" id="previewNote"></p>
          </article>
        </div>
      </section>

      <section class="panel" id="tab-workflow">
        <div class="card">
          <div class="kick">Design Workflow</div>
          <div class="examples">
            <div class="cmp"><div class="step">Step 1</div><h3>Define the state</h3><p id="workflowState"></p></div>
            <div class="cmp"><div class="step">Step 2</div><h3>Write the transition</h3><p id="workflowTransition"></p></div>
            <div class="cmp"><div class="step">Step 3</div><h3>Set base cases</h3><p id="workflowBase"></p></div>
            <div class="cmp"><div class="step">Step 4</div><h3>Choose the order</h3><p id="workflowOrder"></p></div>
            <div class="cmp"><div class="step">Step 5</div><h3>Audit complexity</h3><p id="workflowVerify"></p></div>
          </div>
        </div>

        <div class="grid" style="margin-top:12px">
          <article class="card">
            <div class="kick">Starter Template</div>
            <pre id="templateCode"></pre>
          </article>
          <article class="card">
            <div class="kick">Top-Down vs Bottom-Up</div>
            <div class="compare">
              <div class="cmp"><h3>Memoization</h3><p>Write the recurrence naturally, recurse only when needed, and cache results so repeated states are solved once.</p></div>
              <div class="cmp"><h3>Tabulation</h3><p>List states in dependency order, fill the table iteratively, and often gain clearer control over memory use.</p></div>
              <div class="cmp"><h3>When memoization helps</h3><p>Useful when not every state is reached or when the recurrence is easier to explain recursively.</p></div>
              <div class="cmp"><h3>When tabulation helps</h3><p>Useful when the full table is needed, iteration order is obvious, or recursion depth would become awkward.</p></div>
            </div>
          </article>
        </div>
      </section>

      <section class="panel" id="tab-patterns">
        <div class="card"><div class="kick">Classic Examples</div><div class="examples" id="exampleGrid"></div></div>
        <div class="card" style="margin-top:12px">
          <div class="kick">Pattern Map</div>
          <div class="pattern-grid">
            <div class="pattern"><h3>1D DP</h3><p>Use one index as the state. Typical for Fibonacci, climbing stairs, rod cutting, and linear recurrences.</p></div>
            <div class="pattern"><h3>Knapsack Style</h3><p>Track items plus a resource such as capacity, budget, or time. Transitions usually branch into take and skip choices.</p></div>
            <div class="pattern"><h3>Grid DP</h3><p>Each cell depends on neighbors such as top, left, or diagonal. Common in path counting and matrix optimization.</p></div>
            <div class="pattern"><h3>String DP</h3><p>Use prefixes or suffixes as states. LCS, edit distance, and palindromic subsequence problems live here.</p></div>
            <div class="pattern"><h3>Interval DP</h3><p>Define the answer on a range like <code>[l, r]</code>. Useful when splitting an interval changes future choices.</p></div>
            <div class="pattern"><h3>State Compression</h3><p>Encode subsets or bitmasks when the state must remember a small set of visited choices.</p></div>
          </div>
        </div>
      </section>

      <section class="panel" id="tab-simulation">
        <div class="card">
          <div class="kick">Animated State Fill</div>
          <div class="sim-controls">
            <button class="sim-btn" id="simPlayBtn">Play</button>
            <button class="sim-btn" id="simStepBtn">Step</button>
            <button class="sim-btn" id="simResetBtn">Reset</button>
            <span class="badge" id="simProgress"></span>
          </div>
          <h2 id="simTitle"></h2>
          <p class="sub" id="simSummary"></p>
          <div class="compare">
            <div class="cmp"><h3>Current Step</h3><p id="simExplain"></p></div>
            <div class="cmp"><h3>State Snapshot</h3><p id="simStats"></p></div>
          </div>
          <div class="card" style="padding:14px">
            <div class="kick">Custom Simulation Input</div>
            <div class="editor-grid">
              <label class="editor-field" id="introFieldFibN">
                <strong>Fibonacci n</strong>
                <input id="introCustomFibN" type="number" min="2" max="20" value="8" />
              </label>
              <label class="editor-field" id="introFieldKnapsackItems">
                <strong>Knapsack Items</strong>
                <textarea id="introCustomKnapsackItems">A,2,3
B,3,4
C,4,5
D,5,8</textarea>
              </label>
              <label class="editor-field" id="introFieldKnapsackCapacity">
                <strong>Knapsack Capacity</strong>
                <input id="introCustomKnapsackCapacity" type="number" min="1" max="20" value="5" />
              </label>
              <label class="editor-field" id="introFieldLcsA">
                <strong>LCS String A</strong>
                <input id="introCustomLcsA" type="text" value="ABCBD" />
              </label>
              <label class="editor-field" id="introFieldLcsB">
                <strong>LCS String B</strong>
                <input id="introCustomLcsB" type="text" value="BDCAB" />
              </label>
            </div>
            <div class="sim-controls" style="margin-top:12px">
              <button class="sim-btn" id="introApplyCustomBtn">Apply Input</button>
              <button class="sim-btn" id="introResetCustomBtn">Reset Input</button>
              <span class="badge" id="introInputMode"></span>
            </div>
            <p class="note" id="introInputHelp"></p>
          </div>
          <div class="sim-board" id="simBoard"></div>
          <p class="note">The simulation uses a compact demo size so the state transitions stay readable while still matching the selected example.</p>
        </div>
      </section>

      <section class="panel" id="tab-pitfalls">
        <div class="card">
          <div class="kick">Common Mistakes</div>
          <div class="compare">
            <div class="cmp"><h3>Wrong state definition</h3><p>If the state misses information needed later, the recurrence will look simple but produce wrong answers.</p></div>
            <div class="cmp"><h3>Missing base cases</h3><p>A correct transition still fails if the smallest solvable cases are not initialized clearly.</p></div>
            <div class="cmp"><h3>Bad iteration order</h3><p>Tabulation only works when dependencies are ready before the current state is computed.</p></div>
            <div class="cmp"><h3>Ignoring memory</h3><p>Some tables are too large to store directly. Compression is possible only when future states use limited history.</p></div>
            <div class="cmp"><h3>Stopping at the value</h3><p>Many questions also want the actual path, set, or sequence. Store parent choices when reconstruction matters.</p></div>
            <div class="cmp"><h3>Using DP too early</h3><p>If a greedy rule is provably safe or a simple traversal solves the task, full DP may be unnecessary overhead.</p></div>
          </div>
        </div>

        <div class="card" style="margin-top:12px">
          <div class="kick">When Not To Use It</div>
          <div class="compare">
            <div class="cmp"><h3>Greedy beats it</h3><p>If a local choice can be proven globally safe, greedy usually stays simpler and faster.</p></div>
            <div class="cmp"><h3>Divide and conquer is enough</h3><p>If subproblems do not overlap, plain recursion or divide and conquer already avoids duplicate work.</p></div>
            <div class="cmp"><h3>Search space is tiny</h3><p>For very small inputs, brute force can be easier to write and verify than a full DP table.</p></div>
            <div class="cmp"><h3>State explosion wins</h3><p>If the natural state is too large, rethink the formulation before filling a huge and mostly useless table.</p></div>
          </div>
        </div>
      </section>
    </main>
  </div>

  <script>
    const fmt=(value)=>new Intl.NumberFormat("en-US",{notation:value>=1000000?"compact":"standard",maximumFractionDigits:value>=1000?1:0}).format(value);

    function fibPreview(scale){
      const n=scale*4,naive=Math.round(Math.pow(1.62,n)),states=n+1;
      return{label:`n = ${n}`,summary:`For Fibonacci at ${n}, naive recursion revisits the same indices again and again. Dynamic programming reduces the work to ${fmt(states)} stored states.`,bars:[{label:"Naive calls",value:naive,note:"Repeated recursion recomputes many smaller Fibonacci values."},{label:"Unique states",value:states,note:"Each index from 0 through n is solved once."},{label:"Transitions",value:Math.max(0,n-1),note:"Every new state reuses the previous two answers."}]}
    }

    function knapsackPreview(scale){
      const items=scale+10,capacity=scale*12,brute=Math.pow(2,items),states=(items+1)*(capacity+1);
      return{label:`${items} items, W = ${capacity}`,summary:`For ${items} items and capacity ${capacity}, brute force explores about ${fmt(brute)} subsets. A standard DP table tracks ${fmt(states)} item-capacity states instead.`,bars:[{label:"Subset choices",value:brute,note:"Brute force considers take or skip for every item."},{label:"DP states",value:states,note:"Each state is identified by item index and remaining capacity."},{label:"Transitions",value:items*capacity*2,note:"Each table cell compares skip and take when possible."}]}
    }

    function lcsPreview(scale){
      const a=scale*3,b=scale*3-2,naive=Math.round(Math.pow(1.68,a+b)),states=(a+1)*(b+1);
      return{label:`|A| = ${a}, |B| = ${b}`,summary:`For strings of lengths ${a} and ${b}, naive branching revisits the same prefix pairs many times. Tabulation turns the problem into ${fmt(states)} table cells.`,bars:[{label:"Naive branches",value:naive,note:"Matching and skipping branches overlap heavily."},{label:"DP cells",value:states,note:"Each pair of prefixes becomes one stored state."},{label:"Comparisons",value:a*b,note:"Each cell uses nearby neighbors and one character check."}]}
    }

    const data=[
      {id:"fib",name:"Fibonacci",family:"1D DP / memoization",summary:"Naive recursion repeats the same Fibonacci values many times. DP stores each result once and reuses it.",state:"dp[i]",stateExplain:"Let the state be the answer for one index i.",recurrence:"dp[i] = dp[i - 1] + dp[i - 2]",time:"O(n)",space:"O(n) or O(1)",signs:["The same smaller Fibonacci values appear in many recursive branches.","There is a clear dependency chain from smaller indices to larger indices.","The subproblem can be named with a single index."],helps:"This is the cleanest first DP example because overlap is visible immediately and the state definition is tiny.",compare:"Greedy has no safe local rule here, and divide and conquer alone still repeats work. DP wins by caching repeated states.",optimal:"The best answer for F(i) comes directly from optimal answers for smaller indices.",overlap:"Calls like F(5) and F(6) both need F(4), F(3), and smaller values.",memo:"Memoization follows the natural recursive definition, while tabulation fills the array from left to right.",payoff:"The runtime drops from exponential growth to linear work by solving each subproblem once.",transitionText:"Reuse the previous two answers to build the current answer.",baseText:"Seed dp[0] = 0 and dp[1] = 1 before filling later states.",orderText:"Increase i from 2 up to n so dependencies are always ready.",workflowTransition:"Write the recurrence exactly from the problem statement: current answer depends on the previous two answers.",workflowBase:"Initialize the first two Fibonacci values so later transitions have stable anchors.",workflowOrder:"A left-to-right pass works because dp[i] depends only on smaller indices.",workflowVerify:"Count one state per index and constant work per transition to justify O(n).",template:"fib(n):\n  if n <= 1:\n    return n\n  dp[0] = 0\n  dp[1] = 1\n  for i from 2 to n:\n    dp[i] = dp[i - 1] + dp[i - 2]\n  return dp[n]",preview:fibPreview},
      {id:"knapsack",name:"0/1 Knapsack",family:"Choice DP / resource constraint",summary:"For each item and capacity, decide whether taking the item improves the best achievable value.",state:"dp[i][w]",stateExplain:"Let the state mean the best value using the first i items with capacity w.",recurrence:"dp[i][w] = max(skip, take)",time:"O(nW)",space:"O(nW) or O(W)",signs:["Every item creates a structured take-or-skip decision.","The remaining capacity matters, so one index alone is not enough.","Brute force branches exponentially, but many item-capacity pairs repeat."],helps:"DP is ideal when choices interact with a limited resource and you must compare many partial solutions safely.",compare:"Greedy works for fractional knapsack, but 0/1 knapsack needs DP because the locally best ratio can block a better final combination.",optimal:"The best value for item i and capacity w is built from best values on smaller items and smaller capacity.",overlap:"Different decision paths reach the same pair of item index and remaining capacity.",memo:"Memoization mirrors the take-or-skip recursion. Tabulation makes the table and dependency order explicit.",payoff:"DP replaces an exponential subset search with a predictable table over items and capacity.",transitionText:"Compare skipping the item with taking it when weight allows.",baseText:"If no items remain or capacity is zero, the best value is zero.",orderText:"Fill by item count, and inside each row process capacities in the dependency-safe direction.",workflowTransition:"For each state, compute the better of not taking the item or taking it and adding its value.",workflowBase:"The zero-item row and zero-capacity column are both zero.",workflowOrder:"Use increasing item index. For 1D compression, iterate capacity backward to avoid reusing the same item twice.",workflowVerify:"The number of states is the number of item-capacity pairs, and each one uses constant-time comparison.",template:"knapsack(items, W):\n  create dp[0..n][0..W] = 0\n  for i from 1 to n:\n    for w from 0 to W:\n      dp[i][w] = dp[i - 1][w]\n      if items[i].weight <= w:\n        dp[i][w] = max(dp[i][w], items[i].value + dp[i - 1][w - items[i].weight])\n  return dp[n][W]",preview:knapsackPreview},
      {id:"lcs",name:"Longest Common Subsequence",family:"String DP / prefix table",summary:"Store the best subsequence length for every pair of prefixes instead of branching recursively over the same suffix pairs.",state:"dp[i][j]",stateExplain:"Let the state be the LCS length for the first i characters of A and the first j characters of B.",recurrence:"match ? 1 + dp[i - 1][j - 1] : max(top, left)",time:"O(mn)",space:"O(mn) or O(min(m, n))",signs:["The problem naturally compares prefixes or suffixes of two sequences.","Recursive branching revisits the same pair of positions many times.","Each state depends only on nearby states in a 2D table."],helps:"DP shines when the answer for two sequences can be described by prefix pairs and each pair is worth solving once.",compare:"Greedy character matching can miss future alignments, so DP is preferred because it keeps both structural possibilities alive.",optimal:"The best LCS for two prefixes is built from best LCS answers on smaller prefix pairs.",overlap:"Skipping a character from either string repeatedly reaches the same pair of prefix lengths.",memo:"Memoization is a good first derivation, but tabulation makes the 2D dependency graph easy to visualize.",payoff:"Instead of exponential branching over both strings, DP uses one table cell per prefix pair.",transitionText:"If characters match, extend the diagonal answer. Otherwise keep the better of top and left.",baseText:"Any state with an empty prefix has LCS length zero.",orderText:"Fill the table row by row or column by column because each cell uses top, left, and diagonal neighbors.",workflowTransition:"Use the character match rule to choose between diagonal extension and the best skip option.",workflowBase:"The first row and first column stay zero because one string prefix is empty there.",workflowOrder:"A standard row-major order works because all dependencies sit above or to the left.",workflowVerify:"There are (m + 1)(n + 1) states and constant work per state, so the table is O(mn).",template:"lcs(A, B):\n  let m = length(A), n = length(B)\n  create dp[0..m][0..n] = 0\n  for i from 1 to m:\n    for j from 1 to n:\n      if A[i - 1] == B[j - 1]:\n        dp[i][j] = 1 + dp[i - 1][j - 1]\n      else:\n        dp[i][j] = max(dp[i - 1][j], dp[i][j - 1])\n  return dp[m][n]",preview:lcsPreview}
    ];

    const tabs=[...document.querySelectorAll(".tab")],panels=[...document.querySelectorAll(".panel")],select=document.getElementById("exampleSelect"),range=document.getElementById("sizeRange");
    const simPlayBtn=document.getElementById("simPlayBtn"),simStepBtn=document.getElementById("simStepBtn"),simResetBtn=document.getElementById("simResetBtn");
    const introApplyCustomBtn=document.getElementById("introApplyCustomBtn"),introResetCustomBtn=document.getElementById("introResetCustomBtn");
    const introCustomFibN=document.getElementById("introCustomFibN"),introCustomKnapsackItems=document.getElementById("introCustomKnapsackItems"),introCustomKnapsackCapacity=document.getElementById("introCustomKnapsackCapacity"),introCustomLcsA=document.getElementById("introCustomLcsA"),introCustomLcsB=document.getElementById("introCustomLcsB");
    const current=()=>data.find((item)=>item.id===select.value)||data[0];
    let introSimulation={steps:[],index:0,timer:null,title:"",summary:""};
    let introCustomState={exampleId:null,enabled:false,fibN:null,items:null,capacity:null,lcsA:null,lcsB:null};

    const cloneTable=(table)=>table.map((row)=>row.slice());

    function stopIntroSimulation(){
      if(introSimulation.timer){
        clearInterval(introSimulation.timer);
        introSimulation.timer=null;
      }
      simPlayBtn.textContent="Play";
    }

    function buildFibSimulation(scale,customN){
      const n=customN??Math.min(10,Math.max(5,Number(scale)+1)),values=Array(n+1).fill(null),steps=[];
      steps.push({kind:"fib",values:values.slice(),current:-1,stats:`Prepared ${n+1} states from dp[0] to dp[${n}].`,explain:"Start with an empty 1D table. Dynamic programming will fill the array from the smallest states upward."});
      values[0]=0;steps.push({kind:"fib",values:values.slice(),current:0,stats:"Base case written: dp[0] = 0.",explain:"The first anchor is dp[0] = 0."});
      values[1]=1;steps.push({kind:"fib",values:values.slice(),current:1,stats:"Base case written: dp[1] = 1.",explain:"The second anchor is dp[1] = 1."});
      for(let i=2;i<=n;i++){
        values[i]=values[i-1]+values[i-2];
        steps.push({kind:"fib",values:values.slice(),current:i,stats:`Computed dp[${i}] = dp[${i-1}] + dp[${i-2}] = ${values[i]}.`,explain:`The current Fibonacci state reuses the two already-computed neighbors.`});
      }
      return{title:`Fibonacci simulation for n = ${n}`,summary:"A simple 1D DP table grows from left to right because every new state depends only on smaller indices.",steps};
    }

    function buildKnapsackSimulation(scale,customItems,customCapacity){
      const items=customItems??[{name:"A",weight:2,value:3},{name:"B",weight:3,value:4},{name:"C",weight:4,value:5},{name:"D",weight:5,value:8}],capacity=customCapacity??Math.min(8,Math.max(4,Number(scale))),dp=Array.from({length:items.length+1},()=>Array(capacity+1).fill(0)),steps=[];
      steps.push({kind:"knapsack",items,capacity,dp:cloneTable(dp),current:[0,0],stats:`Table size ${(items.length+1)} x ${capacity+1}.`,explain:"Row 0 and column 0 start at 0 because no items or no capacity means no value."});
      for(let i=1;i<=items.length;i++){
        const item=items[i-1];
        for(let w=0;w<=capacity;w++){
          const skip=dp[i-1][w];
          const take=item.weight<=w?item.value+dp[i-1][w-item.weight]:null;
          dp[i][w]=Math.max(skip,take??-Infinity);
          const action=take===null?`Item ${item.name} does not fit in capacity ${w}, so copy ${skip}.`:`Compare skip ${skip} with take ${take}; keep ${dp[i][w]}.`;
          steps.push({kind:"knapsack",items,capacity,dp:cloneTable(dp),current:[i,w],stats:`Now filling dp[${i}][${w}] for item ${item.name}.`,explain:action});
        }
      }
      return{title:`0/1 knapsack table fill for W = ${capacity}`,summary:"Each cell compares the previous row against a take transition that also reads from the previous row. That is why 0/1 knapsack does not reuse the same item twice.",steps};
    }

    function buildLcsSimulation(scale,customA,customB){
      const A=(customA??"ABCBDAB".slice(0,Math.min(5,Math.max(4,Number(scale)-1)))).slice(0,8)||"A",B=(customB??"BDCABA".slice(0,Math.min(5,Math.max(4,Number(scale)-2)))).slice(0,8)||"B",m=A.length,n=B.length,dp=Array.from({length:m+1},()=>Array(n+1).fill(0)),steps=[];
      steps.push({kind:"lcs",A,B,dp:cloneTable(dp),current:[0,0],stats:`Grid size ${(m+1)} x ${n+1}.`,explain:"The first row and first column are 0 because one prefix is empty there."});
      for(let i=1;i<=m;i++){
        for(let j=1;j<=n;j++){
          if(A[i-1]===B[j-1]){
            dp[i][j]=1+dp[i-1][j-1];
            steps.push({kind:"lcs",A,B,dp:cloneTable(dp),current:[i,j],stats:`Match at A[${i-1}] and B[${j-1}] -> '${A[i-1]}'.`,explain:`Matching characters extend the diagonal value, so dp[${i}][${j}] becomes ${dp[i][j]}.`});
          }else{
            dp[i][j]=Math.max(dp[i-1][j],dp[i][j-1]);
            steps.push({kind:"lcs",A,B,dp:cloneTable(dp),current:[i,j],stats:`Mismatch between '${A[i-1]}' and '${B[j-1]}'.`,explain:`Use the better of top ${dp[i-1][j]} and left ${dp[i][j-1]}, so dp[${i}][${j}] becomes ${dp[i][j]}.`});
          }
        }
      }
      return{title:`LCS grid fill for "${A}" and "${B}"`,summary:"This 2D DP grows by prefix pairs. Each cell depends on top, left, and sometimes diagonal neighbors.",steps};
    }

    function introPreset(example,scale){
      if(example.id==="fib") return{fibN:Math.min(10,Math.max(5,Number(scale)+1))};
      if(example.id==="knapsack") return{items:[{name:"A",weight:2,value:3},{name:"B",weight:3,value:4},{name:"C",weight:4,value:5},{name:"D",weight:5,value:8}],capacity:Math.min(8,Math.max(4,Number(scale)))};
      return{lcsA:"ABCBDAB".slice(0,Math.min(5,Math.max(4,Number(scale)-1))),lcsB:"BDCABA".slice(0,Math.min(5,Math.max(4,Number(scale)-2)))};
    }

    function introItemsToText(items){
      return items.map((item)=>`${item.name},${item.weight},${item.value}`).join("\n");
    }

    function parseIntroItems(text){
      const lines=text.split(/\r?\n/).map((line)=>line.trim()).filter(Boolean);
      if(!lines.length) throw new Error("Enter at least one item using Name,Weight,Value.");
      return lines.map((line,index)=>{
        const parts=line.split(",").map((part)=>part.trim());
        if(parts.length<3) throw new Error(`Line ${index+1} must use Name,Weight,Value.`);
        const weight=Number(parts[1]),value=Number(parts[2]);
        if(!Number.isFinite(weight)||weight<=0||!Number.isFinite(value)||value<0) throw new Error(`Line ${index+1} must have positive weight and non-negative value.`);
        return{name:parts[0]||`Item ${index+1}`,weight,value};
      });
    }

    function usingIntroCustom(example){
      return introCustomState.enabled&&introCustomState.exampleId===example.id;
    }

    function fillIntroCustomInputs(example,scale){
      const preset=introPreset(example,scale);
      if(example.id==="fib"){
        introCustomFibN.value=String(preset.fibN);
      }else if(example.id==="knapsack"){
        introCustomKnapsackItems.value=introItemsToText(preset.items);
        introCustomKnapsackCapacity.value=String(preset.capacity);
      }else{
        introCustomLcsA.value=preset.lcsA;
        introCustomLcsB.value=preset.lcsB;
      }
    }

    function syncIntroInputEditor(example,scale){
      document.getElementById("introFieldFibN").classList.toggle("hidden",example.id!=="fib");
      document.getElementById("introFieldKnapsackItems").classList.toggle("hidden",example.id!=="knapsack");
      document.getElementById("introFieldKnapsackCapacity").classList.toggle("hidden",example.id!=="knapsack");
      document.getElementById("introFieldLcsA").classList.toggle("hidden",example.id!=="lcs");
      document.getElementById("introFieldLcsB").classList.toggle("hidden",example.id!=="lcs");
      if(!usingIntroCustom(example)) fillIntroCustomInputs(example,scale);
      document.getElementById("introInputMode").textContent=usingIntroCustom(example)?"Custom input active":"Preset input active";
      document.getElementById("introInputHelp").textContent=example.id==="fib"?"Edit n to see a different 1D DP array fill.":"";
      if(example.id==="knapsack") document.getElementById("introInputHelp").textContent="Edit the item list as Name,Weight,Value and choose a capacity to rebuild the compact knapsack simulation.";
      if(example.id==="lcs") document.getElementById("introInputHelp").textContent="Edit the two strings to regenerate the LCS grid fill. Short strings keep the simulation readable.";
    }

    function buildIntroSimulation(example,scale){
      if(example.id==="fib") return buildFibSimulation(scale,usingIntroCustom(example)?introCustomState.fibN:null);
      if(example.id==="knapsack") return buildKnapsackSimulation(scale,usingIntroCustom(example)?introCustomState.items:null,usingIntroCustom(example)?introCustomState.capacity:null);
      return buildLcsSimulation(scale,usingIntroCustom(example)?introCustomState.lcsA:null,usingIntroCustom(example)?introCustomState.lcsB:null);
    }

    function renderIntroSimulation(){
      const step=introSimulation.steps[introSimulation.index]||introSimulation.steps[0];
      if(!step) return;
      document.getElementById("simTitle").textContent=introSimulation.title;
      document.getElementById("simSummary").textContent=introSimulation.summary;
      document.getElementById("simExplain").textContent=step.explain;
      document.getElementById("simStats").textContent=step.stats;
      document.getElementById("simProgress").textContent=`Step ${introSimulation.index+1} / ${introSimulation.steps.length}`;
      if(step.kind==="fib"){
        document.getElementById("simBoard").innerHTML=`<div class="sim-array">${step.values.map((value,index)=>`<div class="sim-cell ${value!==null?"done":""} ${index===step.current?"current":""}"><strong>dp[${index}]</strong><span>${value===null?"?":value}</span></div>`).join("")}</div>`;
        return;
      }
      if(step.kind==="knapsack"){
        const [ci,cw]=step.current;
        const header=`<tr><th class="label">Item / W</th>${Array.from({length:step.capacity+1},(_,w)=>`<th>${w}</th>`).join("")}</tr>`;
        const body=step.dp.map((row,i)=>{
          const label=i===0?"0 items":`${i}. ${step.items[i-1].name}`;
          const cells=row.map((value,w)=>{
            const pending=i>ci||(i===ci&&w>cw);
            const cls=[pending?"pending":"done",i===ci&&w===cw?"current":""].filter(Boolean).join(" ");
            return `<td class="${cls}">${pending?"?":value}</td>`;
          }).join("");
          return `<tr><td class="label">${label}</td>${cells}</tr>`;
        }).join("");
        document.getElementById("simBoard").innerHTML=`<div class="sim-table"><table><thead>${header}</thead><tbody>${body}</tbody></table></div>`;
        return;
      }
      const [ri,rj]=step.current;
      const header=`<tr><th class="label">A / B</th><th>-</th>${step.B.split("").map((char)=>`<th>${char}</th>`).join("")}</tr>`;
      const body=step.dp.map((row,i)=>{
        const rowLabel=i===0?"-":step.A[i-1];
        const cells=row.map((value,j)=>{
          const pending=i>ri||(i===ri&&j>rj);
          const cls=[pending?"pending":"done",i===ri&&j===rj?"current":""].filter(Boolean).join(" ");
          return `<td class="${cls}">${pending?"?":value}</td>`;
        }).join("");
        return `<tr><td class="label">${rowLabel}</td>${cells}</tr>`;
      }).join("");
      document.getElementById("simBoard").innerHTML=`<div class="sim-strings"><span class="${ri>0?"active":""}">A index ${ri}</span><span class="${rj>0?"active":""}">B index ${rj}</span><span class="mono">${step.A}</span><span class="mono">${step.B}</span></div><div class="sim-table"><table><thead>${header}</thead><tbody>${body}</tbody></table></div>`;
    }

    function resetIntroSimulation(example,scale){
      stopIntroSimulation();
      introSimulation={...buildIntroSimulation(example,scale),index:0,timer:null};
      renderIntroSimulation();
    }

    function render(){
      const example=current(),preview=example.preview(Number(range.value)),maxBar=Math.max(...preview.bars.map((item)=>item.value),1);
      if(introCustomState.exampleId!==example.id){
        introCustomState={exampleId:example.id,enabled:false,fibN:null,items:null,capacity:null,lcsA:null,lcsB:null};
      }
      document.getElementById("sizeValue").textContent=preview.label;
      document.getElementById("focusTitle").textContent=`Dynamic programming through ${example.name}`;
      document.getElementById("focusSummary").textContent=example.summary;
      document.getElementById("flowFormula").textContent=`${example.state} -> ${example.recurrence} -> store and reuse`;
      document.getElementById("metricExample").textContent=example.name;
      document.getElementById("metricState").textContent=example.state;
      document.getElementById("metricTime").textContent=example.time;
      document.getElementById("metricSpace").textContent=example.space;
      document.getElementById("signList").innerHTML=example.signs.map((item)=>`<div>${item}</div>`).join("");
      document.getElementById("helpsText").textContent=example.helps;
      document.getElementById("compareText").textContent=example.compare;
      document.getElementById("exampleChips").innerHTML=data.map((item)=>`<span class="chip">${item.name}</span>`).join("");
      document.getElementById("statusText").textContent=preview.summary;
      document.getElementById("heroTitle").textContent=`${example.name} is a clean model for dynamic programming thinking.`;
      document.getElementById("heroSummary").textContent=example.summary;
      document.getElementById("heroBadges").innerHTML=[example.family,example.time,example.space].map((item)=>`<span class="badge">${item}</span>`).join("");
      document.getElementById("stateText").textContent=example.stateExplain;
      document.getElementById("transitionText").textContent=example.transitionText;
      document.getElementById("baseText").textContent=example.baseText;
      document.getElementById("orderText").textContent=example.orderText;
      document.getElementById("optimalText").textContent=example.optimal;
      document.getElementById("overlapText").textContent=example.overlap;
      document.getElementById("memoText").textContent=example.memo;
      document.getElementById("payoffText").textContent=example.payoff;
      document.getElementById("barList").innerHTML=preview.bars.map((item)=>`<div class="bar"><div><strong>${item.label}</strong><br /><span>${item.note}</span></div><div class="track"><div class="fill" style="width:${Math.max(5,Math.round(item.value/maxBar*100))}%"></div></div><div class="barv">${fmt(item.value)}</div></div>`).join("");
      document.getElementById("previewNote").textContent="The exact counts depend on implementation details, but the pattern is the key idea: DP replaces repeated work with a bounded set of reusable states.";
      document.getElementById("workflowState").textContent=example.stateExplain;
      document.getElementById("workflowTransition").textContent=example.workflowTransition;
      document.getElementById("workflowBase").textContent=example.workflowBase;
      document.getElementById("workflowOrder").textContent=example.workflowOrder;
      document.getElementById("workflowVerify").textContent=example.workflowVerify;
      document.getElementById("templateCode").textContent=example.template;
      document.getElementById("exampleGrid").innerHTML=data.map((item)=>`<div class="ex ${item.id===example.id?"active":""}"><h3>${item.name}</h3><p>${item.summary}</p><div class="chips" style="padding:0"><span class="badge">${item.state}</span><span class="badge">${item.time}</span></div></div>`).join("");
      syncIntroInputEditor(example,Number(range.value));
      resetIntroSimulation(example,Number(range.value));
    }

    tabs.forEach((button)=>button.addEventListener("click",()=>{const target=button.dataset.tab;if(target!=="simulation") stopIntroSimulation();tabs.forEach((item)=>item.classList.toggle("active",item===button));panels.forEach((panel)=>panel.classList.toggle("active",panel.id===`tab-${target}`))}));
    select.innerHTML=data.map((item)=>`<option value="${item.id}">${item.name}</option>`).join("");
    select.value="fib";
    select.addEventListener("change",render);
    range.addEventListener("input",render);
    introApplyCustomBtn.addEventListener("click",()=>{
      const example=current();
      try{
        introCustomState.exampleId=example.id;
        if(example.id==="fib"){
          const fibN=Math.max(2,Math.min(20,Number(introCustomFibN.value)||8));
          introCustomState={...introCustomState,enabled:true,fibN};
        }else if(example.id==="knapsack"){
          const items=parseIntroItems(introCustomKnapsackItems.value);
          const capacity=Math.max(1,Math.min(20,Number(introCustomKnapsackCapacity.value)||5));
          introCustomState={...introCustomState,enabled:true,items,capacity};
        }else{
          const lcsA=(introCustomLcsA.value||"A").trim().slice(0,8)||"A";
          const lcsB=(introCustomLcsB.value||"B").trim().slice(0,8)||"B";
          introCustomState={...introCustomState,enabled:true,lcsA,lcsB};
        }
        render();
      }catch(error){
        alert(error.message);
      }
    });
    introResetCustomBtn.addEventListener("click",()=>{
      const example=current();
      introCustomState={exampleId:example.id,enabled:false,fibN:null,items:null,capacity:null,lcsA:null,lcsB:null};
      render();
    });
    simPlayBtn.addEventListener("click",()=>{if(introSimulation.timer){stopIntroSimulation();return;}simPlayBtn.textContent="Pause";introSimulation.timer=setInterval(()=>{if(introSimulation.index>=introSimulation.steps.length-1){stopIntroSimulation();return;}introSimulation.index+=1;renderIntroSimulation();},700);});
    simStepBtn.addEventListener("click",()=>{stopIntroSimulation();if(introSimulation.index<introSimulation.steps.length-1){introSimulation.index+=1;renderIntroSimulation();}});
    simResetBtn.addEventListener("click",()=>{stopIntroSimulation();introSimulation.index=0;renderIntroSimulation();});
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
