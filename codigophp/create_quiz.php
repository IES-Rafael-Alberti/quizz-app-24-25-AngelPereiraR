<?php
// create_quiz.php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    if (!empty($title)) {
        $stmt = $pdo->prepare("INSERT INTO cuestionarios (title, description, created_by) VALUES (?, ?, ?)");
        $stmt->execute([$title, $description, $_SESSION['user_id']]);
        $quiz_id = $pdo->lastInsertId();
        header("Location: add_question.php?quiz_id=$quiz_id");
        exit;
    } else {
        $error = "El título es obligatorio.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Crear Cuestionario</title>
</head>
<body>
<h2>Crear Cuestionario</h2>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    <label>Título: </label>
    <input type="text" name="title" required><br>
    <label>Descripción: </label>
    <textarea name="description"></textarea><br>
    <input type="submit" value="Crear Cuestionario">
</form>
</body>
</html>
