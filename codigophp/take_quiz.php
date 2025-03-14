<?php
// take_quiz.php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Recuperar los cuestionarios disponibles
$stmt = $pdo->query("SELECT * FROM cuestionarios");
$quizzes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Realizar Cuestionario</title>
</head>
<body>
<h2>Seleccione un Cuestionario</h2>
<ul>
    <?php foreach($quizzes as $quiz): ?>
        <li>
            <a href="quiz.php?quiz_id=<?php echo $quiz['quiz_id']; ?>">
                <?php echo htmlspecialchars($quiz['title']); ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
<a href="dashboard.php">Volver al Dashboard</a>
</body>
</html>
