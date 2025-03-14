<?php
// quiz.php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$quiz_id = $_GET['quiz_id'] ?? null;
if (!$quiz_id) {
    header("Location: take_quiz.php");
    exit;
}

// Recuperar los detalles del cuestionario
$stmt = $pdo->prepare("SELECT * FROM cuestionarios WHERE quiz_id = ?");
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch();
if (!$quiz) {
    die("Cuestionario no encontrado.");
}

// Recuperar las preguntas del cuestionario
$stmt = $pdo->prepare("SELECT * FROM preguntas WHERE quiz_id = ?");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Procesar respuestas y calcular la puntuación
    $score = 0;
    foreach ($questions as $question) {
        $q_id = $question['question_id'];
        $user_answer = $_POST["answer_$q_id"] ?? '';
        if ($user_answer == $question['correct_option']) {
            $score++;
        }
    }
    // Guardar el resultado
    $stmt = $pdo->prepare("INSERT INTO resultados (user_id, quiz_id, score) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $quiz_id, $score]);

    // Preparar retroalimentación
    $total = count($questions);
    $feedback = "Puntuación: $score de $total.";
    $detailed_feedback = [];
    foreach ($questions as $question) {
        $q_id = $question['question_id'];
        $user_answer = $_POST["answer_$q_id"] ?? '';
        $correct = ($user_answer == $question['correct_option']);
        $detailed_feedback[] = [
            'question'      => $question['question_text'],
            'your_answer'   => $user_answer,
            'correct_answer'=> $question['correct_option'],
            'result'        => $correct ? 'Correcto' : 'Incorrecto'
        ];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($quiz['title']); ?></title>
</head>
<body>
<h2><?php echo htmlspecialchars($quiz['title']); ?></h2>
<p><?php echo htmlspecialchars($quiz['description']); ?></p>

<?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
    <h3>Resultados</h3>
    <p><?php echo $feedback; ?></p>
    <h4>Detalle de respuestas:</h4>
    <ul>
        <?php foreach($detailed_feedback as $fb): ?>
            <li>
                <strong><?php echo htmlspecialchars($fb['question']); ?></strong><br>
                Tu respuesta: <?php echo htmlspecialchars($fb['your_answer']); ?> -
                Respuesta correcta: <?php echo htmlspecialchars($fb['correct_answer']); ?> -
                <?php echo $fb['result']; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="take_quiz.php">Volver a seleccionar otro cuestionario</a>
<?php else: ?>
    <form method="post">
        <?php foreach ($questions as $index => $question): ?>
            <div>
                <p><?php echo ($index+1) . ". " . htmlspecialchars($question['question_text']); ?></p>
                <input type="radio" name="answer_<?php echo $question['question_id']; ?>" value="A" required> A) <?php echo htmlspecialchars($question['option_a']); ?><br>
                <input type="radio" name="answer_<?php echo $question['question_id']; ?>" value="B"> B) <?php echo htmlspecialchars($question['option_b']); ?><br>
                <input type="radio" name="answer_<?php echo $question['question_id']; ?>" value="C"> C) <?php echo htmlspecialchars($question['option_c']); ?><br>
                <input type="radio" name="answer_<?php echo $question['question_id']; ?>" value="D"> D) <?php echo htmlspecialchars($question['option_d']); ?><br>
            </div>
            <hr>
        <?php endforeach; ?>
        <input type="submit" value="Enviar Respuestas">
    </form>
<?php endif; ?>
</body>
</html>
