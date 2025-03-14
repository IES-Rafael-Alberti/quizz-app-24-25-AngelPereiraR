<?php
// dashboard.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
<h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
<?php if($_SESSION['role'] == 'instructor'): ?>
    <a href="create_quiz.php">Crear Cuestionario</a><br>
<?php endif; ?>
<a href="take_quiz.php">Realizar Cuestionario</a><br>
<a href="statistics.php">Ver Estadísticas</a><br>
<a href="logout.php">Cerrar Sesión</a>
</body>
</html>
