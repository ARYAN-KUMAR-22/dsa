<?php
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: dashboard.php');
    exit;
}

require_once 'db.php';

// Fetch users
$stmt = $pdo->query("SELECT id, username, email, is_admin, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle user deletion if needed
if (isset($_GET['delete_id']) && $_GET['delete_id'] != $_SESSION['user_id']) {
    $del = $pdo->prepare("DELETE FROM users WHERE id = :id AND is_admin = 0");
    $del->execute(['id' => $_GET['delete_id']]);
    header('Location: admin.php');
    exit;
}

// Basic metrics
$totalUsers = count($users);

// Fetch feedbacks
$stmt_f = $pdo->query("SELECT id, name, email, message, created_at FROM feedbacks ORDER BY created_at DESC");
$feedbacks = $stmt_f->fetchAll(PDO::FETCH_ASSOC);
$totalFeedbacks = count($feedbacks);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | AlgoLens</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f172a;
            --surface: #1e293b;
            --card: #334155;
            --text: #f8fafc;
            --muted: #94a3b8;
            --border: rgba(148, 163, 184, 0.2);
            --primary: #38bdf8;
            --danger: #ef4444;
        }
        
        body.light-mode {
            --bg: #f8fafc;
            --surface: #ffffff;
            --card: #f1f5f9;
            --text: #0f172a;
            --muted: #64748b;
            --border: rgba(15, 23, 42, 0.15);
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            margin: 0; padding: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }

        .header h1 { margin: 0; font-weight: 800; }
        
        .metric-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .metric-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 24px;
        }

        .metric-card span { color: var(--muted); font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;}
        .metric-card h2 { margin: 10px 0 0; font-size: 2.5rem; color: var(--primary); }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--surface);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        th, td {
            padding: 16px 20px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        th {
            background: rgba(148, 163, 184, 0.05);
            color: var(--muted);
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,0.02); }

        .btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }
        
        .btn-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: bold;
            background: rgba(56, 189, 248, 0.1);
            color: var(--primary);
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Platform Control Panel</h1>
        <div style="display: flex; align-items: center; gap: 10px;">
            <button class="btn" id="themeToggle" style="background:transparent; color:var(--text); border:none; font-size:1.2rem; cursor:pointer;" aria-label="Switch theme">☀️</button>
            <a href="dashboard.php" class="btn" style="background: var(--surface); color: var(--text); border: 1px solid var(--border);">Back to App</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="metric-cards">
        <div class="metric-card">
            <span>Total Registered Users</span>
            <h2><?= $totalUsers ?></h2>
        </div>
        <div class="metric-card">
            <span>Feedback Entries</span>
            <h2><?= $totalFeedbacks ?></h2>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Joined At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><strong><?= htmlspecialchars($u['username']) ?></strong></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= $u['is_admin'] ? '<span class="badge">Admin</span>' : 'Student' ?></td>
                    <td><?= date('Y-m-d H:i', strtotime($u['created_at'])) ?></td>
                    <td>
                        <?php if (!$u['is_admin']): ?>
                            <a href="admin.php?delete_id=<?= $u['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to remove this user?');">Remove</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2 style="margin-top: 50px; margin-bottom: 20px;">User Feedback</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Sender Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($feedbacks as $f): ?>
                <tr>
                    <td><?= $f['id'] ?></td>
                    <td><strong><?= htmlspecialchars($f['name']) ?></strong></td>
                    <td><a href="mailto:<?= htmlspecialchars($f['email']) ?>" style="color: var(--primary); text-decoration: none;"><?= htmlspecialchars($f['email']) ?></a></td>
                    <td style="max-width: 400px; line-height: 1.4; color: var(--muted);"><?= nl2br(htmlspecialchars($f['message'])) ?></td>
                    <td><?= date('M d, Y H:i', strtotime($f['created_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($feedbacks)): ?>
                <tr><td colspan="5" style="text-align: center; color: var(--muted); padding: 30px;">No feedback received yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <footer style="margin-top: 50px; text-align: center; font-size: 0.9rem; color: var(--muted); border-top: 1px solid var(--border); padding-top: 20px;">
        &copy; <?php echo date("Y"); ?> AlgoLens Admin Panel.
    </footer>

    <script>
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
