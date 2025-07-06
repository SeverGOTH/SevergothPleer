<?php
// Включение отображения ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Данные подключения
$servername = "sql303.infinityfree.com";
$username = "if0_39312889";
$password = "PYzEnNlONQLl";
$dbname = "if0_39312889_gothic_player";

echo "<h3>Тестирование подключения к базе данных</h3>";
echo "Сервер: $servername<br>";
echo "Пользователь: $username<br>";
echo "База данных: $dbname<br><br>";

// Подключение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("<div style='color:red; font-weight:bold;'>Ошибка подключения: " . $conn->connect_error . "</div>");
}
echo "<div style='color:green; font-weight:bold;'>✅ Успешное подключение к базе данных!</div><br>";

// Проверка таблицы comments
$result = $conn->query("SHOW TABLES LIKE 'comments'");
if ($result->num_rows > 0) {
    echo "✅ Таблица 'comments' существует<br>";
    
    // Проверка вставки данных
    $sql = "INSERT INTO comments (author, comment_text) VALUES ('Тестовый пользователь', 'Это тестовый комментарий')";
    if ($conn->query($sql)) {
        echo "✅ Тестовая запись успешно добавлена!<br>";
        
        // Удаление тестовой записи
        $conn->query("DELETE FROM comments WHERE author = 'Тестовый пользователь'");
        echo "✅ Тестовая запись удалена";
    } else {
        echo "<div style='color:red;'>❌ Ошибка при добавлении записи: " . $conn->error . "</div>";
    }
} else {
    echo "<div style='color:red;'>❌ Таблица 'comments' не существует!</div>";
}

$conn->close();
?>