<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$dashboardLink = ($isLoggedIn && isset($_SESSION['is_admin']) && $_SESSION['is_admin']) ? 'admin.php' : 'dashboard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AlgoLens | Learn DSA Interactively</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&family=Fira+Code:wght@500;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: #030712;
      --surface: #111827;
      --card: #1f2937;
      --primary: #38bdf8;
      --secondary: #818cf8;
      --accent: linear-gradient(135deg, var(--primary), var(--secondary));
      --text: #f8fafc;
      --muted: #94a3b8;
      --border: rgba(148, 163, 184, 0.15);
    }
    
    body.light-mode {
      --bg: #f8fafc;
      --surface: #ffffff;
      --card: #f1f5f9;
      --text: #0f172a;
      --muted: #64748b;
      --border: rgba(15, 23, 42, 0.15);
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      background-color: var(--bg);
      color: var(--text);
      font-family: 'Inter', sans-serif;
      overflow-x: hidden;
      line-height: 1.6;
    }

    /* Navbar */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 5%;
      background: rgba(3, 7, 18, 0.8);
      backdrop-filter: blur(12px);
      position: fixed;
      width: 100%;
      top: 0;
      z-index: 1000;
      border-bottom: 1px solid var(--border);
    }
    .brand {
      display: flex;
      align-items: center;
      gap: 12px;
      font-weight: 900;
      font-size: 1.5rem;
      letter-spacing: -0.03em;
    }
    .brand img {
      width: 42px;
      height: 42px;
      border-radius: 12px;
    }
    .brand-text span {
      background: var(--accent);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .nav-links {
      display: flex;
      gap: 20px;
      align-items: center;
    }
    .btn {
      padding: 10px 24px;
      border-radius: 999px;
      font-weight: 600;
      font-size: 0.9rem;
      cursor: pointer;
      text-decoration: none;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-ghost {
      background: transparent;
      color: var(--text);
      border: 1px solid var(--border);
    }
    .btn-ghost:hover {
      background: rgba(255, 255, 255, 0.05);
    }
    .btn-primary {
      background: var(--accent);
      color: #fff;
      border: none;
      box-shadow: 0 4px 15px rgba(56, 189, 248, 0.3);
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(56, 189, 248, 0.4);
    }

    /* Hero */
    .hero {
      padding: 140px 5% 80px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 50px;
      align-items: center;
      max-width: 1400px;
      margin: 0 auto;
    }
    .hero-content h1 {
      font-size: clamp(3rem, 5vw, 4.5rem);
      line-height: 1.1;
      font-weight: 900;
      margin-bottom: 24px;
      letter-spacing: -0.03em;
    }
    .hero-content p {
      color: var(--muted);
      font-size: 1.15rem;
      margin-bottom: 32px;
      max-width: 500px;
    }
    .hero-poster {
      position: relative;
      border-radius: 24px;
      overflow: hidden;
      border: 1px solid var(--border);
      box-shadow: 0 20px 80px rgba(0, 0, 0, 0.6);
      transform: perspective(1000px) rotateY(-5deg);
      transition: transform 0.5s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    .hero-poster:hover {
      transform: perspective(1000px) rotateY(0deg) scale(1.02);
    }
    .hero-poster img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    /* About Us / Features */
    .about-section {
      padding: 80px 5%;
      background: var(--surface);
      border-top: 1px solid var(--border);
    }
    .about-container {
      max-width: 1400px;
      margin: 0 auto;
      text-align: center;
    }
    .about-container h2 {
      font-size: 2.5rem;
      margin-bottom: 16px;
    }
    .about-container > p {
      color: var(--muted);
      max-width: 700px;
      margin: 0 auto 50px;
      font-size: 1.1rem;
    }
    .feature-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 24px;
    }
    .feature-card {
      background: var(--card);
      padding: 32px;
      border-radius: 20px;
      border: 1px solid var(--border);
      text-align: left;
      transition: transform 0.2s;
    }
    .feature-card:hover {
      transform: translateY(-5px);
      border-color: rgba(56, 189, 248, 0.3);
    }
    .feature-card h3 {
      font-size: 1.3rem;
      margin-bottom: 12px;
      color: var(--text);
    }
    .feature-card p {
      color: var(--muted);
      font-size: 0.95rem;
    }
    .feature-icon {
      font-size: 2.5rem;
      margin-bottom: 16px;
      background: var(--accent);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    /* Modal */
    .modal-overlay {
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(5px);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 2000;
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    .modal-overlay.active {
      display: flex;
      opacity: 1;
    }
    .modal {
      background: var(--surface);
      padding: 40px;
      border-radius: 24px;
      width: 100%;
      max-width: 420px;
      border: 1px solid var(--border);
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
      position: relative;
      transform: translateY(20px);
      transition: transform 0.3s ease;
    }
    .modal-overlay.active .modal {
      transform: translateY(0);
    }
    .modal h2 {
      margin-bottom: 24px;
      font-size: 1.8rem;
    }
    .close-btn {
      position: absolute;
      top: 20px;
      right: 20px;
      background: transparent;
      border: none;
      color: var(--muted);
      font-size: 1.5rem;
      cursor: pointer;
    }
    .form-group {
      margin-bottom: 18px;
    }
    .form-group label {
      display: block;
      margin-bottom: 6px;
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--muted);
    }
    .form-group input {
      width: 100%;
      padding: 12px 14px;
      border-radius: 12px;
      border: 1px solid var(--border);
      background: var(--bg);
      color: var(--text);
      font-family: inherit;
      outline: none;
    }
    .form-group input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.1);
    }
    .form-submit {
      width: 100%;
      padding: 12px;
      margin-top: 10px;
      font-size: 1rem;
    }
    .form-footer {
      margin-top: 24px;
      text-align: center;
      font-size: 0.9rem;
      color: var(--muted);
    }
    .form-footer a {
      color: var(--primary);
      cursor: pointer;
      text-decoration: none;
    }
    .alert {
      padding: 10px 14px;
      border-radius: 8px;
      margin-bottom: 16px;
      font-size: 0.9rem;
      display: none;
    }
    .alert.error { background: rgba(220, 38, 38, 0.1); border: 1px solid rgba(220, 38, 38, 0.3); color: #fca5a5; }
    .alert.success { background: rgba(22, 163, 74, 0.1); border: 1px solid rgba(22, 163, 74, 0.3); color: #86efac; }

    @media (max-width: 900px) {
      .hero {
        grid-template-columns: 1fr;
        padding-top: 120px;
        text-align: center;
      }
      .hero-content p {
        margin: 0 auto 32px;
      }
      .hero-poster {
        transform: none;
      }
    }
  </style>
</head>
<body>

  <nav class="navbar">
    <div class="brand">
      <img src="assets/platform_logo.png" alt="Logo">
      <div class="brand-text">Algo<span>Lens</span></div>
    </div>
    <div class="nav-links">
      <a href="#about" style="color: var(--text); text-decoration: none; font-weight: 600; font-size: 0.9rem;">About</a>
      <a href="#feedback" style="color: var(--text); text-decoration: none; font-weight: 600; font-size: 0.9rem; margin-right: 10px;">Contact</a>
      <button class="btn btn-ghost" id="themeToggle" aria-label="Switch theme" style="border:none; border-radius:50%; width:40px; height:40px; display:inline-flex; align-items:center; justify-content:center; font-size:1.2rem; cursor:pointer; margin-right:10px;">☀️</button>
      
      <?php if($isLoggedIn): ?>
          <a href="<?= $dashboardLink ?>" class="btn btn-primary" style="text-decoration:none;">Go to Dashboard &rarr;</a>
      <?php else: ?>
          <button class="btn btn-ghost" onclick="openModal('loginModal')">Sign In</button>
          <button class="btn btn-primary" onclick="openModal('registerModal')">Get Started</button>
      <?php endif; ?>
    </div>
  </nav>

  <section class="hero">
    <div class="hero-content">
      <h1>Master Algorithms Through Visual Intuition</h1>
      <p>Stop memorizing code. Start seeing the logic. Interactive dashboards covering Sorting, Graph Theory, Divide & Conquer, and Dynamic Programming.</p>
      <div style="display: flex; gap: 14px; flex-wrap: wrap;">
        <?php if($isLoggedIn): ?>
            <a href="<?= $dashboardLink ?>" class="btn btn-primary" style="font-size: 1.1rem; padding: 14px 32px; text-decoration: none;">Launch App</a>
        <?php else: ?>
            <button class="btn btn-primary" style="font-size: 1.1rem; padding: 14px 32px;" onclick="openModal('registerModal')">Start Learning Free</button>
        <?php endif; ?>
        <a href="#about" class="btn btn-ghost" style="font-size: 1.1rem; padding: 14px 32px;">Explore Features</a>
      </div>
    </div>
    <div class="hero-poster">
      <img src="assets/hero_poster.png" alt="Platform Dashboard Preview">
    </div>
  </section>

  <section id="about" class="about-section">
    <div class="about-container">
      <h2>Why AlgoLens?</h2>
      <p>We built this platform to bridge the gap between dry textbook theory and actual understanding. By combining real-time visualizers with guided intuition, you learn how structural limits bend and fold.</p>
      
      <div class="feature-grid">
        <div class="feature-card">
          <div class="feature-icon">🔍</div>
          <h3>Step-by-Step Tracing</h3>
          <p>Scrub through complex recursion trees and graph traversals exactly like a video timeline. Inspect the call stack at any micro-second.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">🧩</div>
          <h3>Dynamic Programming Demystified</h3>
          <p>Interactive tables visually link the overlapping subproblems so you can actually feel the transitions instead of guessing formulas.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">⚡</div>
          <h3>Beautiful Code execution</h3>
          <p>Write C++ using the built-in compiler visualization lab. Inspect arrays, linked lists, and variables instantly mapped into memory blocks.</p>
        </div>
      </div>
    </div>
  </section>

  <section id="about-team" style="padding: 80px 5%; max-width: 1400px; margin: 0 auto; text-align: center;">
    <h2 style="font-size: 2.5rem; margin-bottom: 24px;">Who We Are</h2>
    <p style="color: var(--muted); max-width: 800px; margin: 0 auto; font-size: 1.1rem;">
      AlgoLens was built by students, for students. We realized that passing Data Structures and Algorithms requires more than just reading code—it requires visual intuition. We've dedicated this platform to interactive learning, breaking down the toughest concepts in Computer Science into digestible, dynamic experiences so you can crush your exams and interviews.
    </p>
  </section>

  <section id="feedback" style="padding: 80px 5%; background: var(--surface); border-top: 1px solid var(--border);">
    <div style="max-width: 600px; margin: 0 auto;">
      <h2 style="font-size: 2.5rem; margin-bottom: 16px; text-align: center;">Send Feedback</h2>
      <p style="text-align: center; color: var(--muted); margin-bottom: 40px;">Found a bug, have a feature suggestion, or just want to chat? Let us know!</p>
      <div class="alert" id="feedbackAlert"></div>
      <form id="feedbackForm" style="background: var(--card); padding: 30px; border-radius: 16px; border: 1px solid var(--border);">
        <div class="form-group">
          <label>Your Name</label>
          <input type="text" name="name" required placeholder="John Doe">
        </div>
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" required placeholder="name@example.com">
        </div>
        <div class="form-group">
          <label>Message</label>
          <textarea style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--border); background: var(--bg); color: var(--text); font-family: inherit; outline: none; resize: vertical; min-height: 120px;" name="message" required placeholder="Type your message here..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary form-submit">Submit Feedback</button>
      </form>
    </div>
  </section>

  <!-- Login Modal -->
  <div class="modal-overlay" id="loginModal">
    <div class="modal">
      <button class="close-btn" onclick="closeModal('loginModal')">&times;</button>
      <h2>Welcome Back</h2>
      <div class="alert" id="loginAlert"></div>
      <form id="loginForm">
        <input type="hidden" name="action" value="login">
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" required placeholder="name@example.com">
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" required placeholder="••••••••">
        </div>
        <button type="submit" class="btn btn-primary form-submit">Sign In</button>
      </form>
      <div class="form-footer">
        Don't have an account? <a onclick="switchModal('loginModal', 'registerModal')">Register now</a>
      </div>
    </div>
  </div>

  <!-- Register Modal -->
  <div class="modal-overlay" id="registerModal">
    <div class="modal">
      <button class="close-btn" onclick="closeModal('registerModal')">&times;</button>
      <h2>Create Account</h2>
      <div class="alert" id="regAlert"></div>
      <form id="registerForm">
        <input type="hidden" name="action" value="register">
        <div class="form-group">
          <label>Username</label>
          <input type="text" name="username" required placeholder="coder123" minlength="3">
        </div>
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" required placeholder="name@example.com">
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" required placeholder="Choose a strong password" minlength="6">
        </div>
        <button type="submit" class="btn btn-primary form-submit">Register & Start Learning</button>
      </form>
      <div class="form-footer">
        Already have an account? <a onclick="switchModal('registerModal', 'loginModal')">Sign In</a>
      </div>
    </div>
  </div>

  <footer style="text-align: center; padding: 40px 5%; color: var(--muted); border-top: 1px solid var(--border); margin-top: 60px;">
    <p>&copy; <?php echo date("Y"); ?> AlgoLens. Built for students, by students.</p>
  </footer>

  <script>
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

    function openModal(id) {
      document.getElementById(id).classList.add('active');
    }
    function closeModal(id) {
      document.getElementById(id).classList.remove('active');
      // Reset forms and alerts
      document.getElementById(id).querySelectorAll('form').forEach(f => f.reset());
      document.getElementById(id).querySelectorAll('.alert').forEach(a => a.style.display = 'none');
    }
    function switchModal(closeId, openId) {
      closeModal(closeId);
      openModal(openId);
    }
    
    // Close modal on outside click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
      overlay.addEventListener('click', (e) => {
        if(e.target === overlay) overlay.classList.remove('active');
      });
    });

    function handleAuth(formId, alertId) {
      document.getElementById(formId).addEventListener('submit', function(e) {
        e.preventDefault();
        const alertBox = document.getElementById(alertId);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        submitBtn.textContent = 'Processing...';
        submitBtn.disabled = true;

        fetch('auth.php', {
          method: 'POST',
          body: new FormData(this)
        })
        .then(res => res.json())
        .then(data => {
          alertBox.style.display = 'block';
          alertBox.className = 'alert ' + (data.success ? 'success' : 'error');
          alertBox.textContent = data.message;
          
          if(data.success && data.redirect) {
            setTimeout(() => window.location.href = data.redirect, 800);
          } else {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
          }
        })
        .catch(err => {
          alertBox.style.display = 'block';
          alertBox.className = 'alert error';
          alertBox.textContent = 'A network error occurred. Please try again.';
          submitBtn.textContent = originalText;
          submitBtn.disabled = false;
        });
      });
    }

    handleAuth('loginForm', 'loginAlert');
    handleAuth('registerForm', 'regAlert');
    
    // Feedback logic
    document.getElementById('feedbackForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const alertBox = document.getElementById('feedbackAlert');
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.textContent;
      
      submitBtn.textContent = 'Sending...';
      submitBtn.disabled = true;

      fetch('feedback.php', {
        method: 'POST',
        body: new FormData(this)
      })
      .then(res => res.json())
      .then(data => {
        alertBox.style.display = 'block';
        alertBox.className = 'alert ' + (data.success ? 'success' : 'error');
        alertBox.textContent = data.message;
        if(data.success) {
            this.reset();
            setTimeout(() => { alertBox.style.display = 'none'; }, 4000);
        }
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
      })
      .catch(err => {
        alertBox.style.display = 'block';
        alertBox.className = 'alert error';
        alertBox.textContent = 'A network error occurred. Please try again.';
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
      });
    });
  </script>
</body>
</html>
