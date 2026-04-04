<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'db.php';

// Fetch the latest user info from the database
$stmt = $pdo->prepare("SELECT username, email, is_admin, created_at FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
    exit;
}

$role = $user['is_admin'] ? 'Faculty / Admin' : 'Student';
$memberSince = date("F j, Y, g:i a", strtotime($user['created_at']));
$avatarInitial = strtoupper(substr($user['username'], 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | AlgoLens</title>
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
        }

        body.light-mode {
            --bg: #f8fafc;
            --surface: #ffffff;
            --card: #f1f5f9;
            --primary: #0284c7;
            --border: rgba(15, 23, 42, 0.15);
            --text: #0f172a;
            --muted: #64748b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* Navbar (Matches Dashboard) */
        .navbar {
            height: 64px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 1.2rem;
            text-decoration: none;
            color: var(--text);
        }
        .brand img {
            width: 32px;
            height: 32px;
            border-radius: 8px;
        }
        .btn {
            padding: 8px 16px;
            border-radius: 8px;
            background: var(--surface);
            color: var(--text);
            font-weight: 600;
            border: 1px solid var(--border);
            text-decoration: none;
            transition: 0.2s;
        }
        .btn:hover { background: rgba(56, 189, 248, 0.1); }
        .btn-primary { background: var(--primary); color: #fff; border: none; }
        .btn-primary:hover { filter: brightness(1.1); }

        /* Profile Container */
        .container {
            max-width: 800px;
            margin: 60px auto;
            padding: 0 20px;
        }
        .profile-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        /* Avatar Header */
        .avatar-lg {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #818cf8);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            box-shadow: 0 8px 20px rgba(56, 189, 248, 0.4);
            border: 4px solid var(--surface);
        }
        
        .username {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 5px;
            background: linear-gradient(to right, var(--primary), #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .role-badge {
            background: rgba(56, 189, 248, 0.15);
            color: var(--primary);
            padding: 6px 14px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 30px;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            width: 100%;
            margin-top: 20px;
            text-align: left;
        }
        .info-box {
            background: var(--surface);
            padding: 24px;
            border-radius: 12px;
            border: 1px solid var(--border);
        }
        .info-label {
            font-size: 0.8rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
            font-weight: 700;
        }
        .info-value {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .action-row {
            margin-top: 40px;
            display: flex;
            gap: 15px;
            justify-content: center;
            width: 100%;
        }

        @media (max-width: 600px) {
            .info-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body class="<?= isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'light' ? 'light-mode' : '' ?>">

    <header class="navbar">
        <a href="dashboard.php" class="brand">
            <img src="assets/platform_logo.png" alt="Logo">
            <div>AlgoLens</div>
        </a>
        <a href="dashboard.php" class="btn">&larr; Back to Dashboard</a>
    </header>

    <div class="container">
        <div class="profile-card">
            <div class="avatar-lg"><?= htmlspecialchars($avatarInitial) ?></div>
            <h1 class="username"><?= htmlspecialchars($user['username']) ?></h1>
            <div class="role-badge"><?= htmlspecialchars($role) ?></div>

            <div class="info-grid">
                <div class="info-box">
                    <div class="info-label">Email Address</div>
                    <div class="info-value"><?= htmlspecialchars($user['email']) ?></div>
                </div>
                <div class="info-box">
                    <div class="info-label">Account Privilege</div>
                    <div class="info-value"><?= htmlspecialchars($role) ?></div>
                </div>
                <div class="info-box" style="grid-column: 1 / -1;">
                    <div class="info-label">Member Since</div>
                    <div class="info-value">🗓️ <?= htmlspecialchars($memberSince) ?></div>
                </div>
            </div>

            <div class="action-row">
                <a href="settings.php" class="btn btn-primary">Edit Settings</a>
                <a href="logout.php" class="btn">Sign Out</a>
            </div>
        </div>
    </div>

    <script>
        // Apply theme from localStorage if cookie isn't perfectly synced yet
        if(localStorage.getItem('theme') === 'light') {
            document.body.classList.add('light-mode');
        }
    </script>
</body>
</html>
