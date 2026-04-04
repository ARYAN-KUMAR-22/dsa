<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$activeView = isset($_GET['view']) ? $_GET['view'] : '01_Basics_and_Tools/asymptotic_notation.php';
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
    .brand-text {
      background: linear-gradient(to right, var(--primary), #818cf8);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      font-weight: 800;
      letter-spacing: -0.5px;
    }
    .brand span.suffix {
      color: var(--muted);
      font-weight: 400;
      font-size: 0.95rem;
      -webkit-text-fill-color: var(--muted); /* prevent gradient bleed */
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
    .user-profile {
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      position: relative;
    }
    .avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--primary), #818cf8);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 1rem;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
      transition: transform 0.2s;
    }
    .user-profile:hover .avatar {
      transform: scale(1.05);
    }
    .user-info {
      display: flex;
      flex-direction: column;
    }
    .user-name {
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--text);
    }
    .user-role {
      font-size: 0.70rem;
      color: var(--muted);
    }
    
    /* Dropdown CSS */
    .dropdown-menu {
      position: absolute;
      top: 50px;
      right: 0;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.5);
      width: 220px;
      padding: 8px;
      display: none;
      flex-direction: column;
      z-index: 100;
      opacity: 0;
      transform: translateY(-10px);
      transition: opacity 0.2s, transform 0.2s;
    }
    .dropdown-menu.show {
      display: flex;
      opacity: 1;
      transform: translateY(0);
    }
    .dropdown-item {
      padding: 10px 14px;
      color: var(--text);
      text-decoration: none;
      font-size: 0.9rem;
      border-radius: 8px;
      transition: background 0.2s;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .dropdown-item:hover {
      background: var(--hover);
    }
    .dropdown-divider {
      height: 1px;
      background: var(--border);
      margin: 8px 0;
    }
    .text-danger {
      color: #ef4444;
    }
    body.dark-mode .text-danger {
      color: #fca5a5;
    }

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
      <div><span class="brand-text">AlgoLens</span> <span class="suffix">Workspace</span></div>
    </div>
    <div class="nav-actions">
      <button class="btn" id="themeToggle" aria-label="Toggle Theme">☀️</button>
      <div class="user-profile" id="profileDropdownBtn">
        <div class="user-info" style="text-align: right; padding-right: 4px;">
          <span class="user-name"><?= htmlspecialchars($_SESSION['username'] ?? 'Student') ?></span>
          <span class="user-role"><?= (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) ? 'Faculty' : 'Student' ?></span>
        </div>
        <div class="avatar"><?= strtoupper(substr(htmlspecialchars($_SESSION['username'] ?? 'User'), 0, 1)) ?></div>
        
        <div class="dropdown-menu" id="profileDropdown">
          <a href="profile.php" class="dropdown-item">👤 My Profile</a>
          <a href="settings.php" class="dropdown-item">⚙️ Settings</a>
          <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
            <div class="dropdown-divider"></div>
            <a href="admin.php" class="dropdown-item">🛡️ Admin Panel</a>
          <?php endif; ?>
          <div class="dropdown-divider"></div>
          <a href="logout.php" class="dropdown-item text-danger">🚪 Logout</a>
        </div>
      </div>
    </div>
  </header>

  <!-- Workspace Split Layout -->
  <div class="workspace">
    <!-- Left Sidebar Menu -->
    <aside class="sidebar" id="sidebarMenu">
      
      <div class="sidebar-category">
        <div class="category-title">01. Basics & Tools</div>
        <a href="01_Basics_and_Tools/asymptotic_notation.php" class="nav-item active">Asymptotic Notation</a>
        <a href="01_Basics_and_Tools/recurrence_relation.php" class="nav-item">Recurrence Relation</a>
        <a href="01_Basics_and_Tools/stl_containers_cpp.php" class="nav-item">STL Containers</a>
        <a href="01_Basics_and_Tools/code_visualizer.php" class="nav-item">Code Visualizer</a>
      </div>

      <div class="sidebar-category">
        <div class="category-title">02. Sorting Algorithms</div>
        <a href="02_Sorting_Algorithms/index.php" class="nav-item">Sorting Hub Directory</a>
      </div>

      <div class="sidebar-category">
        <div class="category-title">03. Hashing Techniques</div>
        <a href="03_Hashing_Techniques/intro_to_hashing_theory.php" class="nav-item">Intro to Hashing</a>
        <a href="03_Hashing_Techniques/chaining_in_hashing.php" class="nav-item">Chaining Strategy</a>
        <a href="03_Hashing_Techniques/linear_probing_in_hashing.php" class="nav-item">Linear Probing</a>
        <a href="03_Hashing_Techniques/quadratic_probing_in_hashing.php" class="nav-item">Quadratic Probing</a>
        <a href="03_Hashing_Techniques/double_hashing_in_hashing.php" class="nav-item">Double Hashing</a>
      </div>

      <div class="sidebar-category">
        <div class="category-title">04. Trees & Heaps</div>
        <a href="04_Trees_and_Heaps/traversal_animation.php" class="nav-item">Traversal Animation</a>
        <a href="04_Trees_and_Heaps/traversal_animation_enhanced.php" class="nav-item">Enhanced Traversal</a>
        <a href="04_Trees_and_Heaps/bst_animation.php" class="nav-item">BST Animation</a>
        <a href="04_Trees_and_Heaps/avl_animation.php" class="nav-item">AVL Animation</a>
        <a href="04_Trees_and_Heaps/heap_animation.php" class="nav-item">Heap Animation</a>
      </div>

      <div class="sidebar-category">
        <div class="category-title">05. Graph Algorithms</div>
        <a href="05_Graph_Algorithms/undirected_graph_representation.php" class="nav-item">Undirected Graph</a>
        <a href="05_Graph_Algorithms/directed_graph_representation.php" class="nav-item">Directed Graph</a>
        <a href="05_Graph_Algorithms/bfs_traversal.php" class="nav-item">BFS Traversal</a>
        <a href="05_Graph_Algorithms/dfs_traversal.php" class="nav-item">DFS Traversal</a>
        <a href="05_Graph_Algorithms/disjoint_set_union.php" class="nav-item">Disjoint Set (DSU)</a>
      </div>

      <div class="sidebar-category">
        <div class="category-title">06. Divide & Conquer</div>
        <a href="06_Divide_and_Conquer/divide_and_conquer_introduction.php" class="nav-item">Introduction</a>
        <a href="06_Divide_and_Conquer/binary_search_using_divide_and_conquer.php" class="nav-item">Binary Search</a>
        <a href="06_Divide_and_Conquer/merge_sort_introduction.php" class="nav-item">Merge Sort Intro</a>
        <a href="06_Divide_and_Conquer/recursive_merge_sort_animation.php" class="nav-item">Recursive Merge Sort</a>
        <a href="06_Divide_and_Conquer/iterative_merge_sort_animation.php" class="nav-item">Iterative Merge Sort</a>
        <a href="06_Divide_and_Conquer/k_way_merging.php" class="nav-item">K-way Merging</a>
        <a href="06_Divide_and_Conquer/strassens_matrix_multiplication.php" class="nav-item">Strassen's Multiplication</a>
        <a href="06_Divide_and_Conquer/algorithm_strategies.php" class="nav-item">Algorithm Strategies</a>
        <a href="06_Divide_and_Conquer/max_subarray_divide_and_conquer.php" class="nav-item">Max Subarray D&C</a>
      </div>

      <div class="sidebar-category">
        <div class="category-title">07. Greedy Algorithms</div>
        <a href="07_Greedy_Algorithms/greedy_method_introduction.php" class="nav-item">Greedy Intro</a>
        <a href="07_Greedy_Algorithms/knapsack_problem_fractional.php" class="nav-item">Fractional Knapsack</a>
        <a href="07_Greedy_Algorithms/optimal_merge_pattern.php" class="nav-item">Optimal Merge Pattern</a>
        <a href="07_Greedy_Algorithms/dijkstra_algorithm.php" class="nav-item">Dijkstra's Path</a>
        <a href="07_Greedy_Algorithms/minimum_spanning_tree.php" class="nav-item">Minimum Spanning Tree</a>
      </div>

      <div class="sidebar-category">
        <div class="category-title">08. Dynamic Programming</div>
        <a href="08_Dynamic_Programming/dynamic_programming_introduction.php" class="nav-item">Introduction</a>
        <a href="08_Dynamic_Programming/recursion_and_dynamic_programming.php" class="nav-item">Recursion vs DP</a>
        <a href="08_Dynamic_Programming/zero_one_knapsack_problem.php" class="nav-item">0/1 Knapsack Problem</a>
        <a href="08_Dynamic_Programming/zero_one_knapsack_recursion.php" class="nav-item">0/1 Knapsack Recursion</a>
        <a href="08_Dynamic_Programming/zero_one_knapsack_memoization.php" class="nav-item">0/1 Knapsack Memoization</a>
        <a href="08_Dynamic_Programming/zero_one_knapsack_tabulation.php" class="nav-item">0/1 Knapsack Tabulation</a>
        <a href="08_Dynamic_Programming/zero_one_knapsack_using_set_method.php" class="nav-item">0/1 Knapsack Sets</a>
        <a href="08_Dynamic_Programming/max_subarray_brute_force.php" class="nav-item">Max Subarray (Brute Force)</a>
        <a href="08_Dynamic_Programming/max_subarray_dp.php" class="nav-item">Max Subarray (DP)</a>
        <a href="08_Dynamic_Programming/max_subarray_sum.php" class="nav-item">Max Subarray (Kadane's)</a>
        <a href="08_Dynamic_Programming/kadane_with_boundaries.php" class="nav-item">Kadane's with Boundaries</a>
        <a href="08_Dynamic_Programming/longest_common_subsequence.php" class="nav-item">Longest Common Subseq.</a>
        <a href="08_Dynamic_Programming/lcs_recursion.php" class="nav-item">LCS Recursion</a>
        <a href="08_Dynamic_Programming/lcs_memoization.php" class="nav-item">LCS Memoization</a>
        <a href="08_Dynamic_Programming/lcs_tabulation.php" class="nav-item">LCS Tabulation</a>
        <a href="08_Dynamic_Programming/matrix_chain_multiplication_introduction.php" class="nav-item">Matrix Chain Mult.</a>
        <a href="08_Dynamic_Programming/matrix_chain_multiplication_recursion.php" class="nav-item">Matrix Chain Mult. Recursion</a>
        <a href="08_Dynamic_Programming/matrix_chain_multiplication_memoization.php" class="nav-item">Matrix Chain Mult. Memoization</a>
        <a href="08_Dynamic_Programming/matrix_chain_multiplication_tabulation.php" class="nav-item">Matrix Chain Mult. Tabulation</a>
      </div>
      
    </aside>

    <!-- Right Content Panel -->
    <main class="content-area">
      <iframe id="viewer" src="<?= htmlspecialchars($activeView) ?>" title="Interactive Visualizer"></iframe>
    </main>
  </div>

  <script>
    // Navigation logic
    const viewer = document.getElementById('viewer');
    const links = document.querySelectorAll('.nav-item');
    const currentView = "<?= htmlspecialchars($activeView) ?>";

    // Set initial active state based on PHP view loaded
    links.forEach(l => l.classList.remove('active'));
    links.forEach(l => {
       if (l.getAttribute('href') === currentView) {
           l.classList.add('active');
       }
    });

    links.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Update active class
        links.forEach(l => l.classList.remove('active'));
        this.classList.add('active');

        // Load content into iframe
        const url = this.getAttribute('href');
        viewer.src = url;
        
        // If it's a theory or hub page, load it in the iframe nicely
        if (url.includes('theory.') || url.includes('/index.php') || url.includes('theory.php')) {
          if (window.innerWidth <= 768) {
            viewer.scrollIntoView({behavior: "smooth"});
          }
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

    // Dropdown logic
    const profileBtn = document.getElementById('profileDropdownBtn');
    const profileDropdown = document.getElementById('profileDropdown');

    profileBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      profileDropdown.classList.toggle('show');
    });

    window.addEventListener('click', function(e) {
      if (!profileBtn.contains(e.target)) {
        profileDropdown.classList.remove('show');
      }
    });
  </script>
</body>
</html>
