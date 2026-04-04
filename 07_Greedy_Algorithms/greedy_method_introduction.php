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
  <title>Greedy Method - Introduction</title>
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
      --accent: #f59e0b;
      --accent-2: #fbbf24;
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
      --accent: #b45309;
      --accent-2: #92400e;
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
      background: linear-gradient(90deg, #1f1107, #0d1117);
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
      0%, 100% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.45; transform: scale(0.84); }
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

    .workspace {
      flex: 1;
      min-width: 0;
      min-height: 0;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      background:
        radial-gradient(circle at top right, rgba(245, 158, 11, 0.08), transparent 28%),
        radial-gradient(circle at bottom left, rgba(251, 191, 36, 0.06), transparent 30%),
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
      padding: 18px;
    }

    .tab-panel.active {
      display: block;
    }

    .tab-panel::-webkit-scrollbar {
      width: 5px;
    }

    .tab-panel::-webkit-scrollbar-thumb {
      background: var(--border);
      border-radius: 999px;
    }

    .content-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 18px;
      margin-bottom: 16px;
    }

    .content-card h3 {
      color: var(--accent-2);
      font-size: 1rem;
      margin-bottom: 10px;
    }

    .content-card p, .content-card li {
      color: var(--muted);
      line-height: 1.7;
      margin-bottom: 8px;
    }

    .content-card ul, .content-card ol {
      padding-left: 20px;
      display: grid;
      gap: 6px;
    }

    .concept-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 12px;
      margin-top: 12px;
    }

    .concept-box {
      background: var(--card);
      border: 1px solid var(--border);
      padding: 12px;
      border-radius: 10px;
    }

    .concept-box h4 {
      color: var(--accent-2);
      font-size: 0.85rem;
      margin-bottom: 6px;
    }

    .concept-box p {
      font-size: 0.78rem;
      color: var(--muted);
    }

    code {
      background: rgba(245, 158, 11, 0.1);
      color: var(--accent-2);
      padding: 2px 6px;
      border-radius: 4px;
      font-family: 'Fira Code', monospace;
      font-size: 0.85rem;
    }

    .example-highlight {
      background: rgba(245, 158, 11, 0.08);
      border-left: 3px solid var(--accent);
      padding: 12px;
      border-radius: 8px;
      margin-top: 10px;
    }

    .example-highlight strong {
      color: var(--accent-2);
    }

    .code-block {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 12px;
      margin-top: 10px;
      font-family: 'Fira Code', monospace;
      font-size: 0.78rem;
      color: #86efac;
      overflow-x: auto;
      line-height: 1.6;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 12px;
      font-size: 0.82rem;
    }

    table th {
      background: rgba(245, 158, 11, 0.1);
      color: var(--accent-2);
      padding: 10px;
      text-align: left;
      border-bottom: 2px solid var(--border);
    }

    table td {
      padding: 10px;
      border-bottom: 1px solid var(--border);
      color: var(--muted);
    }

    .pros-cons {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 12px;
      margin-top: 12px;
    }

    .pros, .cons {
      background: var(--card);
      border: 1px solid var(--border);
      padding: 12px;
      border-radius: 10px;
    }

    .pros h4 {
      color: #86efac;
      margin-bottom: 8px;
    }

    .cons h4 {
      color: #fca5a5;
      margin-bottom: 8px;
    }

    .pros li, .cons li {
      font-size: 0.78rem;
      margin-bottom: 4px;
    }

    @media (max-width: 900px) {
      .left-panel { width: 240px; }
      .concept-grid { grid-template-columns: 1fr; }
      .pros-cons { grid-template-columns: 1fr; }
    }

    @media (max-width: 700px) {
      .app-body { flex-direction: column; }
      .left-panel { width: 100%; min-height: auto; border-right: none; border-bottom: 1px solid var(--border); }
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
    <h1><span>Greedy Method</span> - Introduction</h1>
    <p>Understanding greedy algorithms and their applications</p>
  </header>

  <div class="app-body">
    <div class="left-panel">
      <div class="panel-header">
        <span class="pulse-dot"></span>
        Method Overview
      </div>
      
      <div class="panel-section">
        <div class="panel-section-title">Key Characteristics</div>
        <div class="summary-grid">
          <div class="metric">
            <div class="metric-label">Strategy</div>
            <div class="metric-value">Local Opt</div>
          </div>
          <div class="metric">
            <div class="metric-label">Approach</div>
            <div class="metric-value">No Backtrack</div>
          </div>
          <div class="metric">
            <div class="metric-label">Speed</div>
            <div class="metric-value">Fast</div>
          </div>
          <div class="metric">
            <div class="metric-label">Optimal</div>
            <div class="metric-value">Not Always</div>
          </div>
        </div>
      </div>

      <div class="panel-section">
        <div class="panel-section-title">Complexity Analysis</div>
        <div class="summary-grid">
          <div class="metric">
            <div class="metric-label">Typical Time</div>
            <div class="metric-value">O(n log n)</div>
          </div>
          <div class="metric">
            <div class="metric-label">Space</div>
            <div class="metric-value">O(n)</div>
          </div>
          <div class="metric">
            <div class="metric-label">Best Case</div>
            <div class="metric-value">O(n)</div>
          </div>
          <div class="metric">
            <div class="metric-label">Worst Case</div>
            <div class="metric-value">O(n²)</div>
          </div>
        </div>
      </div>

      <div class="panel-section">
        <div class="panel-section-title">Problem Categories</div>
        <div class="summary-grid">
          <div class="metric">
            <div class="metric-label">Selection</div>
            <div class="metric-value">Activity</div>
          </div>
          <div class="metric">
            <div class="metric-label">Ordering</div>
            <div class="metric-value">Scheduling</div>
          </div>
          <div class="metric">
            <div class="metric-label">Partitioning</div>
            <div class="metric-value">Knapsack</div>
          </div>
          <div class="metric">
            <div class="metric-label">Optimization</div>
            <div class="metric-value">Machine</div>
          </div>
        </div>
      </div>
    </div>

    <div class="workspace">
      <div class="tabs">
        <button class="tab-btn active" onclick="switchTab('theory')">📚 Theory</button>
        <button class="tab-btn" onclick="switchTab('examples')">💡 Examples</button>
        <button class="tab-btn" onclick="switchTab('reference')">📖 Reference</button>
      </div>

      <!-- Theory Tab -->
      <div id="theory" class="tab-panel active">
        <div class="content-card">
          <h3>What is a Greedy Algorithm?</h3>
          <p>
            A greedy algorithm is an algorithmic approach that solves optimization problems by making a sequence of 
            choices. At each step, it makes the <strong>locally optimal choice</strong> with the hope that this choice 
            will lead to a globally optimal solution.
          </p>
          <p style="margin-top: 10px;">
            The key idea is to <strong>never look back</strong> - once a choice is made, it is never reconsidered or 
            changed, even if a better solution could be found by changing previous decisions.
          </p>
          <div class="example-highlight">
            <strong>Simple Analogy:</strong> When leaving home, if you want to reach a mountain peak and can only see the 
            steepest path from your current position, you keep climbing the steepest available slope. You'll reach <strong>a</strong> peak 
            quickly, but it might not be the highest peak.
          </div>
        </div>

        <div class="content-card">
          <h3>Greedy Choice Property</h3>
          <p>
            A problem has the <strong>greedy choice property</strong> if an optimal solution can be constructed by making 
            greedy choices. Not all optimization problems have this property!
          </p>
          <p style="margin-top: 10px;">
            For a problem to be solvable optimally by a greedy algorithm, the locally optimal choice at each step must 
            also lead to a globally optimal solution.
          </p>
          <div class="example-highlight">
            <strong>Example:</strong> The activity selection problem has the greedy choice property. Selecting the activity 
            that ends earliest always leads to an optimal solution.
          </div>
        </div>

        <div class="content-card">
          <h3>Optimal Substructure</h3>
          <p>
            A problem exhibits <strong>optimal substructure</strong> if an optimal solution contains optimal solutions to 
            subproblems. This property is necessary for both greedy and dynamic programming approaches.
          </p>
          <ul>
            <li>Breaking the problem into smaller subproblems</li>
            <li>Solving those subproblems optimally</li>
            <li>Combining solutions to get the overall optimal solution</li>
          </ul>
        </div>

        <div class="content-card">
          <h3>How Greedy Algorithms Work</h3>
          <p>A typical greedy algorithm follows this general pattern:</p>
          <div class="code-block">
Algorithm Greedy(Problem P):
  1. Sort or prepare candidates
  2. Initialize empty solution set S
  3. While solution is not complete:
     a. Make the best local choice C
     b. Add C to solution S
     c. Remove C from candidates
  4. Return solution S
          </div>
        </div>

        <div class="content-card">
          <h3>When to Use Greedy Algorithms</h3>
          <ul>
            <li>Problem has <strong>greedy choice property</strong> - locally optimal choices lead to global optimality</li>
            <li>Problem has <strong>optimal substructure</strong> - solutions build on optimal subsolutions</li>
            <li>Need a <strong>fast solution</strong> - greedy is often O(n) or O(n log n)</li>
            <li>Optimal solution is not absolutely necessary - approximations are acceptable</li>
            <li>Problem fits patterns like activity selection, huffman coding, or interval scheduling</li>
          </ul>
        </div>

        <div class="content-card">
          <h3>Limitations of Greedy Algorithms</h3>
          <ul>
            <li><strong>Not Always Optimal:</strong> Many problems don't have the greedy choice property</li>
            <li><strong>Problem Dependent:</strong> A greedy strategy that works for one problem may fail for another</li>
            <li><strong>No Backtracking:</strong> Can't reconsider earlier decisions even if they lead to dead ends</li>
            <li><strong>Proving Correctness:</strong> Often difficult to prove that a greedy approach is optimal</li>
            <li><strong>Finding the Right Criterion:</strong> The "best" local choice isn't always obvious</li>
          </ul>
        </div>

        <div class="content-card">
          <h3>Greedy vs Dynamic Programming</h3>
          <table>
            <thead>
              <tr>
                <th>Aspect</th>
                <th>Greedy Algorithm</th>
                <th>Dynamic Programming</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>Approach</strong></td>
                <td>Top-down, immediate choice</td>
                <td>Bottom-up, explore all choices</td>
              </tr>
              <tr>
                <td><strong>Optimality</strong></td>
                <td>Not always optimal</td>
                <td>Always optimal (if correctly solved)</td>
              </tr>
              <tr>
                <td><strong>Time Complexity</strong></td>
                <td>Usually O(n) to O(n log n)</td>
                <td>Usually O(n²) or higher</td>
              </tr>
              <tr>
                <td><strong>Space Complexity</strong></td>
                <td>O(n)</td>
                <td>O(n) to O(n²)</td>
              </tr>
              <tr>
                <td><strong>Backtracking</strong></td>
                <td>No, never reconsiders</td>
                <td>Yes, stores subproblem results</td>
              </tr>
              <tr>
                <td><strong>Best for</strong></td>
                <td>Fast approximations, specific problem types</td>
                <td>Optimal solutions when time permits</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Examples Tab -->
      <div id="examples" class="tab-panel">
        <div class="content-card">
          <h3>Activity Selection Problem</h3>
          <p>
            <strong>Problem:</strong> Given a set of activities with start and end times, select the maximum number of 
            non-overlapping activities.
          </p>
          <p style="margin-top: 10px;"><strong>Real-World Example:</strong> You have a conference room and many teams want to book it. Each team has a start and end time. How can you fit the maximum number of meetings without them overlapping?</p>
          
          <p style="margin-top: 12px; margin-bottom: 6px;"><strong>Greedy Strategy:</strong> Always select the activity that ends earliest.</p>
          <p style="color: #8b98b6; font-size: 0.85rem; margin-bottom: 12px;">Why? If an activity ends early, it leaves more room for other activities to happen after it!</p>

          <p style="font-size: 0.85rem; font-weight: 600; color: var(--accent-2); margin-bottom: 8px;">📋 Available Activities:</p>
          <div style="background: var(--card); padding: 10px; border-radius: 8px; font-size: 0.82rem; margin-bottom: 12px;">
            <div style="color: var(--muted); margin-bottom: 4px;">Activity 1: Start 1, End <strong style="color: var(--accent);">4</strong></div>
            <div style="color: var(--muted); margin-bottom: 4px;">Activity 2: Start 3, End <strong style="color: var(--accent);">5</strong></div>
            <div style="color: var(--muted); margin-bottom: 4px;">Activity 3: Start 0, End <strong style="color: var(--accent);">6</strong></div>
            <div style="color: var(--muted); margin-bottom: 4px;">Activity 4: Start 5, End <strong style="color: var(--accent);">7</strong></div>
            <div style="color: var(--muted); margin-bottom: 4px;">Activity 5: Start 3, End <strong style="color: var(--accent);">8</strong></div>
            <div style="color: var(--muted); margin-bottom: 4px;">Activity 6: Start 5, End <strong style="color: var(--accent);">9</strong></div>
            <div style="color: var(--muted); margin-bottom: 4px;">Activity 7: Start 6, End <strong style="color: var(--accent);">10</strong></div>
            <div style="color: var(--muted);">Activity 8: Start 8, End <strong style="color: var(--accent);">11</strong></div>
          </div>

          <p style="font-size: 0.85rem; font-weight: 600; color: var(--accent-2); margin-bottom: 8px;">Step 1️⃣ Sort by End Time</p>
          <p style="color: var(--muted); font-size: 0.82rem; margin-bottom: 12px;">Arrange all activities by when they finish (earliest to latest):</p>
          <div style="background: var(--card); padding: 10px; border-radius: 8px; font-size: 0.82rem; margin-bottom: 12px;">
            <div style="color: #86efac; margin-bottom: 3px;">✓ Activity 1: End <strong>4</strong></div>
            <div style="color: #86efac; margin-bottom: 3px;">✓ Activity 2: End <strong>5</strong></div>
            <div style="color: #86efac; margin-bottom: 3px;">✓ Activity 4: End <strong>7</strong></div>
            <div style="color: #86efac; margin-bottom: 3px;">✓ Activity 3: End <strong>6</strong></div>
            <div style="color: #86efac; margin-bottom: 3px;">✓ Activity 5: End <strong>8</strong></div>
            <div style="color: #86efac; margin-bottom: 3px;">✓ Activity 6: End <strong>9</strong></div>
            <div style="color: #86efac; margin-bottom: 3px;">✓ Activity 7: End <strong>10</strong></div>
            <div style="color: #86efac;">✓ Activity 8: End <strong>11</strong></div>
          </div>

          <p style="font-size: 0.85rem; font-weight: 600; color: var(--accent-2); margin-bottom: 8px;">Step 2️⃣ Select Activities Using Greedy Approach</p>
          
          <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); padding: 10px; border-radius: 8px; margin-bottom: 12px;">
            <div style="font-size: 0.82rem; margin-bottom: 8px;"><strong style="color: #86efac;">✓ SELECTED: Activity 1 (Time 1-4)</strong></div>
            <div style="font-size: 0.78rem; color: var(--muted);">Why? It ends earliest (at time 4). Pick it first to give plenty of time for other activities.</div>
          </div>

          <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); padding: 10px; border-radius: 8px; margin-bottom: 12px;">
            <div style="font-size: 0.82rem; margin-bottom: 8px;"><strong style="color: #86efac;">✓ SELECTED: Activity 4 (Time 5-7)</strong></div>
            <div style="font-size: 0.78rem; color: var(--muted);">✗ Skip Activity 2 (3-5) - overlaps with Activity 1 ❌<br/>✗ Skip Activity 3 (0-6) - overlaps with Activity 1 ❌<br/>✓ Activity 4 starts at 5 (right after Activity 1 ends) - No overlap! ✓</div>
          </div>

          <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); padding: 10px; border-radius: 8px; margin-bottom: 12px;">
            <div style="font-size: 0.82rem; margin-bottom: 8px;"><strong style="color: #86efac;">✓ SELECTED: Activity 8 (Time 8-11)</strong></div>
            <div style="font-size: 0.78rem; color: var(--muted);">✗ Skip Activity 5 (3-8) - overlaps with previous selections ❌<br/>✗ Skip Activity 6 (5-9) - overlaps with Activity 4 ❌<br/>✗ Skip Activity 7 (6-10) - overlaps with Activity 4 ❌<br/>✓ Activity 8 starts at 8 (right after Activity 4 ends) - No overlap! ✓</div>
          </div>

          <p style="font-size: 0.85rem; font-weight: 600; color: var(--accent-2); margin-bottom: 8px;">Final Result</p>
          <div style="background: rgba(245, 158, 11, 0.1); border-left: 3px solid var(--accent); padding: 12px; border-radius: 8px;">
            <div style="font-size: 0.82rem; color: var(--muted);"><strong style="color: var(--accent-2);">Selected Activities:</strong> Activity 1, Activity 4, Activity 8</div>
            <div style="font-size: 0.82rem; color: var(--muted); margin-top: 6px;"><strong style="color: var(--accent-2);">Total Activities Scheduled:</strong> <strong style="color: #86efac;">3 activities</strong> (Maximum Possible!)</div>
          </div>

          <p style="margin-top: 12px;"><strong>Why it works:</strong> By choosing the activity that ends earliest, we leave the maximum amount of time for future activities. This greedy choice consistently gives us the best result!</p>
        </div>

        <div class="content-card">
          <h3>Huffman Coding</h3>
          <p>
            <strong>Problem:</strong> Create an optimal binary code for characters to minimize total encoded message length.
          </p>
          <p style="margin-top: 10px;"><strong>Greedy Strategy:</strong> Repeatedly combine the two least frequent characters.</p>
          <div class="example-highlight">
            <strong>Process:</strong>
            <ol style="padding-left: 20px; margin-top: 10px;">
              <li>Count frequency of each character</li>
              <li>Create leaf node for each character</li>
              <li>While more than one node remains: combine two nodes with minimum frequency</li>
              <li>Assign codes based on tree structure (left=0, right=1)</li>
            </ol>
          </div>
          <p style="margin-top: 10px;"><strong>Result:</strong> Most frequent characters get shorter codes, minimizing total bits.</p>
        </div>

        <div class="content-card">
          <h3>0/1 Knapsack (Why Greedy Fails)</h3>
          <p>
            <strong>Problem:</strong> You have a backpack that can hold 50 kg. There are items with different values and weights. You can either take an item or leave it (no partial items). What's the maximum value you can carry?
          </p>
          <p style="margin-top: 10px;"><strong>Real-World Example:</strong> Packing a suitcase for travel. You need to fit the most valuable items without exceeding weight limit.</p>

          <p style="margin-top: 12px; margin-bottom: 6px;"><strong>Greedy Strategy (That Fails!):</strong> Always pick item with highest value-to-weight ratio first.</p>
          <p style="color: #8b98b6; font-size: 0.85rem; margin-bottom: 12px;">Why this seems logical? Items with high value per kg seem most efficient. But this doesn't work for 0/1 Knapsack!</p>

          <p style="font-size: 0.85rem; font-weight: 600; color: var(--accent-2); margin-bottom: 8px;">📦 Available Items (sorted by ratio - highest first):</p>
          <div style="background: var(--card); padding: 10px; border-radius: 8px; font-size: 0.82rem; margin-bottom: 12px;">
            <div style="color: var(--muted); margin-bottom: 6px; padding-bottom: 6px; border-bottom: 1px solid var(--border);">
              <strong style="color: var(--accent);">Item 1:</strong> Value = <strong>60</strong> | Weight = <strong>10 kg</strong> | Ratio = <strong style="color: #fbbf24;">6.0</strong> ⭐ Best Ratio
            </div>
            <div style="color: var(--muted); margin-bottom: 6px; padding-bottom: 6px; border-bottom: 1px solid var(--border);">
              <strong style="color: var(--accent);">Item 2:</strong> Value = <strong>100</strong> | Weight = <strong>20 kg</strong> | Ratio = <strong style="color: #fbbf24;">5.0</strong>
            </div>
            <div style="color: var(--muted);">
              <strong style="color: var(--accent);">Item 3:</strong> Value = <strong>120</strong> | Weight = <strong>30 kg</strong> | Ratio = <strong style="color: #fbbf24;">4.0</strong>
            </div>
          </div>

          <p style="font-size: 0.85rem; font-weight: 600; color: var(--danger); margin-bottom: 8px;">❌ Greedy Approach (Fails!)</p>
          <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); padding: 10px; border-radius: 8px; margin-bottom: 12px;">
            <div style="font-size: 0.82rem; margin-bottom: 6px;"><strong style="color: #fca5a5;">Step 1: Pick Item 1 (Ratio 6.0)</strong></div>
            <div style="font-size: 0.78rem; color: var(--muted); margin-left: 12px; margin-bottom: 6px;">✓ Added to backpack: Value = 60 | Weight = 10 kg</div>
            <div style="font-size: 0.78rem; color: var(--muted); margin-left: 12px; margin-bottom: 8px;">Capacity remaining: 50 - 10 = <strong>40 kg</strong></div>
            
            <div style="font-size: 0.82rem; margin-bottom: 6px;"><strong style="color: #fca5a5;">Step 2: Pick Item 2 (Ratio 5.0)</strong></div>
            <div style="font-size: 0.78rem; color: var(--muted); margin-left: 12px; margin-bottom: 6px;">✓ Added to backpack: Value = 60 + 100 = <strong>160</strong> | Weight = 10 + 20 = <strong>30 kg</strong></div>
            <div style="font-size: 0.78rem; color: var(--muted); margin-left: 12px; margin-bottom: 8px;">Capacity remaining: 50 - 30 = <strong>20 kg</strong></div>
            
            <div style="font-size: 0.82rem; margin-bottom: 6px;"><strong style="color: #fca5a5;">Step 3: Try Item 3 (Ratio 4.0)</strong></div>
            <div style="font-size: 0.78rem; color: var(--muted); margin-left: 12px; margin-bottom: 6px;">✗ Can't fit! Item 3 weighs 30 kg but only 20 kg space left.</div>
            <div style="font-size: 0.78rem; color: var(--muted); margin-left: 12px;">Stop here (no more items fit).</div>
          </div>

          <div style="background: rgba(239, 68, 68, 0.15); border-left: 3px solid var(--danger); padding: 12px; border-radius: 8px; margin-bottom: 12px;">
            <div style="font-size: 0.82rem; color: var(--muted);"><strong style="color: #fca5a5;">Greedy Result:</strong> Items 1 + 2</div>
            <div style="font-size: 0.82rem; color: var(--muted); margin-top: 6px;"><strong style="color: #fca5a5;">Total Value:</strong> 60 + 100 = <strong>160</strong></div>
            <div style="font-size: 0.82rem; color: var(--muted);"><strong style="color: #fca5a5;">Total Weight:</strong> 10 + 20 = <strong>30 kg (out of 50 kg)</strong></div>
          </div>

          <p style="font-size: 0.85rem; font-weight: 600; color: var(--good); margin-bottom: 8px;">✓ Better Solution (Not Greedy!)</p>
          <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); padding: 10px; border-radius: 8px; margin-bottom: 12px;">
            <div style="font-size: 0.82rem; margin-bottom: 6px;"><strong style="color: #86efac;">What if we think differently and skip Item 1?</strong></div>
            <div style="font-size: 0.82rem; margin-bottom: 6px;"><strong style="color: #86efac;">Step 1: Pick Item 2</strong></div>
            <div style="font-size: 0.78rem; color: var(--muted); margin-left: 12px; margin-bottom: 8px;">✓ Value = 100 | Weight = 20 kg | Remaining: 30 kg</div>
            
            <div style="font-size: 0.82rem; margin-bottom: 6px;"><strong style="color: #86efac;">Step 2: Pick Item 3</strong></div>
            <div style="font-size: 0.78rem; color: var(--muted); margin-left: 12px; margin-bottom: 8px;">✓ Value = 100 + 120 = <strong>220</strong> | Weight = 20 + 30 = <strong>50 kg</strong> | Remaining: 0 kg</div>
            
            <div style="font-size: 0.82rem; margin-bottom: 6px;"><strong style="color: #86efac;">Backpack is perfectly full!</strong></div>
          </div>

          <div style="background: rgba(34, 197, 94, 0.15); border-left: 3px solid var(--good); padding: 12px; border-radius: 8px; margin-bottom: 12px;">
            <div style="font-size: 0.82rem; color: var(--muted);"><strong style="color: #86efac;">Better Result:</strong> Items 2 + 3</div>
            <div style="font-size: 0.82rem; color: var(--muted); margin-top: 6px;"><strong style="color: #86efac;">Total Value:</strong> 100 + 120 = <strong>220</strong> ⭐ 60 more value!</div>
            <div style="font-size: 0.82rem; color: var(--muted);"><strong style="color: #86efac;">Total Weight:</strong> 20 + 30 = <strong>50 kg (using full capacity)</strong></div>
          </div>

          <p style="margin-top: 12px;"><strong>❌ Why Greedy Fails:</strong> The knapsack problem doesn't have the "greedy choice property". Picking the item with the best ratio at each step doesn't lead to the overall best solution. You need to consider which <strong>combination</strong> of items is best, not just individual items.</p>
          
          <p style="margin-top: 10px;"><strong>✓ Solution:</strong> Use <strong>Dynamic Programming</strong> instead! It explores all possible combinations and guarantees finding the optimal solution.</p>
        </div>

        <div class="content-card">
          <h3>Coin Change Problem (Greedy Works Sometimes)</h3>
          <p>
            <strong>Problem:</strong> Make a specific amount of money using the minimum number of coins.
          </p>
          <p style="margin-top: 10px;"><strong>Real-World Example:</strong> A cashier needs to give change of 30 cents using the fewest coins possible.</p>

          <p style="margin-top: 12px; margin-bottom: 6px;"><strong>Greedy Strategy:</strong> Always use the largest coin that doesn't exceed the remaining amount.</p>

          <p style="font-size: 0.85rem; font-weight: 600; color: var(--accent-2); margin-bottom: 8px;">✓ Example 1: US Coins (Greedy Works!)</p>
          <div style="background: var(--card); padding: 10px; border-radius: 8px; font-size: 0.82rem; margin-bottom: 12px;">
            <div style="color: var(--muted); margin-bottom: 6px; padding-bottom: 6px; border-bottom: 1px solid var(--border);">
              <strong style="color: var(--accent);">Available Coins:</strong> 25¢, 10¢, 5¢, 1¢
            </div>
            <div style="color: var(--muted); margin-bottom: 8px;">
              <strong style="color: var(--accent);">Goal:</strong> Make 30 cents with minimum coins
            </div>

            <div style="color: var(--muted); margin-bottom: 6px;">
              <strong style="color: #fbbf24;">Step 1:</strong> Use largest coin (25¢)
            </div>
            <div style="font-size: 0.78rem; color: var(--muted); margin-left: 12px; margin-bottom: 8px;">
              ✓ Use one 25¢ coin | Remaining: 30 - 25 = <strong>5¢</strong>
            </div>

            <div style="color: var(--muted); margin-bottom: 6px;">
              <strong style="color: #fbbf24;">Step 2:</strong> Next largest coin is 10¢ (too big, skip)
            </div>
            <div style="font-size: 0.78rem; color: var(--muted); margin-left: 12px; margin-bottom: 8px;">
              ✗ 10¢ > 5¢ remaining, can't use it
            </div>

            <div style="color: var(--muted); margin-bottom: 6px;">
              <strong style="color: #fbbf24;">Step 3:</strong> Use 5¢
            </div>
            <div style="font-size: 0.78rem; color: var(--muted); margin-left: 12px; margin-bottom: 8px;">
              ✓ Use one 5¢ coin | Remaining: 5 - 5 = <strong>0¢</strong> ✓
            </div>
          </div>

          <div style="background: rgba(34, 197, 94, 0.15); border-left: 3px solid var(--good); padding: 12px; border-radius: 8px; margin-bottom: 12px;">
            <div style="font-size: 0.82rem; color: var(--muted);"><strong style="color: #86efac;">Greedy Result:</strong> 1 × 25¢ + 1 × 5¢</div>
            <div style="font-size: 0.82rem; color: var(--muted); margin-top: 6px;"><strong style="color: #86efac;">Total Coins:</strong> <strong>2 coins</strong> (OPTIMAL!) ✓</div>
          </div>

          <p style="font-size: 0.85rem; font-weight: 600; color: var(--danger); margin-top: 12px; margin-bottom: 8px;">✗ Example 2: Custom Coins (Greedy Fails!)</p>
          <div style="background: var(--card); padding: 10px; border-radius: 8px; font-size: 0.82rem; margin-bottom: 12px;">
            <div style="color: var(--muted); margin-bottom: 6px; padding-bottom: 6px; border-bottom: 1px solid var(--border);">
              <strong style="color: var(--accent);">Available Coins:</strong> 4, 3, 1 (custom denominations)
            </div>
            <div style="color: var(--muted); margin-bottom: 8px;">
              <strong style="color: var(--accent);">Goal:</strong> Make 6 with minimum coins
            </div>

            <div style="color: #fca5a5; margin-bottom: 6px;">
              <strong>Greedy Approach (Fails!):</strong>
            </div>
            <div style="color: var(--muted); margin-bottom: 6px; margin-left: 12px;">
              Step 1: Use 4 | Remaining: 6 - 4 = 2
            </div>
            <div style="color: var(--muted); margin-bottom: 6px; margin-left: 12px;">
              Step 2: Can't use 4 (too big), use 3? No, 3 > 2. Use 1 twice
            </div>
            <div style="color: #fca5a5; margin-left: 12px;">
              Total: 4 + 1 + 1 = <strong>3 coins</strong> ❌
            </div>

            <div style="color: #86efac; margin-top: 8px; margin-bottom: 6px;">
              <strong>Better Solution:</strong>
            </div>
            <div style="color: var(--muted); margin-left: 12px;">
              Use 3 + 3 = <strong>2 coins</strong> ✓ (OPTIMAL!)
            </div>
          </div>

          <p style="margin-top: 12px;"><strong>Key Takeaway:</strong> Greedy works perfectly for <strong>real-world currency systems</strong> (US, Euro, etc.) because they're specifically designed that way. But for <strong>custom coin systems</strong>, greedy can fail. When in doubt, use <strong>Dynamic Programming</strong> to guarantee the optimal solution.</p>
        </div>

        <div class="content-card">
          <h3>Fractional Knapsack</h3>
          <p>
            <strong>Problem:</strong> Select items (can take fractions/portions of items) to maximize value within weight capacity.
          </p>
          <p style="margin-top: 10px;"><strong>Real-World Example:</strong> A liquid tank fill-up where you can pour partial amounts (unlike solid items).</p>

          <p style="margin-top: 12px; margin-bottom: 6px;"><strong>Greedy Strategy (Works!):</strong> Always pick item with highest value-to-weight ratio first, take as much as fits.</p>

          <p style="font-size: 0.85rem; font-weight: 600; color: var(--accent-2); margin-bottom: 8px;">📦 Available Items (sorted by ratio):</p>
          <div style="background: var(--card); padding: 10px; border-radius: 8px; font-size: 0.82rem; margin-bottom: 12px;">
            <div style="color: var(--muted); margin-bottom: 6px; padding-bottom: 6px; border-bottom: 1px solid var(--border);">
              <strong style="color: var(--accent);">Item 1:</strong> Value = 60 | Weight = 10 | Ratio = <strong style="color: #fbbf24;">6.0</strong> ⭐ Best
            </div>
            <div style="color: var(--muted); margin-bottom: 6px; padding-bottom: 6px; border-bottom: 1px solid var(--border);">
              <strong style="color: var(--accent);">Item 2:</strong> Value = 100 | Weight = 20 | Ratio = <strong style="color: #fbbf24;">5.0</strong>
            </div>
            <div style="color: var(--muted);">
              <strong style="color: var(--accent);">Item 3:</strong> Value = 120 | Weight = 30 | Ratio = <strong style="color: #fbbf24;">4.0</strong>
            </div>
          </div>

          <p style="font-size: 0.85rem; margin-bottom: 8px;"><strong style="color: var(--accent-2);">Greedy Selection Process:</strong></p>
          <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); padding: 10px; border-radius: 8px; margin-bottom: 12px;">
            <div style="font-size: 0.82rem; margin-bottom: 6px;"><strong style="color: #86efac;">Step 1: Take Item 1 (Ratio 6.0)</strong></div>
            <div style="font-size: 0.78rem; color: var(--muted); margin-left: 12px; margin-bottom: 8px;">
              ✓ Take <strong>ALL 10 kg</strong> of Item 1 | Value gained: 60 | Capacity used: 10 | Remaining: 40 kg
            </div>

            <div style="font-size: 0.82rem; margin-bottom: 6px;"><strong style="color: #86efac;">Step 2: Take Item 2 (Ratio 5.0)</strong></div>
            <div style="font-size: 0.78rem; color: var(--muted); margin-left: 12px; margin-bottom: 8px;">
              ✓ Take <strong>ALL 20 kg</strong> of Item 2 | Value gained: 100 | Total value: 60 + 100 = 160 | Capacity used: 30 | Remaining: 20 kg
            </div>

            <div style="font-size: 0.82rem; margin-bottom: 6px;"><strong style="color: #86efac;">Step 3: Take Item 3 (Ratio 4.0)</strong></div>
            <div style="font-size: 0.78rem; color: var(--muted); margin-left: 12px; margin-bottom: 8px;">
              ✓ Can't take all 30 kg (only 20 kg space) → Take <strong>PARTIAL: 20 kg out of 30 kg</strong>
            </div>
            <div style="font-size: 0.78rem; color: var(--muted); margin-left: 12px; margin-bottom: 8px;">
              Value from 20 kg of Item 3 = (20/30) × 120 = <strong>80</strong> | Total value: 160 + 80 = <strong>220</strong> | Capacity used: 50 | Remaining: 0 kg ✓
            </div>
          </div>

          <div style="background: rgba(34, 197, 94, 0.15); border-left: 3px solid var(--good); padding: 12px; border-radius: 8px;">
            <div style="font-size: 0.82rem; color: var(--muted);"><strong style="color: #86efac;">Greedy Result (Optimal!):</strong></div>
            <div style="font-size: 0.82rem; color: var(--muted); margin-top: 6px;">
              ✓ 10 kg Item 1 + 20 kg Item 2 + 20 kg of Item 3 (2/3 of it)
            </div>
            <div style="font-size: 0.82rem; color: var(--muted); margin-top: 6px;">
              <strong style="color: #86efac;">Total Value:</strong> 60 + 100 + 80 = <strong>240</strong> ⭐
            </div>
            <div style="font-size: 0.82rem; color: var(--muted);">
              <strong style="color: #86efac;">Total Weight:</strong> 10 + 20 + 20 = <strong>50 kg (perfectly full!)</strong>
            </div>
          </div>

          <p style="margin-top: 12px;"><strong>✓ Why Greedy Works Here:</strong> In fractional knapsack, you can divide items. This means the greedy choice property holds: selecting the highest ratio item first is always optimal because you can take partial amounts. This is different from 0/1 knapsack where you must take entire items!</p>
        </div>

        <div class="content-card">
          <h3>Dijkstra's Shortest Path</h3>
          <p>
            <strong>Problem:</strong> Find shortest path from source to destination in a weighted graph.
          </p>
          <p style="margin-top: 10px;"><strong>Greedy Strategy:</strong> Always expand the node with minimum distance from source.</p>
          <div class="example-highlight">
            <strong>Process:</strong> Starting from source, repeatedly visit the unvisited node closest to the source. 
            This greedily explores the graph, always going to the nearest unvisited node first.
          </div>
          <p style="margin-top: 10px;"><strong>Why it works:</strong> Once a node's shortest distance is determined, it can 
          never be improved because all edge weights are non-negative.</p>
        </div>
      </div>

      <!-- Reference Tab -->
      <div id="reference" class="tab-panel">
        <div class="content-card">
          <h3>Advantages of Greedy Algorithms</h3>
          <div class="pros-cons">
            <div class="pros">
              <h4>✓ Advantages</h4>
              <ul>
                <li>Very fast - typically O(n) or O(n log n)</li>
                <li>Simple to understand and implement</li>
                <li>Low memory requirements</li>
                <li>Good for real-time systems</li>
                <li>Often provides good approximations</li>
                <li>Intuitive to analyze</li>
              </ul>
            </div>
            <div class="cons">
              <h4>✗ Disadvantages</h4>
              <ul>
                <li>Not always optimal</li>
                <li>Proof of optimality is hard</li>
                <li>No backtracking capability</li>
                <li>May get stuck at local maxima</li>
                <li>Problem-dependent correctness</li>
                <li>Difficult to modify for constraints</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="content-card">
          <h3>Classical Greedy Algorithm Problems</h3>
          <ul>
            <li><strong>Activity Selection:</strong> Select maximum non-overlapping activities ✓ Optimal</li>
            <li><strong>Huffman Coding:</strong> Optimal prefix-free code ✓ Optimal</li>
            <li><strong>0/1 Knapsack:</strong> Select items with value limit ✗ NOT optimal (use DP)</li>
            <li><strong>Fractional Knapsack:</strong> Select fractional items ✓ Optimal</li>
            <li><strong>Coin Change:</strong> Minimum coins for amount (depends on coin set)</li>
            <li><strong>Dijkstra's Shortest Path:</strong> Shortest path in weighted graph ✓ Optimal</li>
            <li><strong>Prim's MST:</strong> Minimum spanning tree ✓ Optimal</li>
            <li><strong>Kruskal's MST:</strong> Minimum spanning tree ✓ Optimal</li>
            <li><strong>Job Sequencing:</strong> Schedule jobs for maximum profit ✓ Optimal</li>
          </ul>
        </div>

        <div class="content-card">
          <h3>Key Points to Remember</h3>
          <ul>
            <li>Greedy algorithms make <strong>locally optimal choices</strong> at each step</li>
            <li>They <strong>never backtrack</strong> or reconsider previous decisions</li>
            <li>Problems must have <strong>greedy choice property</strong> and <strong>optimal substructure</strong></li>
            <li>Greedy works well for some problems (Activity Selection, Huffman) but fails for others (0/1 Knapsack)</li>
            <li>When greedy fails, consider <strong>dynamic programming</strong> instead</li>
            <li>Typical time complexity is <strong>O(n) to O(n log n)</strong> - very fast!</li>
            <li>Always <strong>prove correctness</strong> before assuming greedy works for a problem</li>
          </ul>
        </div>

        <div class="content-card">
          <h3>Decision Tree: When to Use Approach</h3>
          <ol>
            <li><strong>Is speed critical?</strong> → Consider greedy (O(n) to O(n log n))</li>
            <li><strong>Does problem have greedy choice property?</strong> → Can use greedy ✓</li>
            <li><strong>Does greedy produce correct results on test cases?</strong> → Use greedy ✓</li>
            <li><strong>No greedy choice property?</strong> → Use dynamic programming or exact algorithms</li>
            <li><strong>Problem is NP-Hard and solution needed fast?</strong> → Use approximation greedy</li>
          </ol>
        </div>

        <div class="content-card">
          <h3>Quick Reference: Common Greedy Strategies</h3>
          <div class="concept-grid">
            <div class="concept-box">
              <h4>Selection Strategy</h4>
              <p>Choose item with best immediate value. Example: Activity ending earliest.</p>
            </div>
            <div class="concept-box">
              <h4>Ordering Strategy</h4>
              <p>Process items in specific order. Example: Sort by value or deadline.</p>
            </div>
            <div class="concept-box">
              <h4>Pruning Strategy</h4>
              <p>Remove items that can't be used. Example: Skip overlapping activities.</p>
            </div>
            <div class="concept-box">
              <h4>Exchange Strategy</h4>
              <p>Swap items to improve solution. Example: Prim's MST algorithm.</p>
            </div>
          </div>
        </div>

        <div class="content-card">
          <h3>Summary Table</h3>
          <table>
            <thead>
              <tr>
                <th>Algorithm</th>
                <th>Time</th>
                <th>Space</th>
                <th>Optimal?</th>
                <th>When to Use</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Activity Selection</td>
                <td>O(n log n)</td>
                <td>O(n)</td>
                <td>✓ Yes</td>
                <td>Non-overlapping activities</td>
              </tr>
              <tr>
                <td>Huffman Coding</td>
                <td>O(n log n)</td>
                <td>O(n)</td>
                <td>✓ Yes</td>
                <td>Optimal prefix codes</td>
              </tr>
              <tr>
                <td>Dijkstra's Path</td>
                <td>O((V+E) log V)</td>
                <td>O(V)</td>
                <td>✓ Yes</td>
                <td>Shortest path (non-neg)</td>
              </tr>
              <tr>
                <td>Greedy Coin Change</td>
                <td>O(n)</td>
                <td>O(1)</td>
                <td>✗ Maybe</td>
                <td>Coin systems only</td>
              </tr>
              <tr>
                <td>Fractional Knapsack</td>
                <td>O(n log n)</td>
                <td>O(n)</td>
                <td>✓ Yes</td>
                <td>Items can be fractioned</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
    function switchTab(tabName) {
      // Hide all panels
      const panels = document.querySelectorAll('.tab-panel');
      panels.forEach(panel => panel.classList.remove('active'));
      
      // Remove active class from all buttons
      const buttons = document.querySelectorAll('.tab-btn');
      buttons.forEach(button => button.classList.remove('active'));
      
      // Show selected panel
      document.getElementById(tabName).classList.add('active');
      
      // Add active class to clicked button
      event.target.classList.add('active');
    }
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