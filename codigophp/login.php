<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            // Inicio de sesión correcto
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Credenciales inválidas.";
        }
    } else {
        $error = "Complete todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión</title>
</head>
<body>
<h2>Inicio de Sesión</h2>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    <label>Nombre de usuario: </label>
    <input type="text" name="username" required><br>
    <label>Contraseña: </label>
    <input type="password" name="password" required><br>
    <input type="submit" value="Iniciar Sesión">
</form>
<a href="register.php">Registrarse</a>
</body>
</html>
