<?php
require_once __DIR__ . '/../src/config.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Student Management</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="style/main.css" rel="stylesheet">
  <script src="/assets/app.js" defer></script>
</head>
<body>
  <div class="app-grid">
    <aside class="sidebar">
      <h1 class="brand">SM</h1>
      <nav>
        <a href="students.php"><span class="material-icons">people</span>Students</a>
        <a href="courses.php"><span class="material-icons">menu_book</span>Courses</a>
        <a href="attendance.php"><span class="material-icons">schedule</span>Attendance</a>
        <a href="schedule.php"><span class="material-icons">calendar_today</span>Schedule Generator</a>
      </nav>
    </aside>
    <main class="main">
      <header class="topbar">
        <h2>Dashboard</h2>
      </header>
      <section class="content">
        <div class="card fade-in">
          <h3>Welcome</h3>
          <p>Use the menu to manage students, courses, attendance, and generate schedules.</p>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
