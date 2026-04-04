<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AlgoLens | Workspace</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet" />
  <style>
    :root {
      --bg: #030712;
      --surface: #111827;
      --card: #1f2937;
      --primary: #38bdf8;
      --border: rgba(148, 163, 184, 0.15);
      --text: #f8fafc;
      --muted: #94a3b8;
      --sidebar-width: 320px;
      --nav-height: 64px;
      --hover: rgba(56, 189, 248, 0.1);
    }

    body.light-mode {
      --bg: #f8fafc;
      --surface: #ffffff;
      --card: #f1f5f9;
      --primary: #0284c7;
      --border: rgba(15, 23, 42, 0.15);
      --text: #0f172a;
      --muted: #64748b;
      --hover: rgba(2, 132, 199, 0.08);
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', sans-serif;
      color: var(--text);
      background: var(--bg);
      display: flex;
      flex-direction: column;
      height: 100vh;
      overflow: hidden;
    }

    /* Navbar */
    .navbar {
      height: var(--nav-height);
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 24px;
      flex-shrink: 0;
      z-index: 10;
    }
    .brand {
      display: flex;
      align-items: center;
      gap: 12px;
      font-weight: 800;
      font-size: 1.2rem;
    }
    .brand img {
      width: 32px;
      height: 32px;
      border-radius: 8px;
    }
    .brand span.workspace {
      color: var(--muted);
      font-weight: 400;
    }
    .nav-actions {
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .btn {
      padding: 8px 16px;
      border-radius: 8px;
      border: 1px solid var(--border);
      background: transparent;
      color: var(--text);
      font-weight: 600;
      font-size: 0.85rem;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.2s;
    }
    .btn:hover { background: var(--hover); }
    .btn-danger { color: #fca5a5; border-color: rgba(220, 38, 38, 0.3); }
    .btn-danger:hover { background: rgba(220, 38, 38, 0.1); }
    body.light-mode .btn-danger { color: #ef4444; }

    /* Workspace */
    .workspace {
      display: flex;
      flex-grow: 1;
      height: calc(100vh - var(--nav-height));
      overflow: hidden;
    }

    /* Sidebar */
    .sidebar {
      width: var(--sidebar-width);
      background: var(--surface);
      border-right: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      overflow-y: auto;
      padding: 20px 0;
    }
    .sidebar-category {
      margin-bottom: 24px;
    }
    .category-title {
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: var(--muted);
      font-weight: 700;
      padding: 0 24px;
      margin-bottom: 8px;
    }
    .nav-item {
      display: block;
      padding: 10px 24px;
      color: var(--text);
      text-decoration: none;
      font-size: 0.95rem;
      border-left: 3px solid transparent;
      transition: all 0.2s;
    }
    .nav-item:hover {
      background: var(--hover);
    }
    .nav-item.active {
      background: var(--hover);
      border-left-color: var(--primary);
      color: var(--primary);
      font-weight: 600;
    }

    /* Main Content */
    .content-area {
      flex-grow: 1;
      position: relative;
      background: var(--bg);
    }
    #viewer {
      width: 100%;
      height: 100%;
      border: none;
      display: block;
      background: #fff; /* White background internally for legacy interactives */
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .workspace { flex-direction: column; }
      .sidebar { width: 100%; height: 30%; border-right: none; border-bottom: 1px solid var(--border); }
      .content-area { height: 70%; }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <header class="navbar">
    <div class="brand">
      <img src="assets/platform_logo.png" alt="Logo">
      <div>AlgoLens <span class="workspace">Workspace</span></div>
    </div>
    <div class="nav-actions">
      <span style="color: var(--muted); font-size: 0.85rem; margin-right: 10px; display: none;">Hi, <?= htmlspecialchars($_SESSION['username']) ?></span>
      <button class="btn" id="themeToggle" aria-label="Toggle Theme">☀️</button>
      <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <a href="admin.php" class="btn" style="color: #86efac; border-color: rgba(34, 197, 94, 0.3);">Admin Panel</a>
      <?php endif; ?>
      <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
  </header>

  <!-- Workspace Split Layout -->
  <div class="workspace">
    <!-- Left Sidebar Menu -->
    <aside class="sidebar" id="sidebarMenu">
      
      <div class="sidebar-category">
        <div class="category-title">01. Basics & Tools</div>
        <a href="01_Basics_and_Tools/asymptotic_notation.html" class="nav-item active">Asymptotic Notation</a>
        <a href="01_Basics_and_Tools/recurrence_relation.html" class="nav-item">Recurrence Relation</a>
        <a href="01_Basics_and_Tools/stl_containers_cpp.html" class="nav-item">STL Containers</a>
        <a href="01_Basics_and_Tools/code_visualizer.html" class="nav-item">Code Visualizer</a>
      </div>

      <div class="sidebar-category">
        <div class="category-title">02. Sorting Algorithms</div>
        <a href="02_Sorting_Algorithms/bubble_sort_animation.html" class="nav-item">Bubble Sort</a>
        <a href="02_Sorting_Algorithms/insertion_sort_animation.html" class="nav-item">Insertion Sort</a>
        <a href="02_Sorting_Algorithms/selection_sort_animation.html" class="nav-item">Selection Sort</a>
        <a href="02_Sorting_Algorithms/quick_sort_animation.html" class="nav-item">Quick Sort</a>
        <a href="02_Sorting_Algorithms/merge_sort_animation.html" class="nav-item">Merge Sort</a>
        <a href="02_Sorting_Algorithms/count_sort_animation.html" class="nav-item">Count Sort</a>
        <a href="02_Sorting_Algorithms/bucket_sort_animation.html" class="nav-item">Bucket Sort</a>
        <a href="02_Sorting_Algorithms/radix_sort_animation.html" class="nav-item">Radix Sort</a>
        <a href="02_Sorting_Algorithms/shell_sort_animation.html" class="nav-item">Shell Sort</a>
      </div>

      <div class="sidebar-category">
        <div class="category-title">03. Hashing Techniques</div>
        <a href="03_Hashing_Techniques/intro_to_hashing.html" class="nav-item">Intro to Hashing</a>
        <a href="03_Hashing_Techniques/chaining_in_hashing.html" class="nav-item">Chaining Strategy</a>
        <a href="03_Hashing_Techniques/linear_probing_in_hashing.html" class="nav-item">Linear Probing</a>
        <a href="03_Hashing_Techniques/quadratic_probing_in_hashing.html" class="nav-item">Quadratic Probing</a>
        <a href="03_Hashing_Techniques/double_hashing_in_hashing.html" class="nav-item">Double Hashing</a>
      </div>

      <div class="sidebar-category">
        <div class="category-title">04. Trees & Heaps</div>
        <a href="04_Trees_and_Heaps/traversal_animation.html" class="nav-item">Traversal Animation</a>
        <a href="04_Trees_and_Heaps/traversal_animation_enhanced.html" class="nav-item">Enhanced Traversal</a>
        <a href="04_Trees_and_Heaps/bst_animation.html" class="nav-item">BST Animation</a>
        <a href="04_Trees_and_Heaps/avl_animation.html" class="nav-item">AVL Animation</a>
        <a href="04_Trees_and_Heaps/heap_animation.html" class="nav-item">Heap Animation</a>
      </div>

      <div class="sidebar-category">
        <div class="category-title">05. Graph Algorithms</div>
        <a href="05_Graph_Algorithms/undirected_graph_representation.html" class="nav-item">Undirected Graph</a>
        <a href="05_Graph_Algorithms/directed_graph_representation.html" class="nav-item">Directed Graph</a>
        <a href="05_Graph_Algorithms/bfs_traversal.html" class="nav-item">BFS Traversal</a>
        <a href="05_Graph_Algorithms/dfs_traversal.html" class="nav-item">DFS Traversal</a>
        <a href="05_Graph_Algorithms/disjoint_set_union.html" class="nav-item">Disjoint Set (DSU)</a>
      </div>

      <div class="sidebar-category">
        <div class="category-title">06. Divide & Conquer</div>
        <a href="06_Divide_and_Conquer/divide_and_conquer_introduction.html" class="nav-item">Introduction</a>
        <a href="06_Divide_and_Conquer/binary_search_using_divide_and_conquer.html" class="nav-item">Binary Search</a>
        <a href="06_Divide_and_Conquer/merge_sort_introduction.html" class="nav-item">Merge Sort Intro</a>
        <a href="06_Divide_and_Conquer/recursive_merge_sort_animation.html" class="nav-item">Recursive Merge Sort</a>
        <a href="06_Divide_and_Conquer/iterative_merge_sort_animation.html" class="nav-item">Iterative Merge Sort</a>
        <a href="06_Divide_and_Conquer/k_way_merging.html" class="nav-item">K-way Merging</a>
        <a href="06_Divide_and_Conquer/strassens_matrix_multiplication.html" class="nav-item">Strassen's Multiplication</a>
        <a href="06_Divide_and_Conquer/algorithm_strategies.html" class="nav-item">Algorithm Strategies</a>
        <a href="06_Divide_and_Conquer/max_subarray_divide_and_conquer.html" class="nav-item">Max Subarray D&C</a>
      </div>

      <div class="sidebar-category">
        <div class="category-title">07. Greedy Algorithms</div>
        <a href="07_Greedy_Algorithms/greedy_method_introduction.html" class="nav-item">Greedy Intro</a>
        <a href="07_Greedy_Algorithms/knapsack_problem_fractional.html" class="nav-item">Fractional Knapsack</a>
        <a href="07_Greedy_Algorithms/optimal_merge_pattern.html" class="nav-item">Optimal Merge Pattern</a>
        <a href="07_Greedy_Algorithms/dijkstra_algorithm.html" class="nav-item">Dijkstra's Path</a>
        <a href="07_Greedy_Algorithms/minimum_spanning_tree.html" class="nav-item">Minimum Spanning Tree</a>
      </div>

      <div class="sidebar-category">
        <div class="category-title">08. Dynamic Programming</div>
        <a href="08_Dynamic_Programming/dynamic_programming_introduction.html" class="nav-item">Introduction</a>
        <a href="08_Dynamic_Programming/recursion_and_dynamic_programming.html" class="nav-item">Recursion vs DP</a>
        <a href="08_Dynamic_Programming/zero_one_knapsack_problem.html" class="nav-item">0/1 Knapsack Problem</a>
        <a href="08_Dynamic_Programming/zero_one_knapsack_recursion.html" class="nav-item">0/1 Knapsack Recursion</a>
        <a href="08_Dynamic_Programming/zero_one_knapsack_using_set_method.html" class="nav-item">0/1 Knapsack Sets</a>
        <a href="08_Dynamic_Programming/max_subarray_sum.html" class="nav-item">Max Subarray (Kadane's)</a>
        <a href="08_Dynamic_Programming/kadane_with_boundaries.html" class="nav-item">Kadane's with Boundaries</a>
        <a href="08_Dynamic_Programming/longest_common_subsequence.html" class="nav-item">Longest Common Subseq.</a>
        <a href="08_Dynamic_Programming/matrix_chain_multiplication_introduction.html" class="nav-item">Matrix Chain Mult.</a>
      </div>
      
    </aside>

    <!-- Right Content Panel -->
    <main class="content-area">
      <iframe id="viewer" src="01_Basics_and_Tools/asymptotic_notation.html" title="Interactive Visualizer"></iframe>
    </main>
  </div>

  <script>
    // Navigation logic
    const viewer = document.getElementById('viewer');
    const links = document.querySelectorAll('.nav-item');

    links.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Update active class
        links.forEach(l => l.classList.remove('active'));
        this.classList.add('active');

        // Load content into iframe
        const url = this.getAttribute('href');
        viewer.src = url;
        
        // Optional mobile behavior: scroll down to iframe
        if (window.innerWidth <= 768) {
            viewer.scrollIntoView({behavior: "smooth"});
        }
      });
    });

    // Theme logic
    const themeBtn = document.getElementById('themeToggle');
    const currentTheme = localStorage.getItem('theme') || 'dark';
    if(currentTheme === 'light') {
      document.body.classList.add('light-mode');
      themeBtn.textContent = '🌙';
    }
    
    themeBtn.addEventListener('click', () => {
      document.body.classList.toggle('light-mode');
      const isLight = document.body.classList.contains('light-mode');
      localStorage.setItem('theme', isLight ? 'light' : 'dark');
      themeBtn.textContent = isLight ? '🌙' : '☀️';
    });
  </script>
</body>
</html>
