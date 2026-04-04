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
  <title>0/1 Knapsack Using Tabulation</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet" />
  <style>
    :root{--bg:#0b1020;--panel:#11192b;--surface:#18233a;--card:#1d2943;--border:#334760;--text:#edf5ff;--muted:#97abc6;--accent:#60a5fa;--accent2:#a78bfa;--accent3:#bfdbfe;--good:#22c55e;--warn:#f59e0b;--shadow:0 22px 48px rgba(0,0,0,.3)}

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
      --accent: #1d4ed8;
      --accent2: #6d28d9;
      --accent3: #1d4ed8;
      --good: #15803d;
      --warn: #b45309;
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
    body{min-height:100vh;display:flex;flex-direction:column;background:radial-gradient(circle at top right,rgba(96,165,250,.12),transparent 25%),radial-gradient(circle at bottom left,rgba(167,139,250,.1),transparent 28%),var(--bg);color:var(--text);font-family:Inter,sans-serif}
    header{display:flex;align-items:center;gap:16px;padding:14px 24px;border-bottom:1px solid var(--border);background:linear-gradient(90deg,#111b3a,#0b1020)}
    header h1{font-size:1.2rem;font-weight:800}
    header h1 span{color:var(--accent)}
    header p{color:var(--muted);font-size:.8rem;line-height:1.6}
    .actions{margin-left:auto;display:flex;flex-wrap:wrap;gap:10px}
    .actions a{text-decoration:none;padding:8px 12px;border-radius:999px;border:1px solid var(--border);background:var(--surface);color:var(--muted);font-size:.78rem;transition:transform .18s ease,border-color .18s ease,background .18s ease,color .18s ease}
    .actions a:hover{transform:translateY(-1px);border-color:rgba(96,165,250,.36);color:var(--text)}
    .actions a.primary{background:linear-gradient(135deg,#60a5fa,var(--accent2));border-color:transparent;color:#101625;font-weight:800}
    .app{flex:1;display:flex;min-height:calc(100vh - 80px)}
    .left{width:320px;min-width:320px;background:var(--panel);border-right:1px solid var(--border);overflow-y:auto}
    .panel-head{padding:9px 12px 7px;border-bottom:1px solid var(--border);font-size:.7rem;letter-spacing:.08em;text-transform:uppercase;font-weight:800;color:var(--accent);display:flex;align-items:center;gap:8px;position:sticky;top:0;background:var(--panel)}
    .pulse{width:8px;height:8px;border-radius:50%;background:var(--good);box-shadow:0 0 10px rgba(34,197,94,.7);animation:pulse 1.3s infinite;flex-shrink:0}
    @keyframes pulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(.84);opacity:.5}}
    .sec{border-bottom:1px solid var(--border)}
    .sec-title{padding:8px 12px 4px;color:var(--muted);font-size:.67rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase}
    .box{margin:0 12px 10px;padding:12px;border-radius:14px;border:1px solid var(--border);background: var(--card);color:var(--muted);font-size:.8rem;line-height:1.65}
    .focus{color:var(--text);font-size:.94rem;font-weight:800;line-height:1.5;margin-bottom:6px}
    .metrics{padding:0 12px 12px;display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px}
    .metric{border:1px solid var(--border);border-radius:12px;background: var(--card);padding:10px;box-shadow:var(--shadow)}
    .metric-label{color:var(--muted);font-size:.64rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:5px}
    .metric-value{font-size:.8rem;line-height:1.45;font-family:"Fira Code",monospace}
    .chips{padding:0 12px 12px;display:flex;flex-wrap:wrap;gap:8px}
    .chip,.badge,.pick{display:inline-flex;align-items:center;padding:6px 10px;border-radius:999px;font-size:.72rem;font-weight:800}
    .chip{color:var(--accent2);background:rgba(96,165,250,.1);border:1px solid rgba(96,165,250,.24)}
    .badge{color:var(--accent2);background:rgba(167,139,250,.1);border:1px solid rgba(167,139,250,.24)}
    .pick{color:var(--good);background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.22)}
    .work{flex:1;min-width:0;display:flex;flex-direction:column;overflow:hidden}
    .tabs{display:flex;gap:2px;padding:8px 16px 0;border-bottom:1px solid var(--border);background: var(--surface);overflow-x:auto}
    .tab{padding:9px 18px;border:none;background:transparent;color:var(--muted);border-radius:8px 8px 0 0;cursor:pointer;font-size:.82rem;font-weight:700;border-bottom:2px solid transparent;transition:color .18s ease,background .18s ease;white-space:nowrap}
    .tab:hover{color:var(--text);background: var(--surface)}
    .tab.active{color:var(--accent);border-bottom-color:var(--accent);background: var(--card)}
    .controls{display:flex;flex-wrap:wrap;gap:8px;padding:12px 16px 10px;border-bottom:1px solid var(--border);align-items:center;flex-shrink:0}
    .field{display:flex;align-items:center;gap:8px;padding:10px 12px;border:1px solid var(--border);border-radius:14px;background: var(--card)}
    .field span{color:var(--muted);font-size:.72rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase}
    .field select,.field input{border:none;background:transparent;color:var(--text);outline:none;font:.8rem "Fira Code",monospace}
    .range{display:flex;align-items:center;gap:10px;min-width:220px}
    .range input{width:100%;accent-color:var(--accent)}
    .value{color:var(--accent2);font:.8rem "Fira Code",monospace}
    .panel{display:none;padding:12px 16px 16px;overflow-y:auto;flex:1}
    .panel.active{display:flex;flex-direction:column}
    .sim-controls{display:flex;flex-wrap:wrap;gap:10px;align-items:center}
    .sim-btn{padding:9px 14px;border-radius:12px;border:1px solid var(--border);background: var(--card);color:var(--text);font:600 .78rem "Inter",sans-serif;cursor:pointer;transition:all .2s}
    .sim-btn:hover{border-color:rgba(96,165,250,.34);background: var(--card)}
    .sim-btn.primary{background:linear-gradient(135deg,#60a5fa,var(--accent2));border-color:transparent;color:#101625;font-weight:800}
    .editor-field{display:grid;gap:6px}
    .editor-field strong{font-size:.74rem;letter-spacing:.08em;text-transform:uppercase;color:var(--muted)}
    .editor-field input,.editor-field textarea{width:100%;border:1px solid var(--border);border-radius:12px;background: var(--card);color:var(--text);padding:10px 12px;font:0.8rem "Fira Code",monospace;outline:none;transition:border-color .2s}
    .editor-field input:focus,.editor-field textarea:focus{border-color:var(--accent)}
    .editor-field textarea{min-height:100px;resize:vertical}
    .canvas-container{flex:1;display:flex;flex-direction:column;overflow:auto;gap:12px;min-height:0}
    canvas{border:1px solid var(--border);border-radius:12px;background: var(--card);flex-shrink:0}
    .working-box{background: var(--card);border:1px solid var(--border);border-radius:12px;padding:12px;margin:10px 0}
    .working-title{color:var(--accent);font-weight:800;font-size:.85rem;margin-bottom:8px}
    .working-content{color:var(--text);font-family:"Fira Code",monospace;font-size:.8rem;line-height:1.6;white-space:pre-wrap;word-break:break-word}
    .cell-highlight{background:rgba(96,165,250,.2);border-left:3px solid var(--accent);padding-left:6px;margin:4px 0}
    .note{color:var(--muted);font-size:.82rem;line-height:1.7;margin:10px 0}
    .progress-bar{width:100%;height:6px;background:rgba(96,165,250,.1);border-radius:999px;overflow:hidden}
    .progress-fill{height:100%;background:linear-gradient(90deg,#60a5fa,#a78bfa);transition:width .3s}
    code{padding:2px 6px;border-radius:8px;background:rgba(96,165,250,.08);color:var(--accent3);font-family:"Fira Code",monospace;font-size:.9em}
    .info-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:10px;margin:10px 0}
    .info-card{background: var(--card);border:1px solid var(--border);border-radius:10px;padding:10px;text-align:center}
    .info-label{color:var(--muted);font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px}
    .info-value{color:var(--accent);font-size:1.2rem;font-weight:800;font-family:"Fira Code",monospace}
    @media (max-width:1120px){.app{flex-direction:column}.left{width:100%;min-width:0;border-right:none;border-bottom:1px solid var(--border)}}
    @media (max-width:760px){header{padding:12px 16px;flex-wrap:wrap}.actions{margin-left:0}}
  
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
      <h1><span>0/1 Knapsack</span> Using Tabulation</h1>
      <p>Watch the DP table fill step-by-step with detailed working. Customize items and see the algorithm in action!</p>
    </div>
    <div class="actions">
      <a class="primary" href="../index.php">Home</a>
      <a href="zero_one_knapsack_recursion.php">Recursion</a>
      <a href="zero_one_knapsack_memoization.php">Memoization</a>
    </div>
  </header>

  <div class="app">
    <aside class="left">
      <div class="panel-head"><span class="pulse"></span>Computation Info</div>
      <section class="sec">
        <div class="sec-title">Metrics</div>
        <div class="metrics">
          <div class="metric"><div class="metric-label">Best Value</div><div class="metric-value" id="metricBest">0</div></div>
          <div class="metric"><div class="metric-label">Items</div><div class="metric-value" id="metricItems">4</div></div>
          <div class="metric"><div class="metric-label">Capacity</div><div class="metric-value" id="metricCapacity">7</div></div>
          <div class="metric"><div class="metric-label">Cells</div><div class="metric-value" id="metricCells">40</div></div>
        </div>
      </section>
      <section class="sec">
        <div class="sec-title">Solution</div>
        <div class="box" style="margin-bottom:12px">
          <div style="color:var(--accent);font-weight:800;margin-bottom:6px">Chosen Items:</div>
          <div class="chips" id="chosenItems" style="padding:0;gap:6px"></div>
        </div>
        <div class="box">
          <div style="color:var(--accent);font-weight:800;margin-bottom:6px">Items Available:</div>
          <div class="chips" id="itemChips" style="padding:0;gap:6px"></div>
        </div>
      </section>
      <section class="sec" style="border:none">
        <div class="sec-title">Current Step</div>
        <div class="box" id="currentStep" style="min-height:60px">Select a scenario and click Step or Play to start</div>
      </section>
    </aside>

    <main class="work">
      <div class="tabs">
        <button class="tab active" data-tab="visualizer">📊 Visualizer</button>
        <button class="tab" data-tab="input">⚙️ Custom Input</button>
        <button class="tab" data-tab="info">📖 Learn</button>
      </div>

      <div class="controls">
        <div class="field"><span>Preset</span><select id="scenarioSelect"></select></div>
        <div class="sim-controls">
          <button class="sim-btn primary" id="playBtn">▶ Play</button>
          <button class="sim-btn" id="stepBtn">⏭ Step</button>
          <button class="sim-btn" id="resetBtn">⟲ Reset</button>
        </div>
        <span class="badge" id="progress">0/0</span>
      </div>

      <div class="panel active" id="tab-visualizer">
        <div class="canvas-container">
          <div>
            <canvas id="tableCanvas" width="1200" height="400"></canvas>
            <div class="note" id="tableNote">Select a preset or create custom input to visualize the DP table</div>
          </div>
          <div class="working-box" style="flex-shrink:0">
            <div class="working-title">Current Computation</div>
            <div class="working-content" id="workingDisplay">Ready to start...</div>
          </div>
        </div>
      </div>

      <div class="panel" id="tab-input">
        <div class="info-grid">
          <div class="info-card">
            <div class="info-label">Format</div>
            <div class="info-value">Name,W,V</div>
          </div>
          <div class="info-card">
            <div class="info-label">Example</div>
            <div class="info-value">A,2,3</div>
          </div>
          <div class="info-card">
            <div class="info-label">Max Items</div>
            <div class="info-value">8</div>
          </div>
        </div>
        <div class="editor-field">
          <strong>Knapsack Capacity</strong>
          <input id="customCapacity" type="number" min="1" max="30" value="7" />
        </div>
        <div class="editor-field" style="margin-top:12px">
          <strong>Items (Name,Weight,Value - one per line)</strong>
          <textarea id="customItems">A,1,1
B,3,4
C,4,5
D,5,7</textarea>
        </div>
        <div class="sim-controls" style="margin-top:12px;gap:8px">
          <button class="sim-btn primary" id="applyBtn">✓ Apply Custom Input</button>
          <button class="sim-btn" id="resetInputBtn">↻ Reset</button>
        </div>
        <div id="inputStatus" class="note" style="margin-top:12px;color:var(--good)"></div>
      </div>

      <div class="panel" id="tab-info">
        <div style="overflow-y:auto;padding-right:12px">
          <h3 style="color:var(--accent);margin:12px 0 8px">DP Table Algorithm</h3>
          <pre style="background: var(--card);border:1px solid var(--border);border-radius:12px;padding:12px;font-size:.75rem;margin:10px 0">dp[i][w] = max(skip, take)
  skip = dp[i-1][w]           // don't take item i
  take = items[i].value + dp[i-1][w - items[i].weight]  // take item i</pre>

          <h3 style="color:var(--accent);margin:20px 0 8px">How to Read the Table</h3>
          <div class="box" style="margin:10px 0">
            <strong>Rows:</strong> Item indices (0 to n). Row 0 = no items.<br/>
            <strong>Columns:</strong> Knapsack capacities (0 to W). Column 0 = no capacity.<br/>
            <strong>Cell value:</strong> Maximum value achievable using items up to row i with capacity of column w.
          </div>

          <h3 style="color:var(--accent);margin:20px 0 8px">Fill Order</h3>
          <div class="box" style="margin:10px 0">
            <strong>Row by row, left to right</strong><br/>
            For each item, for each capacity, compute the cell value.<br/>
            Base cases are automatically 0 (first row and first column).
          </div>

          <h3 style="color:var(--accent);margin:20px 0 8px">Reconstruction</h3>
          <div class="box" style="margin:10px 0">
            Start at dp[n][capacity].<br/>
            If value changed from previous row → item was taken.<br/>
            Move up and adjust capacity. Repeat until reaching top.
          </div>

          <h3 style="color:var(--accent);margin:20px 0 8px">Space Optimization</h3>
          <div class="box" style="margin:10px 0">
            <strong>Full table:</strong> O(n × capacity)<br/>
            <strong>Optimized:</strong> O(capacity) using 2 rows<br/>
            Trade: Slightly more complex code, significant memory savings for large n.
          </div>

          <h3 style="color:var(--accent);margin:20px 0 8px">Time Complexity</h3>
          <div class="box" style="margin:10px 0">
            <strong>O(n × capacity)</strong><br/>
            No recursion overhead. Each cell computed exactly once with O(1) work per cell.
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    const fmt = v => new Intl.NumberFormat("en-US").format(v);
    const esc = s => String(s).replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#39;");

    const scenarios = [
      { key: "classic", label: "Classic Bag", capacity: 7, items: [{n:"A",w:1,v:1},{n:"B",w:3,v:4},{n:"C",w:4,v:5},{n:"D",w:5,v:7}] },
      { key: "backpack", label: "Weekend Backpack", capacity: 5, items: [{n:"Map",w:1,v:15},{n:"Jacket",w:2,v:20},{n:"Camera",w:3,v:35},{n:"Snacks",w:2,v:18},{n:"Light",w:1,v:9}] },
      { key: "lab", label: "Student Lab Kit", capacity: 6, items: [{n:"Notes",w:1,v:12},{n:"Tablet",w:2,v:32},{n:"Book",w:3,v:40},{n:"Charger",w:2,v:26},{n:"Stand",w:4,v:58}] }
    ];

    const state = { items: [], capacity: 7, table: [], steps: [], stepIndex: 0, isPlaying: false, currentView: null };

    function solveWithSteps(items, capacity) {
      const n = items.length;
      const dp = Array(n+1).fill(0).map(() => Array(capacity+1).fill(0));
      const steps = [];

      for (let i = 1; i <= n; i++) {
        for (let w = 1; w <= capacity; w++) {
          const skip = dp[i-1][w];
          let take = -Infinity;
          let canTake = false;
          if (items[i-1].w <= w) {
            take = items[i-1].v + dp[i-1][w - items[i-1].w];
            canTake = true;
          }
          const result = Math.max(skip, take);
          dp[i][w] = result;
          steps.push({ i, w, skip, take, canTake, result, itemIdx: i-1, itemName: items[i-1].n });
        }
      }

      // Reconstruct
      const picks = [];
      let w = capacity;
      for (let i = n; i > 0; i--) {
        if (dp[i][w] !== dp[i-1][w]) {
          picks.push(items[i-1].n);
          w -= items[i-1].w;
        }
      }

      return { table: dp, steps, picks, bestValue: dp[n][capacity], n, capacity };
    }

    function drawTable(result, highlightI, highlightW) {
      const canvas = document.getElementById("tableCanvas");
      const ctx = canvas.getContext("2d");
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      const n = result.n;
      const cap = result.capacity;
      const cellW = Math.max(30, Math.min(50, (canvas.width - 80) / (cap + 1)));
      const cellH = Math.max(25, Math.min(40, (canvas.height - 60) / (n + 1)));
      const startX = 70;
      const startY = 40;

      // Draw headers
      ctx.fillStyle = "#97abc6";
      ctx.font = "700 11px 'Fira Code'";
      ctx.textAlign = "center";
      for (let w = 0; w <= cap; w++) {
        ctx.fillText(w, startX + w * cellW + cellW/2, startY - 15);
      }
      ctx.textAlign = "right";
      for (let i = 0; i <= n; i++) {
        ctx.fillText("i="+i, startX - 15, startY + i * cellH + cellH/2 + 4);
      }

      // Draw cells
      ctx.textAlign = "center";
      ctx.textBaseline = "middle";
      for (let i = 0; i <= n; i++) {
        for (let w = 0; w <= cap; w++) {
          const x = startX + w * cellW;
          const y = startY + i * cellH;
          const isHighlight = (i === highlightI && w === highlightW);
          const isFilled = (i > 0 && w > 0);

          ctx.fillStyle = isHighlight ? "rgba(245,158,11,0.4)" : isFilled ? "rgba(96,165,250,0.15)" : "rgba(51,71,96,0.3)";
          ctx.fillRect(x, y, cellW, cellH);
          
          ctx.strokeStyle = isHighlight ? "#f59e0b" : isFilled ? "rgba(96,165,250,0.4)" : "rgba(51,71,96,0.6)";
          ctx.lineWidth = isHighlight ? 3 : 1;
          ctx.strokeRect(x, y, cellW, cellH);

          ctx.fillStyle = isHighlight ? "#fef3c7" : isFilled ? "#bfdbfe" : "#8b98b6";
          ctx.font = "600 10px 'Fira Code'";
          ctx.fillText(String(result.table[i][w]), x + cellW/2, y + cellH/2);
        }
      }
    }

    function updateUI() {
      const step = state.steps[state.stepIndex] || null;
      document.getElementById("progress").textContent = (state.stepIndex + 1) + " / " + state.steps.length;
      document.getElementById("metricBest").textContent = state.currentView?.bestValue || 0;
      document.getElementById("metricItems").textContent = state.items.length;
      document.getElementById("metricCapacity").textContent = state.capacity;
      document.getElementById("metricCells").textContent = (state.items.length + 1) * (state.capacity + 1);

      const picksHtml = state.currentView?.picks.map(p => `<span class="pick">${esc(p)}</span>`).join("") || "<span class=\"badge\">None yet</span>";
      document.getElementById("chosenItems").innerHTML = picksHtml;

      const itemsHtml = state.items.map(itm => `<span class="chip">${esc(itm.n)}(${itm.w},${itm.v})</span>`).join("");
      document.getElementById("itemChips").innerHTML = itemsHtml;

      if (step) {
        const item = state.items[step.itemIdx];
        let working = `Item: ${esc(item.n)} (w=${item.w}, v=${item.v})\n`;
        working += `Capacity: ${step.w}\n\n`;
        working += `Skip: dp[${step.i-1}][${step.w}] = ${step.skip}\n`;
        if (step.canTake) {
          const newW = step.w - item.w;
          working += `Take: ${item.v} + dp[${step.i-1}][${newW}] = ${step.take}\n`;
          working += `\nResult: max(${step.skip}, ${step.take}) = ${step.result}`;
        } else {
          working += `Take: Item too heavy (${item.w} > ${step.w})\n`;
          working += `\nResult: ${step.result}`;
        }
        document.getElementById("workingDisplay").textContent = working;
        document.getElementById("currentStep").innerHTML = `<strong>Item ${step.itemIdx + 1}/${state.items.length}, Capacity ${step.w}/${state.capacity}</strong><br/>Computing dp[${step.i}][${step.w}] = ${step.result}`;
      }

      drawTable(state.currentView, step?.i || 0, step?.w || 0);
    }

    function reset() {
      state.stepIndex = 0;
      document.getElementById("playBtn").textContent = "▶ Play";
      state.isPlaying = false;
      if (state.currentView) updateUI();
    }

    function step() {
      if (!state.currentView || state.stepIndex >= state.steps.length) return;
      state.stepIndex++;
      updateUI();
    }

    function play() {
      if (!state.currentView) return;
      state.isPlaying = !state.isPlaying;
      document.getElementById("playBtn").textContent = state.isPlaying ? "⏸ Pause" : "▶ Play";
      
      if (state.isPlaying) {
        const interval = setInterval(() => {
          if (!state.isPlaying || state.stepIndex >= state.steps.length) {
            clearInterval(interval);
            state.isPlaying = false;
            document.getElementById("playBtn").textContent = "▶ Play";
            return;
          }
          step();
        }, 400);
      }
    }

    function loadScenario(key) {
      const scenario = scenarios.find(s => s.key === key);
      if (!scenario) return;
      state.items = scenario.items;
      state.capacity = scenario.capacity;
      state.currentView = solveWithSteps(state.items, state.capacity);
      state.steps = state.currentView.steps;
      reset();
      updateUI();
    }

    function applyCustom() {
      try {
        const capacity = parseInt(document.getElementById("customCapacity").value);
        if (!Number.isInteger(capacity) || capacity < 1 || capacity > 30) throw "Capacity must be 1-30";
        
        const lines = document.getElementById("customItems").value.trim().split(/\r?\n/).filter(Boolean);
        if (lines.length === 0) throw "Add at least one item";
        if (lines.length > 8) throw "Maximum 8 items";

        const items = lines.map((line, idx) => {
          const parts = line.split(",").map(p => p.trim());
          if (parts.length !== 3) throw `Line ${idx+1}: use Name,Weight,Value`;
          const [name, w, v] = [parts[0], parseInt(parts[1]), parseInt(parts[2])];
          if (!name) throw `Line ${idx+1}: need item name`;
          if (!Number.isInteger(w) || w < 1 || w > 30) throw `Line ${idx+1}: weight 1-30`;
          if (!Number.isInteger(v) || v < 1 || v > 9999) throw `Line ${idx+1}: value 1-9999`;
          return { n: name, w, v };
        });

        state.items = items;
        state.capacity = capacity;
        state.currentView = solveWithSteps(state.items, state.capacity);
        state.steps = state.currentView.steps;
        reset();
        updateUI();
        document.getElementById("scenarioSelect").value = "custom";
        document.getElementById("inputStatus").textContent = "✓ Custom input applied successfully!";
        setTimeout(() => { document.getElementById("inputStatus").textContent = ""; }, 3000);
      } catch (err) {
        document.getElementById("inputStatus").textContent = "✗ Error: " + err;
        document.getElementById("inputStatus").style.color = "var(--warn)";
        setTimeout(() => { document.getElementById("inputStatus").textContent = ""; }, 3000);
      }
    }

    const tabs = document.querySelectorAll(".tab");
    tabs.forEach(tab => {
      tab.addEventListener("click", () => {
        tabs.forEach(t => t.classList.remove("active"));
        document.querySelectorAll(".panel").forEach(p => p.classList.remove("active"));
        tab.classList.add("active");
        document.getElementById("tab-" + tab.dataset.tab).classList.add("active");
      });
    });

    document.getElementById("scenarioSelect").innerHTML = scenarios.map(s => `<option value="${s.key}">${esc(s.label)}</option>`).join("") + `<option value="custom">Custom Input</option>`;
    document.getElementById("scenarioSelect").addEventListener("change", e => loadScenario(e.target.value));
    document.getElementById("playBtn").addEventListener("click", play);
    document.getElementById("stepBtn").addEventListener("click", step);
    document.getElementById("resetBtn").addEventListener("click", reset);
    document.getElementById("applyBtn").addEventListener("click", applyCustom);
    document.getElementById("resetInputBtn").addEventListener("click", () => {
      document.getElementById("customCapacity").value = 7;
      document.getElementById("customItems").value = "A,1,1\nB,3,4\nC,4,5\nD,5,7";
    });

    loadScenario("classic");
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
