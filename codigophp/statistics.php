<?php
// statistics.php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Si el usuario es instructor, se muestran todas las estadísticas; de lo contrario, solo las propias.
if ($_SESSION['role'] == 'instructor') {
    $stmt = $pdo->query("SELECT c.title, COUNT(r.result_id) AS attempts, AVG(r.score) AS avg_score 
                         FROM cuestionarios c
                         LEFT JOIN resultados r ON c.quiz_id = r.quiz_id
                         GROUP BY c.quiz_id");
    $stats = $stmt->fetchAll();
} else {
    $stmt = $pdo->prepare("SELECT c.title, COUNT(r.result_id) AS attempts, AVG(r.score) AS avg_score 
                           FROM cuestionarios c
                           LEFT JOIN resultados r ON c.quiz_id = r.quiz_id
                           WHERE r.user_id = ?
                           GROUP BY c.quiz_id");
    $stmt->execute([$_SESSION['user_id']]);
    $stats = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Estadísticas del Cuestionario</title>
</head>
<body>
<h2>Estadísticas</h2>
<table border="1">
    <tr>
        <th>Cuestionario</th>
        <th>Intentos</th>
        <th>Puntuación Media</th>
    </tr>
    <?php foreach ($stats as $stat): ?>
        <tr>
            <td><?php echo htmlspecialchars($stat['title']); ?></td>
            <td><?php echo $stat['attempts'] ?: 0; ?></td>
            <td><?php echo number_format(isset($stat['avg_score']) ? $stat['avg_score'] : 0, 2); ?></td>

        </tr>
    <?php endforeach; ?>
</table>
<a href="dashboard.php">Volver al Dashboard</a>
</body>
</html>
