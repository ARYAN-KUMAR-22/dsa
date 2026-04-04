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
  <meta charset="UTF-8">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
     body { font-family: 'Inter', sans-serif; padding: 50px; color: #f8fafc; background: #030712; margin: 0; }
     body.light { color: #0f172a; background: #f8fafc; }
     .container { max-width: 800px; margin: 0 auto; background: #111827; padding: 50px; border-radius: 20px; border: 1px solid rgba(148,163,184,0.15); box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
     body.light .container { background: #ffffff; border-color: rgba(15,23,42,0.15); box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
     h1 { color: #38bdf8; margin-bottom: 24px; font-size: 2.8rem; margin-top: 0; }
     body.light h1 { color: #0284c7; }
     p { line-height: 1.8; font-size: 1.15rem; margin-bottom: 40px; color: #94a3b8; }
     body.light p { color: #52637a; }
     .metrics { display: flex; gap: 20px; margin-bottom: 40px; }
     .metric { background: #1f2937; padding: 20px 24px; border-radius: 12px; border: 1px solid rgba(148,163,184,0.1); flex: 1; }
     body.light .metric { background: #f1f5f9; }
     .metric span { display: block; font-size: 0.85rem; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.05em; margin-bottom: 8px; font-weight: 600; }
     body.light .metric span { color: #64748b; }
     .metric strong { font-size: 1.4rem; }
     .btn { display: inline-block; padding: 16px 32px; background: #38bdf8; color: #fff; text-decoration: none; border-radius: 12px; font-weight: 600; font-size: 1.1rem; transition: transform 0.2s, box-shadow 0.2s; }
     body.light .btn { background: #0284c7; }
     .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(56, 189, 248, 0.4); }
     .header-tag { display: inline-block; padding: 6px 12px; border-radius: 999px; background: rgba(56, 189, 248, 0.1); color: #38bdf8; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 16px; }
     body.light .header-tag { background: rgba(2, 132, 199, 0.1); color: #0284c7; }
  </style>
</head>
<body>
  <script>
    if (localStorage.getItem('theme') === 'light' || (window.parent && window.parent.localStorage.getItem('theme') === 'light')) {
        document.body.classList.add('light');
    }
  </script>
  <div class="container">
     <div class="header-tag">Algorithm Theory</div>
     <h1>Intro to Hashing</h1>
     <p>Hashing maps large datasets to a smaller array (Hash Table) using a mathematical Hash Function. It provides extremely fast average-case computational lookup, insertion, and deletion by assigning static keys to unique indexes array structures directly in native memory bounds, scaling massively regardless of elements inserted.</p>
     <div class="metrics">
        <div class="metric">
           <span>Time Complexity</span>
           <strong>O(1) Avg</strong>
        </div>
        <div class="metric">
           <span>Space Complexity</span>
           <strong>O(N)</strong>
        </div>
     </div>
     <a href="intro_to_hashing.php" target="_top" class="btn">Launch Interactive Visualizer &rarr;</a>
  </div>
</body>
</html>
