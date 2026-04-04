<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'db.php';

// Silently inject theme_preference column if it doesn't exist
try {
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'theme_preference'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE users ADD COLUMN theme_preference VARCHAR(10) DEFAULT 'dark'");
    }
} catch (Exception $e) {}

$message = "";
$error = "";

// Fetch current user data
$stmt = $pdo->prepare("SELECT email, theme_preference FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newEmail = trim($_POST['email'] ?? '');
    $newPassword = $_POST['password'] ?? '';
    $theme = $_POST['theme'] ?? 'dark';

    if (empty($newEmail)) {
        $error = "Email cannot be empty.";
    } else {
        try {
            if (!empty($newPassword)) {
                $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                $update = $pdo->prepare("UPDATE users SET email = :email, password_hash = :hash, theme_preference = :theme WHERE id = :id");
                $update->execute([
                    'email' => $newEmail,
                    'hash' => $hash,
                    'theme' => $theme,
                    'id' => $_SESSION['user_id']
                ]);
            } else {
                $update = $pdo->prepare("UPDATE users SET email = :email, theme_preference = :theme WHERE id = :id");
                $update->execute([
                    'email' => $newEmail,
                    'theme' => $theme,
                    'id' => $_SESSION['user_id']
                ]);
            }
            $message = "Settings updated successfully!";
            // Refresh user data
            $user['email'] = $newEmail;
            $user['theme_preference'] = $theme;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "That email is already in use by another account.";
            } else {
                $error = "An error occurred: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | AlgoLens</title>
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
            cursor: pointer;
            font-family: inherit;
            font-size: 0.95rem;
        }
        .btn:hover { background: rgba(56, 189, 248, 0.1); }
        .btn-primary { background: var(--primary); color: #fff; border: none; }
        .btn-primary:hover { filter: brightness(1.1); }

        .container {
            max-width: 600px;
            margin: 60px auto;
            padding: 0 20px;
        }
        .settings-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        h1 {
            font-size: 1.8rem;
            margin-bottom: 8px;
            background: linear-gradient(to right, var(--primary), #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .subtitle {
            color: var(--muted);
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 24px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text);
        }
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text);
            font-family: inherit;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.2s;
        }
        input:focus, select:focus {
            border-color: var(--primary);
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-weight: 600;
        }
        .alert-success { background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: #22c55e;}
        .alert-error { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444;}
        
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
        <div class="settings-card">
            <h1>Account Settings</h1>
            <p class="subtitle">Update your profile details and preferences.</p>

            <?php if (!empty($message)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label>New Password (leave blank to keep current)</label>
                    <input type="password" name="password" placeholder="Enter new password...">
                </div>

                <div class="form-group">
                    <label>Default Visual Theme</label>
                    <select name="theme" id="themeSelect">
                        <option value="dark" <?= ($user['theme_preference'] ?? 'dark') === 'dark' ? 'selected' : '' ?>>Dark Mode</option>
                        <option value="light" <?= ($user['theme_preference'] ?? 'dark') === 'light' ? 'selected' : '' ?>>Light Mode</option>
                    </select>
                </div>

                <div style="margin-top: 32px;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Apply theme from localStorage if available
        if(localStorage.getItem('theme') === 'light') {
            document.body.classList.add('light-mode');
        }

        // Intercept form submit to also sync JS localStorage for immediate effect
        document.querySelector('form').addEventListener('submit', function() {
            const selectedTheme = document.getElementById('themeSelect').value;
            localStorage.setItem('theme', selectedTheme);
            document.cookie = "theme=" + selectedTheme + "; path=/; max-age=31536000";
        });
    </script>
</body>
</html>
