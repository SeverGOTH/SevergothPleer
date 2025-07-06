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

// Все комментарии для глобального поста (ID=1)
$post_id = 1; 

// Подготавливаем SQL-запрос
$stmt = $conn->prepare("SELECT author, comment_text FROM comments WHERE post_id = ? ORDER BY created_at DESC LIMIT 10");
if ($stmt === false) {
    echo json_encode(["status" => "error", "message" => "Ошибка подготовки запроса: " . $conn->error]);
    exit();
}

// Привязываем параметр и выполняем запрос
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    echo json_encode(["status" => "success", "comments" => $comments]);
} else {
    echo json_encode(["status" => "success", "comments" => []]);
}

// Закрываем соединение
$stmt->close();
$conn->close();
?>