<?php
require_once __DIR__ . '/../src/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($_POST['form'] === 'create') {
    $stmt = $pdo->prepare('INSERT INTO courses (code,title,day_set,time_start,time_end) VALUES (?,?,?,?,?)');
    $stmt->execute([$_POST['code'], $_POST['title'], $_POST['day_set'], $_POST['time_start']?:null, $_POST['time_end']?:null]);
    header('Location: courses.php'); exit;
  }
  if ($_POST['form'] === 'update') {
    $stmt = $pdo->prepare('UPDATE courses SET code=?, title=?, day_set=?, time_start=?, time_end=? WHERE id=?');
    $stmt->execute([$_POST['code'], $_POST['title'], $_POST['day_set'], $_POST['time_start']?:null, $_POST['time_end']?:null, $_POST['id']]);
    header('Location: courses.php'); exit;
  }
  if ($_POST['form'] === 'delete') {
    $stmt = $pdo->prepare('DELETE FROM courses WHERE id=?');
    $stmt->execute([$_POST['id']]);
    header('Location: courses.php'); exit;
  }
}
$courses = $pdo->query('SELECT * FROM courses ORDER BY created_at DESC')->fetchAll();
$students = $pdo->query('SELECT * FROM students ORDER BY name')->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Courses</title>
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
        <a href="attendance.php">Attendance</a>
        <a href="schedule.php">Schedule Generator</a>
      </nav>
    </aside>
    <main class="main">
      <header class="topbar"><h2>Courses</h2></header>
      <section class="content">
        <div class="card">
          <h3>Create Course</h3>
          <form method="post">
            <input type="hidden" name="form" value="create">
            <label>Code<input name="code" required></label>
            <label>Title<input name="title" required></label>
            <label>Days (comma-separated)<input name="day_set" placeholder="Mon,Wed,Fri"></label>
            <label>Start time<input name="time_start" type="time"></label>
            <label>End time<input name="time_end" type="time"></label>
            <button class="btn">Create</button>
          </form>
        </div>

        <div class="card">
          <h3>All Courses</h3>
          <table class="table">
            <thead><tr><th>ID</th><th>Code</th><th>Title</th><th>Days</th><th>Time</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach($courses as $c): ?>
              <tr>
                <td><?=htmlspecialchars($c['id'])?></td>
                <td><?=htmlspecialchars($c['code'])?></td>
                <td><?=htmlspecialchars($c['title'])?></td>
                <td><?=htmlspecialchars($c['day_set'])?></td>
                <td><?=( $c['time_start'] ? $c['time_start'].' - '.$c['time_end'] : '-' )?></td>
                <td>
                  <button class="btn small" data-edit='<?=json_encode($c)?>'>Edit</button>
                  <form method="post" style="display:inline">
                    <input type="hidden" name="form" value="delete">
                    <input type="hidden" name="id" value="<?=$c['id']?>">
                    <button class="btn small danger">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>

      </section>
    </main>
  </div>

  <dialog id="editDialog">
    <form method="post">
      <input type="hidden" name="form" value="update">
      <input type="hidden" name="id" id="editId">
      <label>Code<input name="code" id="editCode" required></label>
      <label>Title<input name="title" id="editTitle" required></label>
      <label>Days<input name="day_set" id="editDays"></label>
      <label>Start time<input name="time_start" id="editStart" type="time"></label>
      <label>End time<input name="time_end" id="editEnd" type="time"></label>
      <menu>
        <button class="btn">Save</button>
        <button type="button" class="btn" id="cancel">Cancel</button>
      </menu>
    </form>
  </dialog>

</body>
</html>
<script>
document.addEventListener('click', e=>{
  const btn = e.target.closest('button[data-edit]');
  if(!btn) return;
  const data = JSON.parse(btn.getAttribute('data-edit'));
  const d = document.getElementById('editDialog');
  document.getElementById('editId').value = data.id;
  document.getElementById('editCode').value = data.code;
  document.getElementById('editTitle').value = data.title;
  document.getElementById('editDays').value = data.day_set;
  document.getElementById('editStart').value = data.time_start || '';
  document.getElementById('editEnd').value = data.time_end || '';
  d.showModal();
});
document.getElementById('cancel').addEventListener('click', ()=>document.getElementById('editDialog').close());
</script>
