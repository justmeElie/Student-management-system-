<?php
require_once __DIR__ . '/../src/config.php';
$student_id = $_GET['student_id'] ?? null;
$students = $pdo->query('SELECT * FROM students ORDER BY name')->fetchAll();
$schedule = [];
if ($student_id) {
  $stmt = $pdo->prepare('SELECT c.* FROM courses c JOIN enrollments e ON e.course_id=c.id WHERE e.student_id=?');
  $stmt->execute([$student_id]);
  $courses = $stmt->fetchAll();
 
  foreach($courses as $c) {
    $days = array_map('trim', explode(',', $c['day_set']));
    foreach($days as $d) {
      if(!$d) continue;
      $schedule[] = ['day'=>$d, 'code'=>$c['code'], 'title'=>$c['title'], 'start'=>$c['time_start'], 'end'=>$c['time_end']];
    }
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Schedule Generator</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="style/main.css" rel="stylesheet">
  <script src="/assets/schedule.js" defer></script>
</head>
<body>
  <div class="app-grid">
    <aside class="sidebar">
      <h1 class="brand">SM</h1>
      <nav>
        <a href="index.php">Dashboard</a>
        <a href="students.php">Students</a>
        <a href="courses.php">Courses</a>
        <a href="attendance.php">Attendance</a>
      </nav>
    </aside>
    <main class="main">
      <header class="topbar"><h2>Schedule Generator</h2></header>
      <section class="content">
        <div class="card">
          <form method="get">
            <label>Student<select name="student_id"><option value="">--select--</option><?php foreach($students as $s):?><option value="<?=$s['id']?>" <?=( $student_id==$s['id'] ? 'selected' : '')?>><?=htmlspecialchars($s['name'])?></option><?php endforeach;?></select></label>
            <button class="btn">Generate</button>
          </form>
        </div>

        <div class="card">
          <h3>Weekly Schedule</h3>
          <div id="scheduleGrid" class="schedule-grid"></div>
        </div>

      </section>
    </main>
  </div>
</body>
</html>

