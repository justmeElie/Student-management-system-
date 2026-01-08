<?php
require_once __DIR__ . '/../src/config.php';
$errors = [];

$success = isset($_GET['success']) ? true : false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($_POST['form'] === 'timein') {
    $student_id = $_POST['student_id'] ?? null;
    $date = $_POST['date'] ?? null;
    $time = $_POST['time'] ?? null;
    if (!$student_id) $errors[] = 'Please select a student.';
    if (!$date) $errors[] = 'Please select a date.';
    if (!$time) $errors[] = 'Please select a time.';
    if (empty($errors)) {
      $stmt = $pdo->prepare('INSERT INTO attendance (student_id, `date`, time_in) VALUES (?,?,?)');
      $stmt->execute([$student_id, $date, $time]);
      header('Location: attendance.php?success=1'); exit;
    }
  }
  if ($_POST['form'] === 'timeout') {

    $id = $_POST['id'] ?? null;
    if (!$id) $errors[] = 'Missing attendance record id.';
    if (empty($errors)) {
      $stmt = $pdo->prepare('UPDATE attendance SET time_out = NOW() WHERE id = ?');
      $stmt->execute([$id]);
      header('Location: attendance.php?success=1'); exit;
    }
  }
}
$students = $pdo->query('SELECT * FROM students ORDER BY name')->fetchAll();
$today = date('Y-m-d');
$now_local = date('Y-m-d\TH:i');
$records = $pdo->prepare('SELECT a.*, s.name FROM attendance a JOIN students s ON s.id=a.student_id ORDER BY a.`date` DESC, a.time_in DESC');
$records->execute();
$records = $records->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Attendance</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="style/main.css" rel="stylesheet">
  <script src="/assets/app.js" defer></script>
</head>
<body>
  <div class="app-grid">
    <aside class="sidebar">
      <h1 class="brand">SM</h1>
      <nav>
        <a href="index.php">Dashboard</a>
        <a href="students.php">Students</a>
        <a href="courses.php">Courses</a>
        <a href="schedule.php">Schedule Generator</a>
      </nav>
    </aside>
    <main class="main">
      <header class="topbar"><h2>Attendance</h2></header>
      <section class="content">
        <div class="card">
          <h3>Time In</h3>

          <?php if (!empty($errors)): ?>
            <div class="notice error">
              <strong>There were problems with your submission:</strong>
              <ul>
                <?php foreach($errors as $e): ?><li><?=htmlspecialchars($e)?></li><?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <?php if ($success): ?>
            <div class="notice success">Time recorded successfully.</div>
          <?php endif; ?>

          <form method="post" novalidate>
            <input type="hidden" name="form" value="timein">
            <div class="form-grid">
              <div>
                <label>Student
                  <select name="student_id" required>
                    <option value="">-- select --</option>
                    <?php foreach($students as $s):?>
                      <option value="<?=$s['id']?>" <?=(isset($_POST['student_id']) && $_POST['student_id']==$s['id'])?'selected':''?>><?=htmlspecialchars($s['name'])?></option>
                    <?php endforeach;?>
                  </select>
                </label>
              </div>

              <div>
                <label>Date
                  <input name="date" type="date" value="<?=htmlspecialchars($_POST['date'] ?? $today)?>" required>
                </label>
              </div>

              <div class="full">
                <label>Time
                  <input name="time" type="datetime-local" value="<?=htmlspecialchars($_POST['time'] ?? $now_local)?>" required>
                </label>
              </div>

              <div class="full">
                <button class="btn" type="submit">Time In</button>
              </div>
            </div>
          </form>
        </div>

        <div class="card">
          <h3>Records</h3>
          <div class="table-responsive">
          <table class="table">
            <thead><tr><th>ID</th><th>Student</th><th>Date</th><th>In</th><th>Out</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach($records as $r): ?>
              <tr>
                <td><?=$r['id']?></td>
                <td><?=htmlspecialchars($r['name'])?></td>
                <td><?=$r['date']?></td>
                <td><?=$r['time_in']?></td>
                <td><?=$r['time_out']?:'-'?></td>
                <td>
                  <?php if(!$r['time_out']): ?>
                  <form method="post" style="display:inline">
                    <input type="hidden" name="form" value="timeout">
                    <input type="hidden" name="id" value="<?=$r['id']?>">
                    <button class="btn small">Time Out</button>
                  </form>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
          </div>
        </div>

      </section>
    </main>
  </div>
</body>
</html>

