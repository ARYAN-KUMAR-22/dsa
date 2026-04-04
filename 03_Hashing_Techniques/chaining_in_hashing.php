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
  <title>Chaining in Hashing</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet" />
  <style>
    :root{--bg:#0b1120;--card:#141c30;--panel:#0f1728;--border:#2a3551;--text:#e2e8f0;--muted:#93a4c3;--accent:#2dd4bf;--accent2:#99f6e4;--warn:#f59e0b;--good:#22c55e;--shadow:0 18px 45px rgba(0,0,0,.28)}

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
      --accent: #0d9488;
      --accent2: #0d9488;
      --good: #15803d;
      --warn: #b45309;
    }

    body.light-mode header {
      background: var(--panel);
      border-bottom-color: var(--border);
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
    body{font-family:Inter,sans-serif;color:var(--text);background:radial-gradient(circle at top left,rgba(45,212,191,.12),transparent 24%),radial-gradient(circle at bottom right,rgba(96,165,250,.08),transparent 26%),linear-gradient(160deg,#08101d,#0b1120);min-height:100vh;padding:24px}
    .shell{max-width:1180px;margin:0 auto;display:grid;gap:18px}
    .hero,.card{background: var(--card);border:1px solid var(--border);border-radius:24px;box-shadow:var(--shadow)}
    .hero{padding:28px}
    .eyebrow{display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:999px;background:rgba(45,212,191,.1);border:1px solid rgba(45,212,191,.2);font-size:.75rem;letter-spacing:.08em;text-transform:uppercase;color:var(--accent2);font-weight:700;margin-bottom:16px}
    h1{font-size:clamp(2rem,4vw,3.4rem);line-height:1.08;margin-bottom:12px}
    h1 span,.card h2,.mini strong,.formula strong{color:var(--accent2)}
    .hero p,.card p,.card li{color:var(--muted);line-height:1.75}
    .hero-links{display:flex;flex-wrap:wrap;gap:10px;margin-top:18px}
    .hero-links a,.demo-actions button{padding:10px 14px;border-radius:999px;border:1px solid var(--border);text-decoration:none;font-weight:600;font-size:.88rem}
    .hero-links a{background:rgba(45,212,191,.08);color:var(--text)}
    .hero-links a.primary{background:linear-gradient(135deg,#0f766e,var(--accent));color:#041614;border-color:transparent}
    .grid{display:grid;grid-template-columns:1.1fr .9fr;gap:18px}
    .card{padding:22px}
    .card h2{font-size:1.05rem;margin-bottom:10px}
    .card ul{padding-left:18px;display:grid;gap:8px}
    .formula{margin-top:14px;padding:14px 16px;border-radius:16px;background: var(--surface);border:1px solid var(--border);font-family:"Fira Code",monospace;color:var(--text)}
    .demo-card{display:grid;gap:16px}
    .demo-head{display:flex;flex-wrap:wrap;justify-content:space-between;gap:12px;align-items:flex-start}
    .demo-actions{display:flex;gap:10px;flex-wrap:wrap}
    .demo-actions button{background: var(--card);color:var(--text);cursor:pointer}
    .demo-actions button.primary{background:linear-gradient(135deg,#0f766e,var(--accent));color:#041614;border-color:transparent}
    .status{padding:14px 16px;border-radius:16px;background:rgba(45,212,191,.08);border:1px solid rgba(45,212,191,.18);color:var(--text)}
    .demo-grid{display:grid;grid-template-columns:.72fr 1.28fr;gap:16px}
    .key-strip,.bucket-board{background: var(--surface);border:1px solid var(--border);border-radius:18px;padding:16px}
    .key-strip h3,.bucket-board h3,.code-card h3{font-size:.82rem;letter-spacing:.08em;text-transform:uppercase;color:var(--accent2);margin-bottom:12px}
    .key-list{display:flex;flex-wrap:wrap;gap:10px}
    .key-pill{min-width:64px;padding:12px 14px;border-radius:14px;background: var(--card);border:1px solid var(--border);text-align:center;font-family:"Fira Code",monospace}
    .key-pill.active{background:rgba(45,212,191,.15);border-color:rgba(153,246,228,.35);transform:translateY(-2px)}
    .bucket-list{display:grid;gap:10px}
    .bucket-row{display:grid;grid-template-columns:90px 1fr;min-height:64px;border:1px solid var(--border);border-radius:16px;overflow:hidden;background: var(--surface)}
    .bucket-row.active{border-color:rgba(153,246,228,.42);box-shadow:0 0 0 1px rgba(45,212,191,.12) inset}
    .bucket-index{display:flex;flex-direction:column;justify-content:center;gap:4px;padding:12px;background: var(--surface);border-right:1px solid var(--border)}
    .bucket-index span{font-size:.68rem;letter-spacing:.08em;text-transform:uppercase;color:var(--muted)}
    .bucket-index strong{font-family:"Fira Code",monospace;font-size:.92rem;color:var(--accent2)}
    .chain{display:flex;align-items:center;flex-wrap:wrap;gap:8px;padding:10px 12px}
    .node,.empty,.arrow{display:inline-flex;align-items:center;justify-content:center}
    .node,.empty{min-width:58px;min-height:38px;padding:8px 10px;border-radius:12px;border:1px solid var(--border);font-family:"Fira Code",monospace}
    .node{background:linear-gradient(180deg,#0f766e,var(--accent));color:#04211e;border-color:transparent}
    .node.new{background:linear-gradient(180deg,#d97706,var(--warn));color:#261704}
    .empty{background: var(--surface);color:#60708d}
    .arrow{font-size:1rem;color:var(--muted)}
    .mini-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}
    .mini{padding:16px;border-radius:16px;border:1px solid var(--border);background: var(--surface)}
    .mini strong{display:block;margin-bottom:6px}
    .code-card{padding:0}
    .code-card .inner{padding:22px}
    pre{margin-top:12px;padding:14px;border-radius:16px;border:1px solid var(--border);background:#09101f;overflow:auto}
    code{font-family:"Fira Code",monospace;color:#dbe4f3;white-space:pre}
    .pseudo{display:grid;gap:8px;margin-top:12px}
    .pseudo-line{display:grid;grid-template-columns:32px 1fr;gap:10px;padding:8px 10px;border-radius:12px;background: var(--surface);font-family:"Fira Code",monospace;font-size:.8rem;color:#dbe4f3}
    .pseudo-line span{color:#60708d;text-align:right}
    @media (max-width:920px){body{padding:14px}.grid,.demo-grid,.mini-grid{grid-template-columns:1fr}.hero,.card,.code-card .inner{padding:18px}}
  
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
  <div class="shell">
    <section class="hero">
      <div class="eyebrow">Hashing Collision Strategy</div>
      <h1><span>Chaining</span> in Hashing</h1>
      <p>
        Chaining is a collision-handling method where each bucket stores a small linked list or chain of keys.
        If many keys map to the same bucket, they are connected one after another instead of overwriting each other.
      </p>
      <div class="hero-links">
        <a class="primary" target="_top" href="intro_to_hashing.php">Open intro page</a>
        <a href="linear_probing_in_hashing.php">Next: linear probing</a>
      </div>
    </section>

    <section class="grid">
      <article class="card">
        <h2>Concept</h2>
        <p>
          A hash table tries to send each key to a bucket using a hash function. When two different keys produce the
          same bucket index, a collision happens. Chaining solves that by keeping a list inside the bucket, so every
          colliding key still has a place to go.
        </p>
        <div class="formula"><strong>Example:</strong> <code>h(k) = k % 7</code>. Keys <code>18</code>, <code>32</code>, and <code>46</code> all map to bucket <code>4</code>.</div>
      </article>

      <article class="card">
        <h2>Why It Helps</h2>
        <ul>
          <li>Insertion stays simple because the key is appended to that bucket's chain.</li>
          <li>Search stays local because only one bucket's list is scanned.</li>
          <li>Deletion is easier than many probing methods because nodes can be removed from the chain directly.</li>
          <li>The table can still work even when collisions are frequent.</li>
        </ul>
      </article>
    </section>

    <section class="card demo-card">
      <div class="demo-head">
        <div>
          <h2>Collision Example</h2>
          <p>Step through three keys that all hash to the same bucket and see how the chain grows.</p>
        </div>
        <div class="demo-actions">
          <button id="replayBtn">Replay</button>
          <button class="primary" id="nextBtn">Next Step</button>
        </div>
      </div>
      <div class="status" id="statusText">Start with an empty table of size 7. No chains exist yet.</div>
      <div class="demo-grid">
        <div class="key-strip">
          <h3>Incoming Keys</h3>
          <div class="key-list" id="keyList"></div>
        </div>
        <div class="bucket-board">
          <h3>Bucket Representation</h3>
          <div class="bucket-list" id="bucketList"></div>
        </div>
      </div>
    </section>

    <section class="grid">
      <article class="card">
        <h2>What Happens During Search</h2>
        <ul>
          <li>Compute the bucket index using the hash function.</li>
          <li>Go to that single bucket.</li>
          <li>Traverse the chain node by node until the key is found or the chain ends.</li>
          <li>If the chains stay short, search remains close to constant time on average.</li>
        </ul>
      </article>

      <article class="card">
        <h2>Tradeoffs</h2>
        <div class="mini-grid">
          <div class="mini">
            <strong>Good</strong>
            <p>Simple collision handling, natural deletion, and easy mental model for beginners.</p>
          </div>
          <div class="mini">
            <strong>Limit</strong>
            <p>If the load factor grows too much, chains become long and performance moves toward linear search.</p>
          </div>
        </div>
      </article>
    </section>

    <section class="grid">
      <article class="card">
        <h2>Pseudo Logic</h2>
        <div class="pseudo">
          <div class="pseudo-line"><span>1</span><div>index = key % tableSize</div></div>
          <div class="pseudo-line"><span>2</span><div>if bucket[index] is empty</div></div>
          <div class="pseudo-line"><span>3</span><div>  create first node in that bucket</div></div>
          <div class="pseudo-line"><span>4</span><div>else</div></div>
          <div class="pseudo-line"><span>5</span><div>  move to the end of the chain</div></div>
          <div class="pseudo-line"><span>6</span><div>  append the new node</div></div>
        </div>
      </article>

      <article class="card code-card">
        <div class="inner">
          <h2>C Code Example</h2>
          <p>This is a small bucket-chaining example using linked nodes.</p>
          <pre><code>#include &lt;stdio.h&gt;
#include &lt;stdlib.h&gt;

#define TABLE_SIZE 7

typedef struct Node {
    int key;
    struct Node* next;
} Node;

Node* table[TABLE_SIZE] = {NULL};

void insert(int key) {
    int index = key % TABLE_SIZE;
    Node* newNode = (Node*)malloc(sizeof(Node));
    newNode-&gt;key = key;
    newNode-&gt;next = NULL;

    if (table[index] == NULL) {
        table[index] = newNode;
        return;
    }

    Node* temp = table[index];
    while (temp-&gt;next != NULL) {
        temp = temp-&gt;next;
    }
    temp-&gt;next = newNode;
}</code></pre>
        </div>
      </article>
    </section>
  </div>

  <script>
    const steps=[
      {active:-1,bucket:-1,chain:[],message:'Start with an empty table of size 7. No chains exist yet.'},
      {active:0,bucket:4,chain:[18],message:'Insert 18. Since 18 % 7 = 4, bucket 4 gets its first node.'},
      {active:1,bucket:4,chain:[18,32],message:'Insert 32. It also maps to bucket 4, so it is appended after 18 in the same chain.'},
      {active:2,bucket:4,chain:[18,32,46],message:'Insert 46. It collides again and is added at the end, making the chain longer.'}
    ];
    const keys=[18,32,46];
    const keyList=document.getElementById('keyList');
    const bucketList=document.getElementById('bucketList');
    const statusText=document.getElementById('statusText');
    const replayBtn=document.getElementById('replayBtn');
    const nextBtn=document.getElementById('nextBtn');
    let stepIndex=0;

    function render(){
      const step=steps[stepIndex];
      keyList.innerHTML=keys.map((key,index)=>`<div class="key-pill ${index===step.active?'active':''}">${key}</div>`).join('');
      bucketList.innerHTML=Array.from({length:7},(_,bucket)=>{
        const isActive=bucket===step.bucket;
        const chain=isActive?step.chain:[];
        const chainHtml=chain.length?chain.map((value,index)=>`${index?'<span class="arrow">-></span>':''}<div class="node ${index===chain.length-1&&step.active>=0?'new':''}">${value}</div>`).join(''):'<div class="empty">empty</div>';
        return `<div class="bucket-row ${isActive?'active':''}"><div class="bucket-index"><span>Bucket</span><strong>${bucket}</strong></div><div class="chain">${chainHtml}</div></div>`;
      }).join('');
      statusText.textContent=step.message;
      nextBtn.textContent=stepIndex===steps.length-1?'Replay Steps':'Next Step';
    }

    nextBtn.addEventListener('click',()=>{
      stepIndex=stepIndex===steps.length-1?0:stepIndex+1;
      render();
    });

    replayBtn.addEventListener('click',()=>{
      stepIndex=0;
      render();
    });

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
