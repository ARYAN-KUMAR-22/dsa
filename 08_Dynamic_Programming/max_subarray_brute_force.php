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
  <title>Maximum Subarray Sum - Brute Force</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet" />
  <style>
    :root{--bg:#0b1020;--panel:#11192b;--surface:#18233a;--card:#1d2943;--border:#334760;--text:#edf5ff;--muted:#97abc6;--accent:#60a5fa;--accent2:#a78bfa;--accent3:#bfdbfe;--good:#22c55e;--warn:#f59e0b}

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
    header{display:flex;align-items:center;gap:14px;padding:10px 18px;border-bottom:1px solid var(--border);background:linear-gradient(90deg,#111b3a,#0b1020)}
    header h1{font-size:1.05rem;font-weight:800}
    header h1 span{color:var(--accent)}
    header p{color:var(--muted);font-size:.72rem;line-height:1.4}
    .actions{margin-left:auto;display:flex;gap:6px;flex-wrap:wrap}
    .actions a{text-decoration:none;padding:6px 10px;border-radius:999px;border:1px solid var(--border);background:var(--surface);color:var(--muted);font-size:.72rem;transition:all .18s}
    .actions a:hover{transform:translateY(-1px);border-color:rgba(96,165,250,.36);color:var(--text)}
    .actions a.primary{background:linear-gradient(135deg,#60a5fa,var(--accent2));border-color:transparent;color:#101625;font-weight:800}
    .app{flex:1;display:flex;min-height:calc(100vh - 78px)}
    .left{width:240px;min-width:240px;background:var(--panel);border-right:1px solid var(--border);overflow-y:auto}
    .panel-head{padding:6px 10px 4px;border-bottom:1px solid var(--border);font-size:.62rem;letter-spacing:.08em;text-transform:uppercase;font-weight:800;color:var(--accent);display:flex;align-items:center;gap:8px;position:sticky;top:0;background:var(--panel)}
    .pulse{width:8px;height:8px;border-radius:50%;background:var(--good);box-shadow:0 0 10px rgba(34,197,94,.7);animation:pulse 1.3s infinite}
    @keyframes pulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(.84);opacity:.5}}
    .sec{border-bottom:1px solid var(--border)}
    .sec-title{padding:5px 10px 3px;color:var(--muted);font-size:.6rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase}
    .box{margin:0 10px 8px;padding:10px;border-radius:10px;border:1px solid var(--border);background: var(--card);color:var(--muted);font-size:.73rem;line-height:1.5}
    .metrics{padding:0 10px 8px;display:grid;grid-template-columns:repeat(2,1fr);gap:6px}
    .metric{border:1px solid var(--border);border-radius:10px;background: var(--card);padding:8px}
    .metric-label{color:var(--muted);font-size:.58rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:3px}
    .metric-value{font-size:.72rem;line-height:1.3;font-family:"Fira Code",monospace;color:var(--accent)}
    .tabs{display:flex;gap:1px;padding:6px 12px 0;border-bottom:1px solid var(--border);background: var(--surface);overflow-x:auto}
    .tab{padding:7px 12px;border:none;background:transparent;color:var(--muted);border-radius:6px 6px 0 0;cursor:pointer;font-size:.76rem;font-weight:700;border-bottom:2px solid transparent;transition:all .18s;white-space:nowrap}
    .tab:hover{color:var(--text);background: var(--surface)}
    .tab.active{color:var(--accent);border-bottom-color:var(--accent);background: var(--surface)}
    .controls{display:flex;gap:8px;padding:8px 12px;border-bottom:1px solid var(--border);align-items:center;flex-shrink:0;flex-wrap:nowrap}
    .field{display:flex;align-items:center;gap:6px;padding:6px 10px;border:1px solid var(--border);border-radius:8px;background: var(--card);white-space:nowrap}
    .field span{color:var(--muted);font-size:.62rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;flex-shrink:0}
    .field select{border:none;background:transparent;color:var(--text);outline:none;font:.73rem "Fira Code",monospace;min-width:100px}
    .field input[type="range"]{accent-color:var(--accent);cursor:pointer;width:120px;margin:0}
    .sim-btn{padding:6px 11px;border-radius:10px;border:1px solid var(--border);background: var(--card);color:var(--text);font:600 .73rem "Inter",sans-serif;cursor:pointer;transition:all .2s}
    .sim-btn:hover{border-color:rgba(96,165,250,.34);background: var(--card)}
    .sim-btn.primary{background:linear-gradient(135deg,#60a5fa,var(--accent2));border-color:transparent;color:#101625;font-weight:800}
    .work{flex:1;min-width:0;display:flex;flex-direction:column;overflow:hidden}
    .viz-container{flex:1;display:grid;grid-template-columns:1fr 1.2fr;gap:10px;padding:10px 12px;overflow:hidden}
    .viz-box{border:1px solid var(--border);border-radius:10px;background: var(--surface);display:flex;flex-direction:column;overflow:hidden}
    .viz-head{padding:8px 12px;border-bottom:1px solid var(--border);font-size:.8rem;font-weight:700;color:var(--accent)}
    .viz-content{flex:1;overflow:auto;padding:10px;font-family:"Fira Code",monospace;font-size:.7rem;line-height:1.5}
    .code-line{padding:6px 8px;margin:2px 0;border-radius:6px;background: var(--surface);color:var(--text);cursor:pointer;transition:all .2s}
    .code-line.active{background:linear-gradient(90deg,rgba(96,165,250,.4),transparent);border-left:3px solid var(--accent);color:#60a5fa;font-weight:600}
    .step-explanation{padding:10px;margin:8px 0;border-radius:8px;background:rgba(96,165,250,.1);border-left:3px solid var(--accent);font-size:.7rem;color:var(--text);line-height:1.5}
    .array-viz{display:flex;gap:4px;margin:12px 0;flex-wrap:wrap}
    .arr-elem{width:48px;height:48px;display:flex;align-items:center;justify-content:center;border-radius:8px;border:2px solid var(--border);background: var(--card);font-size:.75rem;font-weight:700;color:var(--text);transition:all .15s}
    .arr-elem.active{background:linear-gradient(135deg,#f59e0b,#d97706);color:#0b1020;box-shadow:0 0 16px rgba(245,158,11,.8);border-color:#f59e0b;transform:scale(1.08)}
    .arr-elem.in-range{background:linear-gradient(135deg,#60a5fa,#3b82f6);color:var(--text);box-shadow:0 0 12px rgba(96,165,250,.6);border-color:#60a5fa}
    .arr-elem.max{background:linear-gradient(135deg,#22c55e,#16a34a);color:#0b1020;box-shadow:0 0 14px rgba(34,197,94,.6);border-color:#22c55e}
    .arr-elem.negative{background:rgba(239,68,68,.25);border-color:rgba(239,68,68,.5)}
    .stat-box{margin:8px 0;padding:10px;border-radius:8px;border-left:3px solid var(--accent);background:rgba(96,165,250,.1)}
    .stat-label{font-size:.65rem;color:var(--accent);font-weight:800;margin-bottom:4px}
    .stat-value{font-size:.7rem;color:var(--text)}
    .panel{display:none;padding:10px 12px;overflow-y:auto;flex:1}
    .panel.active{display:flex;flex-direction:column}
    .note{color:var(--muted);font-size:.8rem;line-height:1.6;margin:8px 0}
    .editor-field{display:grid;gap:6px}
    .editor-field strong{font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;color:var(--muted)}
    .editor-field input{width:100%;border:1px solid var(--border);border-radius:10px;background: var(--card);color:var(--text);padding:8px 10px;font:.75rem "Fira Code",monospace;outline:none;transition:border-color .2s}
    .editor-field input:focus{border-color:var(--accent)}
  
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
      <h1><span>Maximum Subarray</span> Sum - Brute Force</h1>
      <p>Check all subarrays - O(n²) approach to find maximum sum</p>
    </div>
    <div class="actions">
      <a class="primary" href="../index.php">Home</a>
      <a href="max_subarray_sum.php">Kadane's (O(n))</a>
      <a href="max_subarray_dp.php">DP Approach</a>
    </div>
  </header>

  <div class="app">
    <aside class="left">
      <div class="panel-head"><span class="pulse"></span>Metrics</div>
      <section class="sec">
        <div class="sec-title">Stats</div>
        <div class="metrics">
          <div class="metric"><div class="metric-label">Checks</div><div class="metric-value" id="metricChecks">0</div></div>
          <div class="metric"><div class="metric-label">Max Sum</div><div class="metric-value" id="metricMax">0</div></div>
          <div class="metric"><div class="metric-label">Operations</div><div class="metric-value" id="metricOps">0%</div></div>
          <div class="metric"><div class="metric-label">Time</div><div class="metric-value" id="metricTime">0ms</div></div>
        </div>
      </section>
      <section class="sec">
        <div class="sec-title">Complexity</div>
        <div class="box" style="font-size:.75rem;font-weight:700;color:var(--warn)">O(n²) Time<br/>O(1) Space</div>
      </section>
      <section class="sec" style="border:none">
        <div class="sec-title">Problem</div>
        <div class="box" id="problemBox">Ready</div>
      </section>
    </aside>

    <main class="work">
      <div class="tabs">
        <button class="tab active" data-tab="visualizer">🔍 Brute Force Execution</button>
        <button class="tab" data-tab="input">⚙️ Input</button>
        <button class="tab" data-tab="learn">📖 Learn</button>
      </div>

      <div class="controls">
        <div class="field"><span>Example</span><select id="presetSelect"></select></div>
        <div class="field"><span>Speed</span><input type="range" id="speedSlider" min="0.1" max="2" step="0.1" value="1" /><span id="speedLabel" style="min-width:38px;text-align:center;color:var(--accent);font-size:.68rem;font-weight:700">1.0x</span></div>
        <div style="margin-left:auto"></div>
        <button class="sim-btn primary" id="runBtn">▶ Run</button>
        <button class="sim-btn" id="resetBtn">⟲ Reset</button>
      </div>

      <div class="panel active" id="tab-visualizer">
        <div class="viz-container">
          <div class="viz-box">
            <div class="viz-head">📝 Nested Loops</div>
            <div class="viz-content" id="codeArea"></div>
          </div>
          <div class="viz-box">
            <div class="viz-head">🔎 Checking Subarrays</div>
            <div class="viz-content" style="display:flex;flex-direction:column;gap:10px">
              <div id="statsDisplay"></div>
              <div id="arrayDisplay" style="flex:1;overflow:auto"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="tab-input">
        <div class="note" style="color:var(--accent3)">
          <strong>Format:</strong> Space-separated integers<br/>
          Example: -2 1 -3 4 -1 2
        </div>
        <div class="editor-field">
          <strong>Array Input</strong>
          <input id="customArray" type="text" value="-2 1 -3 4 -1 2" placeholder="Enter numbers" />
        </div>
        <div style="display:flex;gap:8px;margin-top:10px">
          <button class="sim-btn primary" id="applyBtn">✓ Apply</button>
          <button class="sim-btn" id="resetInputBtn">↻ Reset</button>
        </div>
        <div id="inputStatus" class="note"></div>
      </div>

      <div class="panel" id="tab-learn">
        <div style="overflow-y:auto;padding-right:10px">
          <h3 style="color:var(--accent);margin:10px 0 6px;font-size:.95rem">Brute Force Approach</h3>
          <div class="box">
            Try every possible subarray. For each starting position `i`, compute sum of all subarrays starting at `i`. Very straightforward but slow for large arrays.
          </div>

          <h3 style="color:var(--accent);margin:14px 0 6px;font-size:.95rem">Algorithm</h3>
          <div class="box">
            <strong>Nested Loops:</strong><br/>
            • Outer loop: start position (i)<br/>
            • Inner loop: end position (j)<br/>
            • For each [i...j]: sum and compare<br/>
            • Total checks: n + (n-1) + (n-2) + ... = n(n+1)/2 ≈ O(n²)
          </div>

          <h3 style="color:var(--accent);margin:14px 0 6px;font-size:.95rem">Why It's Slow</h3>
          <div class="box">
            <strong>6 elements:</strong> 21 checks<br/>
            <strong>10 elements:</strong> 55 checks<br/>
            <strong>100 elements:</strong> 5,050 checks<br/>
            <strong>1,000 elements:</strong> 500,500 checks! 😱
          </div>

          <h3 style="color:var(--accent);margin:14px 0 6px;font-size:.95rem">vs Kadane's Algorithm</h3>
          <div class="box">
            <strong>Brute Force:</strong> O(n²) — all subarray pairs<br/>
            <strong>Kadane's:</strong> O(n) — single pass<br/>
            <strong>Speedup on n=1000:</strong> 200x faster!
          </div>

          <h3 style="color:var(--accent);margin:14px 0 6px;font-size:.95rem">Visual Guide</h3>
          <div class="box">
            🟨 Orange: Current element<br/>
            🟦 Blue: Current subarray [i...j]<br/>
            🟩 Green: Best subarray found so far
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    const fmt = v => new Intl.NumberFormat("en-US").format(v);

    const state = {
      arr: [-2, 1, -3, 4, -1, 2],
      i: 0, j: 0, checks: 0,
      maxSum: 0, maxStart: 0, maxEnd: 0,
      running: false, currentLine: 0, startTime: 0, speed: 1
    };

    const codeLines = [
      "function maxSubarrayBruteForce(arr):",
      "  maxSum = arr[0]",
      "  for i in range(0, n):",
      "    currentSum = 0",
      "    for j in range(i, n):",
      "      currentSum += arr[j]",
      "      if currentSum > maxSum:",
      "        maxSum = currentSum",
      "  return maxSum"
    ];

    function sleep(ms) {
      return new Promise(resolve => setTimeout(resolve, ms / state.speed));
    }

    async function runBruteForce() {
      state.running = true;
      state.checks = 0;
      state.maxSum = state.arr[0];
      state.maxStart = 0;
      state.maxEnd = 0;
      state.startTime = performance.now();

      state.currentLine = 1;
      updateDisplay();
      await sleep(100);

      state.currentLine = 2;
      updateDisplay();
      await sleep(80);

      const n = state.arr.length;

      for (let i = 0; i < n; i++) {
        state.i = i;
        state.currentLine = 3;
        updateDisplay();
        await sleep(60);

        state.currentLine = 4;
        let currentSum = 0;
        updateDisplay();
        await sleep(60);

        state.currentLine = 5;
        updateDisplay();
        await sleep(40);

        for (let j = i; j < n; j++) {
          state.j = j;
          state.currentLine = 6;
          currentSum += state.arr[j];
          state.checks++;
          updateDisplay();
          await sleep(80);

          state.currentLine = 7;
          updateDisplay();
          await sleep(40);

          if (currentSum > state.maxSum) {
            state.currentLine = 8;
            state.maxSum = currentSum;
            state.maxStart = i;
            state.maxEnd = j;
            updateDisplay();
            await sleep(100);
          }
        }
      }

      state.running = false;
      const elapsed = Math.round(performance.now() - state.startTime);

      document.getElementById("metricChecks").textContent = fmt(state.checks);
      document.getElementById("metricMax").textContent = state.maxSum;
      document.getElementById("metricOps").textContent = Math.round(state.checks / (state.arr.length * state.arr.length) * 100) + "%";
      document.getElementById("metricTime").textContent = elapsed + "ms";
      document.getElementById("problemBox").textContent = `[${state.arr.join(', ')}]`;
      updateDisplay();
    }

    function updateDisplay() {
      let codeHtml = '';
      codeLines.forEach((line, idx) => {
        const lineNum = idx + 1;
        const isActive = state.currentLine === lineNum;
        codeHtml += `<div class="code-line ${isActive ? 'active' : ''}">${lineNum}. ${line}</div>`;
      });
      document.getElementById("codeArea").innerHTML = codeHtml;

      let statsHtml = `<div class="step-explanation">
        <strong>i=${state.i}, j=${state.j}:</strong> Subarray [${state.i}...${state.j}]<br/>
        Total Checks: ${state.checks} | Max: ${state.maxSum}<br/>
        ${state.maxStart <= state.maxEnd ? `<span style="color:var(--good)">✓ Best: [${state.maxStart}...${state.maxEnd}]</span>` : 'Computing...'}
      </div>`;
      document.getElementById("statsDisplay").innerHTML = statsHtml;

      renderArray();
    }

    function renderArray() {
      let html = '<div class="array-viz">';
      state.arr.forEach((val, idx) => {
        let classes = 'arr-elem';

        if (idx === state.j) {
          classes += ' active';
        } else if (idx >= state.i && idx <= state.j) {
          classes += ' in-range';
        }

        if (idx >= state.maxStart && idx <= state.maxEnd) {
          classes += ' max';
        }

        if (val < 0) {
          classes += ' negative';
        }

        html += `<div class="${classes}" title="arr[${idx}]=${val}">${val}</div>`;
      });
      html += '</div>';
      document.getElementById("arrayDisplay").innerHTML = html;
    }

    function reset() {
      state.i = 0;
      state.j = 0;
      state.checks = 0;
      state.maxSum = 0;
      state.currentLine = 0;

      document.getElementById("metricChecks").textContent = "0";
      document.getElementById("metricMax").textContent = "0";
      document.getElementById("metricOps").textContent = "0%";
      document.getElementById("metricTime").textContent = "0ms";
      document.getElementById("codeArea").innerHTML = codeLines.map((l, i) => `<div class="code-line">${i + 1}. ${l}</div>`).join("");
      document.getElementById("statsDisplay").innerHTML = '';
      renderArray();
    }

    function loadPreset(key) {
      const presets = [
        { key: "small", label: "Small [-2,1,-3,4,-1,2]", arr: [-2, 1, -3, 4, -1, 2] },
        { key: "ex2", label: "Positive [1,2,3,4,5]", arr: [1, 2, 3, 4, 5] },
        { key: "ex3", label: "Negative [-5,-2,-8,-1]", arr: [-5, -2, -8, -1] },
        { key: "ex4", label: "Mixed [3,-1,2,-1,3]", arr: [3, -1, 2, -1, 3] }
      ];
      const preset = presets.find(p => p.key === key);
      if (preset) {
        state.arr = [...preset.arr];
        document.getElementById("customArray").value = state.arr.join(' ');
        reset();
      }
    }

    function applyCustom() {
      try {
        const input = document.getElementById("customArray").value.trim();
        if (!input) throw "Input required";
        const arr = input.split(/\s+/).map(v => {
          const n = parseInt(v);
          if (isNaN(n)) throw "Numbers only";
          return n;
        });
        if (arr.length === 0 || arr.length > 10) throw "Array length: 1-10 (for performance)";
        state.arr = arr;
        document.getElementById("presetSelect").value = "custom";
        reset();
        document.getElementById("inputStatus").textContent = "✓ Applied!";
        document.getElementById("inputStatus").style.color = "var(--good)";
      } catch (err) {
        document.getElementById("inputStatus").textContent = "✗ " + err;
        document.getElementById("inputStatus").style.color = "var(--warn)";
      }
    }

    document.querySelectorAll(".tab").forEach(tab => {
      tab.addEventListener("click", () => {
        document.querySelectorAll(".tab").forEach(t => t.classList.remove("active"));
        document.querySelectorAll(".panel").forEach(p => p.classList.remove("active"));
        tab.classList.add("active");
        document.getElementById("tab-" + tab.dataset.tab).classList.add("active");
      });
    });

    const presets = [
      { key: "small", label: "Small [-2,1,-3,4,-1,2]", arr: [-2, 1, -3, 4, -1, 2] },
      { key: "ex2", label: "Positive [1,2,3,4,5]", arr: [1, 2, 3, 4, 5] },
      { key: "ex3", label: "Negative [-5,-2,-8,-1]", arr: [-5, -2, -8, -1] },
      { key: "ex4", label: "Mixed [3,-1,2,-1,3]", arr: [3, -1, 2, -1, 3] }
    ];
    document.getElementById("presetSelect").innerHTML = presets.map(p => `<option value="${p.key}">${p.label}</option>`).join("") + `<option value="custom">Custom</option>`;
    document.getElementById("presetSelect").addEventListener("change", e => loadPreset(e.target.value));
    document.getElementById("speedSlider").addEventListener("input", e => {
      state.speed = parseFloat(e.target.value);
      document.getElementById("speedLabel").textContent = state.speed.toFixed(2) + "x";
    });
    document.getElementById("runBtn").addEventListener("click", async () => {
      if (!state.running) {
        document.getElementById("runBtn").disabled = true;
        await runBruteForce();
        document.getElementById("runBtn").disabled = false;
      }
    });
    document.getElementById("resetBtn").addEventListener("click", reset);
    document.getElementById("applyBtn").addEventListener("click", applyCustom);
    document.getElementById("resetInputBtn").addEventListener("click", () => {
      document.getElementById("customArray").value = "-2 1 -3 4 -1 2";
    });

    loadPreset("small");
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
