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
  <title>Longest Common Subsequence - Recursion</title>
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
    .actions{margin-left:auto;display:flex;gap:6px}
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
    .viz-container{flex:1;display:grid;grid-template-columns:1fr 1fr;gap:10px;padding:10px 12px;overflow:hidden}
    .viz-box{border:1px solid var(--border);border-radius:10px;background: var(--surface);display:flex;flex-direction:column;overflow:hidden}
    .viz-head{padding:8px 12px;border-bottom:1px solid var(--border);font-size:.8rem;font-weight:700;color:var(--accent)}
    .viz-content{flex:1;overflow:auto;padding:10px;font-family:"Fira Code",monospace;font-size:.7rem;line-height:1.5}
    .code-line{padding:6px 8px;margin:2px 0;border-radius:6px;background: var(--surface);color:var(--text);cursor:pointer;transition:all .2s}
    .code-line.active{background:linear-gradient(90deg,rgba(96,165,250,.4),transparent);border-left:3px solid var(--accent);color:#60a5fa;font-weight:600}
    .call-box{margin:8px 0;padding:10px;border-radius:8px;border:2px solid var(--accent);background:rgba(96,165,250,.1);position:relative}
    .call-label{font-size:.65rem;color:var(--accent);font-weight:800;margin-bottom:4px}
    .call-content{font-size:.68rem;color:var(--text)}
    .memo-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(26px,1fr));gap:2px;margin:4px 0}
    .memo-cell{width:26px;height:26px;display:flex;align-items:center;justify-content:center;border-radius:4px;font-size:.55rem;font-weight:700;border:1px solid var(--border);background: var(--surface);color:var(--muted)}
    .memo-cell.computed{background:var(--good);color:#0b1020;box-shadow:0 0 8px rgba(34,197,94,.4)}
    .memo-cell.current{background:var(--warn);color:#0b1020;box-shadow:0 0 10px rgba(245,158,11,.6);animation:pulse2 .6s infinite}
    @keyframes pulse2{0%,100%{transform:scale(1)}50%{transform:scale(1.05)}}
    .panel{display:none;padding:10px 12px;overflow-y:auto;flex:1}
    .panel.active{display:flex;flex-direction:column}
    .note{color:var(--muted);font-size:.8rem;line-height:1.6;margin:8px 0}
    .editor-field{display:grid;gap:6px}
    .editor-field strong{font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;color:var(--muted)}
    .editor-field input{width:100%;border:1px solid var(--border);border-radius:10px;background: var(--card);color:var(--text);padding:8px 10px;font:.75rem "Fira Code",monospace;outline:none;transition:border-color .2s}
    .editor-field input:focus{border-color:var(--accent)}
    @media(max-width:1200px){.viz-container{grid-template-columns:1fr;height:300px}}
  
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
      <h1><span>Longest Common</span> Subsequence - Recursion</h1>
      <p>Naive recursive approach - see exponential inefficiency before optimization</p>
    </div>
    <div class="actions">
      <a class="primary" href="../index.php">Home</a>
      <a href="longest_common_subsequence.php">LCS Intro</a>
      <a href="matrix_chain_multiplication_recursion.php">MCM Recursion</a>
    </div>
  </header>

  <div class="app">
    <aside class="left">
      <div class="panel-head"><span class="pulse"></span>Metrics</div>
      <section class="sec">
        <div class="sec-title">Stats</div>
        <div class="metrics">
          <div class="metric"><div class="metric-label">Calls</div><div class="metric-value" id="metricCalls">0</div></div>
          <div class="metric"><div class="metric-label">Depth</div><div class="metric-value" id="metricDepth">0</div></div>
          <div class="metric"><div class="metric-label">Result</div><div class="metric-value" id="metricResult">0</div></div>
          <div class="metric"><div class="metric-label">Time</div><div class="metric-value" id="metricTime">0ms</div></div>
        </div>
      </section>
      <section class="sec">
        <div class="sec-title">Call Stack</div>
        <div id="callStack" style="padding:0 10px 10px"></div>
      </section>
      <section class="sec" style="border:none">
        <div class="sec-title">Problem</div>
        <div class="box" id="problemBox">Ready</div>
      </section>
    </aside>

    <main class="work">
      <div class="tabs">
        <button class="tab active" data-tab="visualizer">🔍 Execution Trace</button>
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
            <div class="viz-head">📝 Code Execution</div>
            <div class="viz-content" id="codeArea"></div>
          </div>
          <div class="viz-box">
            <div class="viz-head">🧠 Call Stack & Computed</div>
            <div class="viz-content">
              <div id="stackDisplay"></div>
              <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border)">
                <div style="font-size:.7rem;font-weight:700;color:var(--accent);margin-bottom:6px">Computed (i,j):</div>
                <div class="memo-grid" id="memoDisplay"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="tab-input">
        <div class="note" style="color:var(--accent3)">
          <strong>Format:</strong> Two strings (max 10 chars each)<br/>
          Example: AGGTAB,GXTXAYB
        </div>
        <div class="editor-field">
          <strong>String 1</strong>
          <input id="customStr1" type="text" value="AGGTAB" placeholder="First string" />
        </div>
        <div class="editor-field">
          <strong>String 2</strong>
          <input id="customStr2" type="text" value="GXTXAYB" placeholder="Second string" />
        </div>
        <div style="display:flex;gap:8px;margin-top:10px">
          <button class="sim-btn primary" id="applyBtn">✓ Apply</button>
          <button class="sim-btn" id="resetInputBtn">↻ Reset</button>
        </div>
        <div id="inputStatus" class="note"></div>
      </div>

      <div class="panel" id="tab-learn">
        <div style="overflow-y:auto;padding-right:10px">
          <h3 style="color:var(--accent);margin:10px 0 6px;font-size:.95rem">How It Works</h3>
          <div class="box">
            Watch the code execute line-by-line! The highlighted line shows which statement is running. The right panel shows the call stack and which (i,j) pairs have been computed.
          </div>
          <h3 style="color:var(--accent);margin:14px 0 6px;font-size:.95rem">The Problem</h3>
          <div class="box">
            <strong>Same subproblems appear multiple times!</strong><br/>
            For "AB" & "CD": ~7 calls<br/>For "ABC" & "DEF": ~31 calls<br/>
            This exponential growth is why DP is essential.
          </div>
          <h3 style="color:var(--accent);margin:14px 0 6px;font-size:.95rem">Recursion Logic</h3>
          <div class="box">
            <strong>Two cases:</strong><br/>
            • If chars match: LCS(i-1,j-1) + 1<br/>
            • Else: max(LCS(i-1,j), LCS(i,j-1))<br/>
            Base case: If string exhausted, return 0
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    const fmt = v => new Intl.NumberFormat("en-US").format(v);

    const state = {
      str1: "AGGTAB", str2: "GXTXAYB", callCount: 0, maxDepth: 0, result: 0, n: 0,
      running: false, callStack: [], computed: new Set(), currentLine: null, startTime: 0, speed: 1
    };

    const codeLines = [
      "function LCS(i, j):",
      "  if i == 0 or j == 0:",
      "    return 0",
      "  if str1[i-1] == str2[j-1]:",
      "    return LCS(i-1,j-1) + 1",
      "  else:",
      "    return max(LCS(i-1,j), LCS(i,j-1))"
    ];

    async function buildTree(i, j, depth = 0) {
      state.callCount++;
      state.maxDepth = Math.max(state.maxDepth, depth);
      
      state.callStack.push({ i, j, result: null });
      updateDisplay();
      await sleep(80);
      
      state.currentLine = 1;
      if (i === 0 || j === 0) {
        state.currentLine = 2;
        updateDisplay();
        await sleep(40);
        state.callStack[state.callStack.length - 1].result = 0;
        state.computed.add(`[${i},${j}]=0`);
        updateDisplay();
        await sleep(50);
        state.callStack.pop();
        return 0;
      }

      state.currentLine = 4;
      updateDisplay();
      await sleep(60);
      
      if (state.str1[i - 1] === state.str2[j - 1]) {
        state.currentLine = 5;
        updateDisplay();
        await sleep(40);
        const res = await buildTree(i - 1, j - 1, depth + 1) + 1;
        state.callStack[state.callStack.length - 1].result = res;
        state.computed.add(`[${i},${j}]=${res}`);
        updateDisplay();
        await sleep(50);
        state.callStack.pop();
        return res;
      } else {
        state.currentLine = 6;
        updateDisplay();
        await sleep(40);
        state.currentLine = 7;
        updateDisplay();
        await sleep(30);
        const left = await buildTree(i - 1, j, depth + 1);
        
        state.currentLine = 7;
        updateDisplay();
        await sleep(30);
        const right = await buildTree(i, j - 1, depth + 1);
        
        const res = Math.max(left, right);
        state.callStack[state.callStack.length - 1].result = res;
        state.computed.add(`[${i},${j}]=${res}`);
        updateDisplay();
        await sleep(50);
        state.callStack.pop();
        return res;
      }
    }

    function sleep(ms) {
      return new Promise(resolve => setTimeout(resolve, ms / state.speed));
    }

    function updateDisplay() {
      // Code area
      let codeHtml = '';
      codeLines.forEach((line, idx) => {
        const lineNum = idx + 1;
        const isActive = state.currentLine === lineNum;
        codeHtml += `<div class="code-line ${isActive ? 'active' : ''}">${lineNum.toString().padStart(2, '0')}. ${line}</div>`;
      });
      document.getElementById("codeArea").innerHTML = codeHtml;

      // Call stack
      let stackHtml = '';
      state.callStack.forEach((call, idx) => {
        stackHtml += `<div class="call-box">
          <div class="call-label">Frame ${idx + 1}: LCS(${call.i}, ${call.j})</div>
          <div class="call-content">${call.result !== null ? `Result: ${call.result}` : 'Computing...'}</div>
        </div>`;
      });
      document.getElementById("stackDisplay").innerHTML = stackHtml || '<div style="color:var(--muted);font-size:.7rem">Idle</div>';

      // Memo grid
      let memoHtml = '';
      state.computed.forEach(item => {
        const match = item.match(/\[(\d+),(\d+)\]/);
        if (match) {
          const i = parseInt(match[1]);
          const j = parseInt(match[2]);
          memoHtml += `<div class="memo-cell computed">[${i},${j}]</div>`;
        }
      });
      document.getElementById("memoDisplay").innerHTML = memoHtml || '<div style="color:var(--muted);font-size:.7rem">None</div>';
    }

    async function runRecursion() {
      state.callCount = 0;
      state.maxDepth = 0;
      state.result = 0;
      state.callStack = [];
      state.computed = new Set();
      state.running = true;

      state.startTime = performance.now();
      state.result = await buildTree(state.str1.length, state.str2.length);
      const elapsed = Math.round(performance.now() - state.startTime);

      state.running = false;
      document.getElementById("metricCalls").textContent = fmt(state.callCount);
      document.getElementById("metricDepth").textContent = state.maxDepth;
      document.getElementById("metricResult").textContent = state.result;
      document.getElementById("metricTime").textContent = elapsed + "ms";

      document.getElementById("problemBox").textContent = `"${state.str1}" & "${state.str2}"`;
    }

    function reset() {
      state.callCount = 0;
      state.maxDepth = 0;
      state.result = 0;
      state.callStack = [];
      state.computed = new Set();
      state.currentLine = null;
      
      document.getElementById("metricCalls").textContent = "0";
      document.getElementById("metricDepth").textContent = "0";
      document.getElementById("metricResult").textContent = "0";
      document.getElementById("metricTime").textContent = "0ms";
      document.getElementById("codeArea").innerHTML = codeLines.map((l,i) => `<div class="code-line">${(i+1).toString().padStart(2,'0')}. ${l}</div>`).join("");
      document.getElementById("stackDisplay").innerHTML = '';
      document.getElementById("memoDisplay").innerHTML = '';
    }

    function loadPreset(key) {
      const presets = [
        { key: "small", label: "AB & CD", str1: "AB", str2: "CD" },
        { key: "ex1", label: "AGGTAB & GXTXAYB", str1: "AGGTAB", str2: "GXTXAYB" },
        { key: "ex2", label: "ABC & DEF", str1: "ABC", str2: "DEF" },
        { key: "ex3", label: "DOG & CATS", str1: "DOG", str2: "CATS" }
      ];
      const preset = presets.find(p => p.key === key);
      if (preset) { 
        state.str1 = preset.str1;
        state.str2 = preset.str2;
        document.getElementById("customStr1").value = state.str1;
        document.getElementById("customStr2").value = state.str2;
        reset(); 
      }
    }

    function applyCustom() {
      try {
        let s1 = document.getElementById("customStr1").value.trim().toUpperCase();
        let s2 = document.getElementById("customStr2").value.trim().toUpperCase();
        if (!s1 || !s2) throw "Strings required";
        if (s1.length > 10 || s2.length > 10) throw "Max 10 chars";
        if (!/^[A-Z]+$/.test(s1) || !/^[A-Z]+$/.test(s2)) throw "Letters only";
        state.str1 = s1;
        state.str2 = s2;
        document.getElementById("presetSelect").value = "custom";
        reset();
        document.getElementById("inputStatus").textContent = "✓ Applied!";
        document.getElementById("inputStatus").style.color = "var(--good)";
      } catch (err) {
        document.getElementById("inputStatus").textContent = "✗ " + err;
        document.getElementById("inputStatus").style.color = "var(--warn)";
      }
    }

    // Tab switching
    document.querySelectorAll(".tab").forEach(tab => {
      tab.addEventListener("click", () => {
        document.querySelectorAll(".tab").forEach(t => t.classList.remove("active"));
        document.querySelectorAll(".panel").forEach(p => p.classList.remove("active"));
        tab.classList.add("active");
        document.getElementById("tab-" + tab.dataset.tab).classList.add("active");
      });
    });

    // Event listeners
    const presets = [
      { key: "small", label: "AB & CD", str1: "AB", str2: "CD" },
      { key: "ex1", label: "AGGTAB & GXTXAYB", str1: "AGGTAB", str2: "GXTXAYB" },
      { key: "ex2", label: "ABC & DEF", str1: "ABC", str2: "DEF" },
      { key: "ex3", label: "DOG & CATS", str1: "DOG", str2: "CATS" }
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
        await runRecursion();
        document.getElementById("runBtn").disabled = false;
      }
    });
    document.getElementById("resetBtn").addEventListener("click", reset);
    document.getElementById("applyBtn").addEventListener("click", applyCustom);
    document.getElementById("resetInputBtn").addEventListener("click", () => {
      document.getElementById("customStr1").value = "AGGTAB";
      document.getElementById("customStr2").value = "GXTXAYB";
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
