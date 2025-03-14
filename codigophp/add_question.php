<?php
// add_question.php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor') {
    header("Location: login.php");
    exit;
}

$quiz_id = $_GET['quiz_id'] ?? null;
if (!$quiz_id) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question_text  = trim($_POST['question_text']);
    $option_a       = trim($_POST['option_a']);
    $option_b       = trim($_POST['option_b']);
    $option_c       = trim($_POST['option_c']);
    $option_d       = trim($_POST['option_d']);
    $correct_option = $_POST['correct_option'];

    if (!empty($question_text) && !empty($option_a) && !empty($option_b) && !empty($option_c) && !empty($option_d)
        && in_array($correct_option, ['A','B','C','D'])) {
        $stmt = $pdo->prepare("INSERT INTO preguntas (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$quiz_id, $question_text, $option_a, $option_b, $option_c, $option_d, $correct_option]);
        $success = "Pregunta agregada exitosamente.";
    } else {
        $error = "Complete todos los campos correctamente.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Añadir Preguntas</title>
</head>
<body>
<h2>Añadir Preguntas al Cuestionario</h2>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
<form method="post">
    <label>Texto de la Pregunta: </label>
    <textarea name="question_text" required></textarea><br>
    <label>Opción A: </label>
    <input type="text" name="option_a" required><br>
    <label>Opción B: </label>
    <input type="text" name="option_b" required><br>
    <label>Opción C: </label>
    <input type="text" name="option_c" required><br>
    <label>Opción D: </label>
    <input type="text" name="option_d" required><br>
    <label>Opción Correcta: </label>
    <select name="correct_option">
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <option value="D">D</option>
    </select><br>
    <input type="submit" value="Añadir Pregunta">
</form>
<br>
<a href="dashboard.php">Volver al Dashboard</a>
</body>
</html>
