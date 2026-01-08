<?php
require_once __DIR__ . '/../src/config.php';
// nandito na yung mga  basic CRUD for students
$action = $_GET['action'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($_POST['form'] === 'create') {
    $stmt = $pdo->prepare('INSERT INTO students (name,email) VALUES (?,?)');
    $stmt->execute([$_POST['name'], $_POST['email']]);
    header('Location: students.php'); exit;
  }
  if ($_POST['form'] === 'update') {
    $stmt = $pdo->prepare('UPDATE students SET name=?, email=? WHERE id=?');
    $stmt->execute([$_POST['name'], $_POST['email'], $_POST['id']]);
    header('Location: students.php'); exit;
  }
  if ($_POST['form'] === 'delete') {
    $stmt = $pdo->prepare('DELETE FROM students WHERE id=?');
    $stmt->execute([$_POST['id']]);
    header('Location: students.php'); exit;
  }
}
$students = $pdo->query('SELECT * FROM students ORDER BY created_at DESC')->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Students</title>
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
        <a href="courses.php">Courses</a>
        <a href="attendance.php">Attendance</a>
        <a href="schedule.php">Schedule Generator</a>
      </nav>
    </aside>
    <main class="main">
      <header class="topbar"><h2>Students</h2></header>
      <section class="content">
        <div class="card">
          <h3>Add Student</h3>
          <form method="post">
            <input type="hidden" name="form" value="create">
            <label>Name<input name="name" required></label>
            <label>Email<input name="email" type="email"></label>
            <button class="btn">Add</button>
          </form>
        </div>

        <div class="card">
          <h3>All Students</h3>
          <table class="table">
            <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach($students as $s): ?>
              <tr>
                <td><?=htmlspecialchars($s['id'])?></td>
                <td><?=htmlspecialchars($s['name'])?></td>
                <td><?=htmlspecialchars($s['email'])?></td>
                <td>
                  <button class="btn small" data-edit='<?=json_encode($s)?>'>Edit</button>
                  <form method="post" style="display:inline">
                    <input type="hidden" name="form" value="delete">
                    <input type="hidden" name="id" value="<?=$s['id']?>">
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
      <label>Name<input name="name" id="editName" required></label>
      <label>Email<input name="email" id="editEmail" type="email"></label>
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
  document.getElementById('editName').value = data.name;
  document.getElementById('editEmail').value = data.email;
  d.showModal();
});
document.getElementById('cancel').addEventListener('click', ()=>document.getElementById('editDialog').close());
</script>

