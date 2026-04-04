<?php
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: dashboard.php');
    exit;
}

require_once 'db.php';

// Silent auto-migration for User Progress table & Dummy Data
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS user_progress (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        module_name VARCHAR(150) NOT NULL,
        completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    $stmt = $pdo->query("SELECT COUNT(*) FROM user_progress");
    if ($stmt->fetchColumn() == 0) {
        // Insert dummy data
        $users = $pdo->query("SELECT id FROM users LIMIT 10")->fetchAll(PDO::FETCH_COLUMN);
        $modules = ['Sorting Algorithms', 'Graph Traversals', 'Dynamic Programming', 'Basic Arrays', 'Advanced Trees'];
        if (count($users) > 0) {
            $insert = $pdo->prepare("INSERT INTO user_progress (user_id, module_name, completed_at) VALUES (?, ?, DATE_SUB(NOW(), INTERVAL ? DAY))");
            for ($i=0; $i<40; $i++) {
                $uid = $users[array_rand($users)];
                $mod = $modules[array_rand($modules)];
                $days = rand(0, 15);
                $insert->execute([$uid, $mod, $days]);
            }
        }
    }
} catch(Exception $e) {}

// Handle User Deletion
if (isset($_GET['delete_id']) && $_GET['delete_id'] != $_SESSION['user_id']) {
    $del = $pdo->prepare("DELETE FROM users WHERE id = :id AND is_admin = 0");
    $del->execute(['id' => $_GET['delete_id']]);
    header('Location: admin.php?tab=users');
    exit;
}

// Handle User Promotion/Demotion
if (isset($_GET['promote_id'])) {
    $upd = $pdo->prepare("UPDATE users SET is_admin = 1 WHERE id = :id");
    $upd->execute(['id' => $_GET['promote_id']]);
    header('Location: admin.php?tab=users');
    exit;
}
if (isset($_GET['demote_id']) && $_GET['demote_id'] != $_SESSION['user_id']) {
    $upd = $pdo->prepare("UPDATE users SET is_admin = 0 WHERE id = :id");
    $upd->execute(['id' => $_GET['demote_id']]);
    header('Location: admin.php?tab=users');
    exit;
}

// Fetch all core data
$users = $pdo->query("SELECT id, username, email, is_admin, created_at FROM users ORDER BY created_at DESC")->fetchAll();
$feedbacks = $pdo->query("SELECT id, name, email, message, created_at FROM feedbacks ORDER BY created_at DESC")->fetchAll();
$progressLog = $pdo->query("SELECT up.id, u.username, up.module_name, up.completed_at FROM user_progress up JOIN users u ON up.user_id = u.id ORDER BY up.completed_at DESC LIMIT 50")->fetchAll();

// Fetch Chart Analytics
$growthData = $pdo->query("SELECT DATE(created_at) as date, COUNT(*) as count FROM users GROUP BY DATE(created_at) ORDER BY date ASC")->fetchAll(PDO::FETCH_KEY_PAIR);
$moduleData = $pdo->query("SELECT module_name, COUNT(*) as count FROM user_progress GROUP BY module_name ORDER BY count DESC")->fetchAll(PDO::FETCH_KEY_PAIR);

$totalUsers = count($users);
$totalFeedbacks = count($feedbacks);
$totalCompletions = $pdo->query("SELECT COUNT(*) FROM user_progress")->fetchColumn();

// Setup Chart arrays
$growthLabels = array_keys($growthData);
$growthValues = array_values($growthData);
$moduleLabels = array_keys($moduleData);
$moduleValues = array_values($moduleData);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Workspace | AlgoLens</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --bg: #030712;
            --surface: #111827;
            --card: #1f2937;
            --primary: #38bdf8;
            --accent: #818cf8;
            --text: #f8fafc;
            --muted: #94a3b8;
            --border: rgba(148, 163, 184, 0.15);
            --danger: #ef4444;
            --success: #10b981;
            --sidebar-width: 260px;
        }

        body.light-mode {
            --bg: #f8fafc;
            --surface: #ffffff;
            --card: #f1f5f9;
            --primary: #0284c7;
            --accent: #4f46e5;
            --text: #0f172a;
            --muted: #64748b;
            --border: rgba(15, 23, 42, 0.15);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); display: flex; height: 100vh; overflow: hidden; }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
        }
        .brand {
            height: 64px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            font-weight: 800;
            font-size: 1.25rem;
            border-bottom: 1px solid var(--border);
            color: var(--text);
            text-decoration: none;
            gap: 10px;
        }
        .brand img { width: 28px; height: 28px; border-radius: 6px; }
        .nav-links {
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex: 1;
        }
        .nav-link {
            padding: 12px 24px;
            color: var(--muted);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            border-left: 3px solid transparent;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .nav-link:hover {
            color: var(--text);
            background: rgba(56, 189, 248, 0.05);
        }
        .nav-link.active {
            color: var(--primary);
            background: rgba(56, 189, 248, 0.1);
            border-left-color: var(--primary);
        }
        .sidebar-footer {
            padding: 24px;
            border-top: 1px solid var(--border);
        }
        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 8px 16px;
            border-radius: 8px;
            background: var(--card);
            color: var(--text);
            font-weight: 600;
            font-size: 0.85rem;
            border: 1px solid var(--border);
            text-decoration: none;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn:hover { border-color: var(--primary); }
        .btn-danger { color: #fca5a5; border-color: rgba(239,68,68,0.3); }
        .btn-danger:hover { background: rgba(239,68,68,0.1); }
        .btn-success { color: #86efac; border-color: rgba(16,185,129,0.3); }
        .btn-success:hover { background: rgba(16,185,129,0.1); }
        body.light-mode .btn-danger { color: var(--danger); }
        body.light-mode .btn-success { color: var(--success); }

        /* Main Content */
        .main-content {
            flex: 1;
            overflow-y: auto;
            position: relative;
        }
        .header {
            height: 64px;
            padding: 0 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            background: var(--surface);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .header h1 { font-size: 1.1rem; font-weight: 600; }
        .page-body {
            padding: 40px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

        /* Cards & Grid */
        .grid-3 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .stat-label { font-size: 0.85rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;}
        .stat-value { font-size: 2.5rem; font-weight: 800; color: var(--primary); }

        .chart-container {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 24px;
        }

        /* Tables */
        .table-wrap {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 16px 24px; text-align: left; border-bottom: 1px solid var(--border); font-size: 0.9rem;}
        th { background: rgba(0,0,0,0.2); color: var(--muted); font-weight: 600; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.02em; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,0.02); }
        .badge {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-block;
        }
        .badge-admin { background: rgba(56, 189, 248, 0.15); color: var(--primary); }
        .badge-student { background: rgba(148, 163, 184, 0.15); color: var(--muted); }

    </style>
</head>
<body class="<?= isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'light' ? 'light-mode' : '' ?>">

    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="dashboard.php" class="brand">
            <img src="assets/platform_logo.png" alt="Logo">
            <span>Admin Center</span>
        </a>
        <div class="nav-links">
            <div class="nav-link active" onclick="switchTab('overview')">📊 Overview</div>
            <div class="nav-link" onclick="switchTab('users')">👥 User Management</div>
            <div class="nav-link" onclick="switchTab('progress')">📈 Learning Progress</div>
            <div class="nav-link" onclick="switchTab('feedback')">💬 Feedback Engine</div>
        </div>
        <div class="sidebar-footer">
            <a href="dashboard.php" class="btn" style="width: 100%; margin-bottom: 12px;">App Workspace</a>
            <a href="logout.php" class="btn btn-danger" style="width: 100%;">Sign Out</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="header">
            <h1 id="pageTitle">Overview</h1>
            <button class="btn" id="themeToggle" style="background:transparent; border:none; box-shadow:none; font-size:1.2rem;">☀️</button>
        </div>

        <div class="page-body">

            <!-- TAB: OVERVIEW -->
            <div id="tab-overview" class="tab-content active">
                <div class="grid-3">
                    <div class="stat-card">
                        <div class="stat-label">Total Users</div>
                        <div class="stat-value"><?= $totalUsers ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Module Completions</div>
                        <div class="stat-value"><?= $totalCompletions ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Unread Feedbacks</div>
                        <div class="stat-value"><?= $totalFeedbacks ?></div>
                    </div>
                </div>

                <div class="chart-container" style="height: 350px;">
                    <canvas id="growthChart"></canvas>
                </div>

                <div class="chart-container" style="height: 350px;">
                    <canvas id="moduleChart"></canvas>
                </div>
            </div>

            <!-- TAB: USERS -->
            <div id="tab-users" class="tab-content">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($users as $u): ?>
                                <tr>
                                    <td>#<?= $u['id'] ?></td>
                                    <td><strong><?= htmlspecialchars($u['username']) ?></strong></td>
                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                    <td><span class="badge <?= $u['is_admin'] ? 'badge-admin' : 'badge-student' ?>"><?= $u['is_admin'] ? 'Administrator' : 'Student' ?></span></td>
                                    <td><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                                    <td>
                                        <div style="display:flex; gap:8px;">
                                            <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                                <?php if (!$u['is_admin']): ?>
                                                    <a href="admin.php?promote_id=<?= $u['id'] ?>" class="btn btn-success" onclick="return confirm('Promote this user to Admin?');">Promote</a>
                                                    <a href="admin.php?delete_id=<?= $u['id'] ?>" class="btn btn-danger" onclick="return confirm('Delete this user completely?');">Remove</a>
                                                <?php else: ?>
                                                    <a href="admin.php?demote_id=<?= $u['id'] ?>" class="btn" style="color:var(--muted);" onclick="return confirm('Demote this admin to Student?');">Demote</a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span style="color:var(--muted); font-size:0.8rem; font-style:italic;">(You)</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB: PROGRESS -->
            <div id="tab-progress" class="tab-content">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Log ID</th>
                                <th>Student</th>
                                <th>Module Completed</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($progressLog as $p): ?>
                                <tr>
                                    <td>#<?= $p['id'] ?></td>
                                    <td><strong><?= htmlspecialchars($p['username']) ?></strong></td>
                                    <td><span class="badge" style="background: rgba(129, 140, 248, 0.15); color: var(--accent);"><?= htmlspecialchars($p['module_name']) ?></span></td>
                                    <td><?= date('Y-m-d H:i:s', strtotime($p['completed_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($progressLog)): ?>
                            <tr><td colspan="4" style="text-align:center; color:var(--muted); padding:30px;">No progress data collected yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB: FEEDBACK -->
            <div id="tab-feedback" class="tab-content">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Sender</th>
                                <th>Message</th>
                                <th>Date Received</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($feedbacks as $f): ?>
                                <tr>
                                    <td style="vertical-align:top;">
                                        <strong><?= htmlspecialchars($f['name']) ?></strong><br>
                                        <a href="mailto:<?= htmlspecialchars($f['email']) ?>" style="color:var(--primary); text-decoration:none; font-size:0.85rem;"><?= htmlspecialchars($f['email']) ?></a>
                                    </td>
                                    <td style="color:var(--muted); max-width:500px; line-height:1.5;"><?= nl2br(htmlspecialchars($f['message'])) ?></td>
                                    <td style="vertical-align:top;"><?= date('M d \a\t H:i', strtotime($f['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($feedbacks)): ?>
                            <tr><td colspan="3" style="text-align:center; color:var(--muted); padding:30px;">Inbox is empty.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <script>
        // SPA Tab Routing
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));
            document.getElementById('tab-' + tabId).classList.add('active');
            event.currentTarget.classList.add('active');

            const titleMap = {
                'overview': 'Overview',
                'users': 'User Management',
                'progress': 'Learning Progress',
                'feedback': 'Feedback Engine'
            };
            document.getElementById('pageTitle').textContent = titleMap[tabId];
            window.history.replaceState(null, null, '?tab=' + tabId);
        }

        // Auto-open requested tab
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('tab')) {
            const tabs = document.querySelectorAll('.nav-link');
            tabs.forEach(t => {
                if(t.textContent.toLowerCase().includes(urlParams.get('tab'))) {
                    t.click();
                }
            });
        }

        // Charts
        const isLight = document.body.classList.contains('light-mode');
        const gridColor = isLight ? 'rgba(0,0,0,0.05)' : 'rgba(255,255,255,0.05)';
        const textColor = isLight ? '#64748b' : '#94a3b8';

        Chart.defaults.color = textColor;
        Chart.defaults.font.family = "'Inter', sans-serif";

        const growthCtx = document.getElementById('growthChart').getContext('2d');
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($growthLabels) ?>,
                datasets: [{
                    label: 'User Registrations',
                    data: <?= json_encode($growthValues) ?>,
                    borderColor: '#38bdf8',
                    backgroundColor: 'rgba(56, 189, 248, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, title: { display: true, text: 'User Growth Trend (Last 30 Days)', align: 'start' } },
                scales: { 
                    x: { grid: { display: false } },
                    y: { grid: { color: gridColor }, beginAtZero: true }
                }
            }
        });

        const moduleCtx = document.getElementById('moduleChart').getContext('2d');
        new Chart(moduleCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($moduleLabels) ?>,
                datasets: [{
                    label: 'Module Completions',
                    data: <?= json_encode($moduleValues) ?>,
                    backgroundColor: '#818cf8',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, title: { display: true, text: 'Most Popular Modules', align: 'start' } },
                scales: { 
                    x: { grid: { display: false } },
                    y: { grid: { color: gridColor }, beginAtZero: true }
                }
            }
        });

        // Theme sync
        const themeBtn = document.getElementById('themeToggle');
        themeBtn.addEventListener('click', () => {
            document.body.classList.toggle('light-mode');
            const lightNow = document.body.classList.contains('light-mode');
            localStorage.setItem('theme', lightNow ? 'light' : 'dark');
            document.cookie = "theme=" + (lightNow ? 'light' : 'dark') + "; path=/; max-age=31536000";
            location.reload(); // Quick refresh to update chart colors accurately
        });
        if(localStorage.getItem('theme') === 'light' && !document.body.classList.contains('light-mode')) {
            document.body.classList.add('light-mode');
        }
    </script>
</body>
</html>
