<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Конфигурация базы данных
$servername = "sql303.infinityfree.com";
$username = "if0_39312889";
$password = "PYzEnNlONQLl";
$dbname = "if0_39312889_gothic_player";  // Ваша новая база

// Создаем соединение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем соединение
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Ошибка подключения: " . $conn->connect_error]);
    exit();
}

// Проверяем, что запрос является POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Разрешены только POST-запросы"]);
    exit();
}

// Получаем данные из POST-запроса
$post_id = 1; // Глобальный пост для комментариев
$author = isset($_POST['author']) ? trim($_POST['author']) : '';
$comment_text = isset($_POST['comment_text']) ? trim($_POST['comment_text']) : '';

// Валидация данных
if (empty($author) || empty($comment_text)) {
    echo json_encode(["status" => "error", "message" => "Все поля обязательны для заполнения"]);
    exit();
}

if (strlen($comment_text) > 500) {
    echo json_encode(["status" => "error", "message" => "Комментарий слишком длинный (макс. 500 символов)"]);
    exit();
}

// Подготавливаем SQL-запрос
$stmt = $conn->prepare("INSERT INTO comments (post_id, author, comment_text) VALUES (?, ?, ?)");
if ($stmt === false) {
    echo json_encode(["status" => "error", "message" => "Ошибка подготовки запроса: " . $conn->error]);
    exit();
}

// Привязываем параметры и выполняем запрос
$stmt->bind_param("iss", $post_id, $author, $comment_text);
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Комментарий добавлен!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Ошибка выполнения запроса: " . $stmt->error]);
}

// Закрываем соединение
$stmt->close();
$conn->close();
?>