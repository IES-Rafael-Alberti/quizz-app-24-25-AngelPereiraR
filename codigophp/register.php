<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role     = isset($_POST['role']) ? $_POST['role'] : 'student';

    if (!empty($username) && !empty($password)) {
        // Verificar si el usuario ya existe
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $error = "El nombre de usuario ya existe.";
        } else {
            // Insertar nuevo usuario
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (username, password, role) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hash, $role]);
            header("Location: login.php");
            exit;
        }
    } else {
        $error = "Por favor, complete todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>
<h2>Registro</h2>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    <label>Nombre de usuario: </label>
    <input type="text" name="username" required><br>
    <label>Contraseña: </label>
    <input type="password" name="password" required><br>
    <label>Rol: </label>
    <select name="role">
        <option value="student">Estudiante</option>
        <option value="instructor">Instructor</option>
    </select><br>
    <input type="submit" value="Registrarse">
</form>
<a href="login.php">Iniciar sesión</a>
</body>
</html>
