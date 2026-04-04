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
  <title>Hashing Functions</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet" />
  <style>
    :root{--bg:#0b1120;--card:#151d31;--border:#2b3652;--text:#e2e8f0;--muted:#94a3b8;--accent:#14b8a6;--accent2:#5eead4;--warn:#f59e0b;--good:#22c55e;--shadow:0 18px 45px rgba(0,0,0,.28)}

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
      --accent: #0f766e;
      --accent2: #0d9488;
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


    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:Inter,sans-serif;color:var(--text);background:radial-gradient(circle at top left,rgba(20,184,166,.12),transparent 24%),radial-gradient(circle at bottom right,rgba(94,234,212,.08),transparent 26%),linear-gradient(160deg,#08101d,#0b1120);min-height:100vh;padding:24px}
    .shell{max-width:1180px;margin:0 auto;display:grid;gap:18px}
    .hero,.card{background: var(--card);border:1px solid var(--border);border-radius:24px;box-shadow:var(--shadow)}
    .hero{padding:28px}
    .eyebrow{display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:999px;background:rgba(20,184,166,.1);border:1px solid rgba(20,184,166,.22);font-size:.75rem;letter-spacing:.08em;text-transform:uppercase;color:var(--accent2);font-weight:700;margin-bottom:16px}
    h1{font-size:clamp(2rem,4vw,3.3rem);line-height:1.08;margin-bottom:12px}
    h1 span,.card h2,.mini strong,.formula strong{color:var(--accent2)}
    .hero p,.card p,.card li{color:var(--muted);line-height:1.75}
    .hero-links{display:flex;flex-wrap:wrap;gap:10px;margin-top:18px}
    .hero-links a,.tab-btn,.method-btn,.demo-actions button{padding:10px 14px;border-radius:999px;border:1px solid var(--border);text-decoration:none;font-weight:600;font-size:.88rem}
    .hero-links a{background:rgba(20,184,166,.08);color:var(--text)}
    .hero-links a.primary,.demo-actions button.primary{background:linear-gradient(135deg,#0f766e,var(--accent));color:#031716;border-color:transparent}
    .tabs{display:flex;gap:4px;padding:10px;background: var(--card);border:1px solid var(--border);border-radius:22px;box-shadow:var(--shadow)}
    .tab-btn{background:transparent;color:var(--muted);cursor:pointer}
    .tab-btn.active{background:rgba(20,184,166,.12);color:var(--accent2)}
    .tab-panel{display:none;gap:18px}
    .tab-panel.active{display:grid}
    .grid{display:grid;grid-template-columns:1.05fr .95fr;gap:18px}
    .card{padding:22px}
    .card h2{font-size:1.05rem;margin-bottom:10px}
    .card ul{padding-left:18px;display:grid;gap:8px}
    .formula{margin-top:14px;padding:14px 16px;border-radius:16px;background: var(--surface);border:1px solid var(--border);font-family:"Fira Code",monospace;color:var(--text)}
    .mini-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}
    .mini{padding:16px;border-radius:16px;border:1px solid var(--border);background: var(--surface)}
    .mini strong{display:block;margin-bottom:6px}
    pre{margin-top:12px;padding:14px;border-radius:16px;border:1px solid var(--border);background:#09101f;overflow:auto}
    code{font-family:"Fira Code",monospace;color:#dbe4f3;white-space:pre}
    .demo-card{display:grid;gap:16px}
    .mode-row{display:flex;flex-wrap:wrap;gap:10px}
    .method-btn{background: var(--card);color:var(--text);cursor:pointer}
    .method-btn.active{background:rgba(20,184,166,.12);border-color:rgba(94,234,212,.4);color:var(--accent2)}
    .demo-actions{display:flex;gap:10px;flex-wrap:wrap}
    .status{padding:14px 16px;border-radius:16px;background:rgba(20,184,166,.08);border:1px solid rgba(20,184,166,.18);color:var(--text)}
    .demo-grid{display:grid;grid-template-columns:.78fr 1.22fr;gap:16px}
    .key-strip,.bucket-board{background: var(--surface);border:1px solid var(--border);border-radius:18px;padding:16px}
    .key-strip h3,.bucket-board h3,.formula-box h3{font-size:.82rem;letter-spacing:.08em;text-transform:uppercase;color:var(--accent2);margin-bottom:12px}
    .key-list{display:flex;flex-wrap:wrap;gap:10px}
    .key-pill{min-width:70px;padding:12px 14px;border-radius:14px;background: var(--card);border:1px solid var(--border);text-align:center;font-family:"Fira Code",monospace}
    .key-pill.active{background:rgba(20,184,166,.15);border-color:rgba(94,234,212,.4);transform:translateY(-2px)}
    .key-pill.done{background:rgba(34,197,94,.12);border-color:rgba(34,197,94,.32)}
    .formula-box{padding:16px;border-radius:18px;background: var(--surface);border:1px solid var(--border)}
    .formula-box p{font-family:"Fira Code",monospace;color:var(--text);line-height:1.7}
    .bucket-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:10px}
    .bucket{min-height:104px;border:1px solid var(--border);border-radius:18px;background: var(--card);padding:10px;display:flex;flex-direction:column;gap:10px}
    .bucket.active{border-color:rgba(94,234,212,.44);box-shadow:0 0 0 1px rgba(20,184,166,.15) inset}
    .bucket-index{font-size:.68rem;letter-spacing:.08em;text-transform:uppercase;color:var(--muted)}
    .bucket-values{display:flex;flex-wrap:wrap;gap:8px;align-content:flex-start}
    .value-chip,.empty-chip{min-width:44px;min-height:34px;padding:6px 8px;border-radius:10px;border:1px solid var(--border);display:inline-flex;align-items:center;justify-content:center;font-family:"Fira Code",monospace;font-size:.78rem}
    .value-chip{background:linear-gradient(180deg,#0f766e,var(--accent));color:#041a18;border-color:transparent}
    .empty-chip{background: var(--surface);color:#64748b}
    @media (max-width:920px){body{padding:14px}.grid,.demo-grid,.mini-grid{grid-template-columns:1fr}.hero,.card{padding:18px}.tabs{overflow:auto}}
  
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
<a href="../dashboard.php" style="position:fixed; top:20px; left:20px; z-index:9999999; padding:10px 20px; background:#ef4444; color:white; font-family:'Inter', sans-serif; font-size:14px; text-decoration:none; border-radius:8px; box-shadow:0 8px 15px rgba(0,0,0,0.2); font-weight:600; display:flex; align-items:center; gap:8px; transition:transform 0.2s;">&larr; Back to Dashboard</a>

  <div class="shell">
    <section class="hero">
      <div class="eyebrow">Core Hashing Topic</div>
      <h1><span>Hashing Functions</span> Explained</h1>
      <p>
        A hashing function converts a key into a table index. The goal is not just to get an index, but to spread keys
        across the table evenly so insert, search, and delete stay fast on average.
      </p>
      <div class="hero-links">
        <a class="primary" target="_top" href="intro_to_hashing.php">Open intro page</a>
        <a href="chaining_in_hashing.php">Next: chaining</a>
      </div>
    </section>

    <nav class="tabs">
      <button class="tab-btn active" onclick="showTab('tab-theory', this)">Theory</button>
      <button class="tab-btn" onclick="showTab('tab-demo', this)">Demo</button>
    </nav>

    <section id="tab-theory" class="tab-panel active">
      <section class="grid">
        <article class="card">
          <h2>What A Hash Function Does</h2>
          <p>
            A hash function takes an input key and turns it into a valid bucket index. In data structures, this index
            must stay inside the table size, so the function usually applies a modulo or another range-limiting step.
          </p>
          <div class="formula"><strong>Simple example:</strong> <code>h(k) = k % m</code>, where <code>m</code> is the table size.</div>
        </article>

        <article class="card">
          <h2>Good Properties</h2>
          <ul>
            <li><strong>Deterministic:</strong> the same key must always produce the same index.</li>
            <li><strong>Fast:</strong> hashing should not become the expensive part of the operation.</li>
            <li><strong>Uniform:</strong> keys should spread across the table instead of gathering in one area.</li>
            <li><strong>Range-safe:</strong> the result must fit inside the table.</li>
          </ul>
        </article>
      </section>

      <section class="grid">
        <article class="card">
          <h2>Common Methods</h2>
          <ul>
            <li><strong>Division method:</strong> use <code>k % m</code>. This is simple and very common.</li>
            <li><strong>Multiplication method:</strong> multiply by a constant, keep the fractional part, then scale into the table range.</li>
            <li><strong>Folding method:</strong> break the key into parts, add them, then reduce the result into the table range.</li>
            <li><strong>String hashing:</strong> combine character codes carefully instead of treating the whole string like one huge number.</li>
          </ul>
        </article>

        <article class="card">
          <h2>Why Table Size Matters</h2>
          <div class="mini-grid">
            <div class="mini">
              <strong>Good choice</strong>
              <p>Prime table sizes often help the division method distribute keys more evenly.</p>
            </div>
            <div class="mini">
              <strong>Bad choice</strong>
              <p>A poor table size can create repeated patterns and extra collisions even with a decent hash function.</p>
            </div>
          </div>
        </article>
      </section>

      <section class="grid">
        <article class="card">
          <h2>Small C Examples</h2>
          <p>These examples show three different ways to convert a numeric key into an index.</p>
          <pre><code>#include &lt;math.h&gt;

int divisionHash(int key, int m) {
    return key % m;
}

int multiplicationHash(int key, int m) {
    double A = 0.6180339887;
    double product = key * A;
    double fraction = product - floor(product);
    return (int)(m * fraction);
}

int foldingHash(int key, int m) {
    int sum = 0;
    while (key &gt; 0) {
        sum += key % 100;
        key /= 100;
    }
    return sum % m;
}</code></pre>
        </article>

        <article class="card">
          <h2>Main Idea To Remember</h2>
          <p>
            A hash function is not only about getting an answer quickly. It must also reduce collisions by spreading
            inputs in a balanced way. The same set of keys can behave very differently under different hash functions,
            which is why choosing the function matters.
          </p>
        </article>
      </section>
    </section>

    <section id="tab-demo" class="tab-panel">
      <section class="card demo-card">
        <h2>Function Comparison Demo</h2>
        <p>Select a hashing function, then step through sample keys to see how they map into an 11-slot table.</p>
        <div class="mode-row">
          <button class="method-btn active" data-method="division">Division</button>
          <button class="method-btn" data-method="multiplication">Multiplication</button>
          <button class="method-btn" data-method="folding">Folding</button>
        </div>
        <div class="demo-actions">
          <button id="replayBtn">Replay</button>
          <button class="primary" id="nextBtn">Next Key</button>
        </div>
        <div class="status" id="statusText">Division method selected. Start stepping through the keys to see where they land.</div>
        <div class="demo-grid">
          <div class="key-strip">
            <h3>Sample Keys</h3>
            <div class="key-list" id="keyList"></div>
          </div>
          <div class="bucket-board">
            <div class="formula-box">
              <h3>Current Formula</h3>
              <p id="formulaText">h(k) = k % 11</p>
            </div>
            <div class="bucket-grid" id="bucketGrid"></div>
          </div>
        </div>
      </section>

      <section class="grid">
        <article class="card">
          <h2>How To Read The Demo</h2>
          <ul>
            <li>The active key is highlighted on the left.</li>
            <li>Each bucket shows the keys mapped there so far.</li>
            <li>Switching the function resets the demo so you can compare from the same starting state.</li>
            <li>The status line shows the exact calculation for the current key.</li>
          </ul>
        </article>

        <article class="card">
          <h2>What To Notice</h2>
          <ul>
            <li>The same keys do not necessarily land in the same buckets under different functions.</li>
            <li>A better spread usually means fewer collisions later.</li>
            <li>Some functions are easier to compute, while others may spread keys more evenly for certain inputs.</li>
          </ul>
        </article>
      </section>
    </section>
  </div>

  <script>
    function showTab(id,btn){
      document.querySelectorAll('.tab-panel').forEach(panel=>panel.classList.remove('active'));
      document.querySelectorAll('.tab-btn').forEach(button=>button.classList.remove('active'));
      document.getElementById(id).classList.add('active');
      btn.classList.add('active');
    }

    const tableSize=11;
    const sampleKeys=[123,245,376,412,589];
    const methods={
      division:{
        label:'Division',
        formula:'h(k) = k % 11',
        compute(key){return key % tableSize;},
        explain(key,bucket){return `${key} % 11 = ${bucket}`;}
      },
      multiplication:{
        label:'Multiplication',
        formula:'h(k) = floor(11 * frac(k * 0.6180339887))',
        compute(key){
          const product=key*0.6180339887;
          const fraction=product-Math.floor(product);
          return Math.floor(tableSize*fraction);
        },
        explain(key,bucket){
          const product=key*0.6180339887;
          const fraction=product-Math.floor(product);
          return `${key} * 0.618 = ${product.toFixed(3)}, frac = ${fraction.toFixed(3)}, floor(11 * ${fraction.toFixed(3)}) = ${bucket}`;
        }
      },
      folding:{
        label:'Folding',
        formula:'h(k) = (sum of 2-digit groups) % 11',
        compute(key){
          let temp=key,sum=0;
          while(temp>0){
            sum+=temp%100;
            temp=Math.floor(temp/100);
          }
          return sum % tableSize;
        },
        explain(key,bucket){
          let temp=key,parts=[];
          while(temp>0){
            parts.unshift(temp%100);
            temp=Math.floor(temp/100);
          }
          const sum=parts.reduce((a,b)=>a+b,0);
          return `${parts.join(' + ')} = ${sum}, ${sum} % 11 = ${bucket}`;
        }
      }
    };

    const keyList=document.getElementById('keyList');
    const bucketGrid=document.getElementById('bucketGrid');
    const statusText=document.getElementById('statusText');
    const formulaText=document.getElementById('formulaText');
    const replayBtn=document.getElementById('replayBtn');
    const nextBtn=document.getElementById('nextBtn');
    const methodButtons=[...document.querySelectorAll('.method-btn')];
    let activeMethod='division';
    let stepIndex=0;

    function renderDemo(){
      const method=methods[activeMethod];
      const insertedKeys=sampleKeys.slice(0,stepIndex);
      const buckets=Array.from({length:tableSize},()=>[]);
      insertedKeys.forEach(key=>buckets[method.compute(key)].push(key));

      keyList.innerHTML=sampleKeys.map((key,index)=>{
        const cls=['key-pill',index<stepIndex?'done':'',index===Math.max(0,stepIndex-1)&&stepIndex>0?'active':''].filter(Boolean).join(' ');
        return `<div class="${cls}">${key}</div>`;
      }).join('');

      const activeBucket=stepIndex>0?method.compute(sampleKeys[stepIndex-1]):-1;
      bucketGrid.innerHTML=buckets.map((values,index)=>{
        const chips=values.length?values.map(value=>`<div class="value-chip">${value}</div>`).join(''):'<div class="empty-chip">empty</div>';
        return `<div class="bucket ${index===activeBucket?'active':''}"><div class="bucket-index">bucket ${index}</div><div class="bucket-values">${chips}</div></div>`;
      }).join('');

      formulaText.textContent=method.formula;
      if(stepIndex===0){
        statusText.textContent=`${method.label} method selected. Start stepping through the keys to see where they land.`;
      }else{
        const key=sampleKeys[stepIndex-1];
        const bucket=method.compute(key);
        statusText.textContent=`${method.label} method: ${method.explain(key,bucket)}. Key ${key} goes to bucket ${bucket}.`;
      }
      nextBtn.textContent=stepIndex===sampleKeys.length?'Replay Keys':'Next Key';
    }

    function setMethod(name){
      activeMethod=name;
      stepIndex=0;
      methodButtons.forEach(button=>button.classList.toggle('active',button.dataset.method===name));
      renderDemo();
    }

    methodButtons.forEach(button=>{
      button.addEventListener('click',()=>setMethod(button.dataset.method));
    });

    nextBtn.addEventListener('click',()=>{
      stepIndex=stepIndex===sampleKeys.length?0:stepIndex+1;
      renderDemo();
    });

    replayBtn.addEventListener('click',()=>{
      stepIndex=0;
      renderDemo();
    });

    renderDemo();
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
