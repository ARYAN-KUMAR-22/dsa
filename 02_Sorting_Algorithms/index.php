<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
     body { font-family: 'Inter', sans-serif; padding: 50px; color: #f8fafc; background: #030712; margin: 0; }
     body.light { color: #0f172a; background: #f8fafc; }
     .container { max-width: 1100px; margin: 0 auto; }
     
     .header-tag {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 999px;
        background: rgba(56, 189, 248, 0.1);
        color: #38bdf8;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        margin-bottom: 16px;
     }
     body.light .header-tag {
        background: rgba(2, 132, 199, 0.1);
        color: #0284c7;
     }

     h1 { color: #f8fafc; margin-bottom: 12px; font-size: 2.8rem; margin-top: 0; }
     body.light h1 { color: #0f172a; }
     p.subtitle { font-size: 1.15rem; color: #94a3b8; margin-bottom: 40px; line-height: 1.7; }
     body.light p.subtitle { color: #52637a; }
     
     .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
     }

     .card {
        background: #111827;
        border: 1px solid rgba(148,163,184,0.15);
        border-radius: 16px;
        padding: 24px;
        text-decoration: none;
        color: inherit;
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        display: block;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
     }
     body.light .card { background: #ffffff; border-color: rgba(15,23,42,0.15); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }

     .card:hover { border-color: #38bdf8; transform: translateY(-4px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.2); }
     body.light .card:hover { border-color: #0284c7; }

     .card h2 { margin: 0 0 12px 0; font-size: 1.3rem; color: #e2e8f0; }
     body.light .card h2 { color: #1e293b; }
     .card p { margin: 0; font-size: 0.95rem; color: #94a3b8; line-height: 1.5; }
     body.light .card p { color: #64748b; }
  </style>
</head>
<body>
  <script>
    if (localStorage.getItem('theme') === 'light' || (window.parent && window.parent.localStorage.getItem('theme') === 'light')) {
        document.body.classList.add('light');
    }
  </script>
  <div class="container">
     <div class="header-tag">Module Selection</div>
     <h1>Sorting Algorithms Hub</h1>
     <p class="subtitle">Select an algorithm below to study its properties, memory complexities, and launch into the interactive timeline visualizer.</p>
     
     <div class="grid">
        <a href="theory.php?algo=bubble" class="card">
            <h2>Bubble Sort</h2>
            <p>Exchange adjacent elements until the array is sorted.</p>
        </a>
        <a href="theory.php?algo=insertion" class="card">
            <h2>Insertion Sort</h2>
            <p>Simulates sorting playing cards sequentially.</p>
        </a>
        <a href="theory.php?algo=selection" class="card">
            <h2>Selection Sort</h2>
            <p>Find the minimum element and swap it into position.</p>
        </a>
        <a href="theory.php?algo=quick" class="card">
            <h2>Quick Sort</h2>
            <p>Divide and conquer via an optimal pivot.</p>
        </a>
        <a href="theory.php?algo=merge" class="card">
            <h2>Merge Sort</h2>
            <p>Recursively divide, sort, and merge array blocks.</p>
        </a>
        <a href="theory.php?algo=count" class="card">
            <h2>Count Sort</h2>
            <p>Non-comparison based tallying approach.</p>
        </a>
        <a href="theory.php?algo=bucket" class="card">
            <h2>Bucket Sort</h2>
            <p>Map uniformly distributed items into localized buckets.</p>
        </a>
        <a href="theory.php?algo=radix" class="card">
            <h2>Radix Sort</h2>
            <p>Digit by digit sequence mapping iteration.</p>
        </a>
        <a href="theory.php?algo=shell" class="card">
            <h2>Shell Sort</h2>
            <p>Diminishing gap sort based on Insertion sort.</p>
        </a>
     </div>
  </div>
</body>
</html>
