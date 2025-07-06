<?php
// Включить все ошибки
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "1. Скрипт начал работу<br>";

// Настройки БД
$db_host = 'sql303.infinityfree.com';
$db_user = 'if0_39312889';
$db_pass = 'PYzEnNlONQLl';
$db_name = 'if0_39312889_gothic_player';

// 1. Создание папки для бэкапов
$backup_dir = 'backups';
echo "2. Проверяем папку: $backup_dir<br>";

if (!is_dir($backup_dir)) {
    if (mkdir($backup_dir, 0755, true)) {
        echo "3. Папка создана успешно<br>";
    } else {
        die("ОШИБКА: Не удалось создать папку '$backup_dir'");
    }
} else {
    echo "3. Папка уже существует<br>";
}

// 2. Проверка подключения к БД
echo "4. Пробуем подключиться к БД...<br>";
try {
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    if ($mysqli->connect_error) {
        throw new Exception("ОШИБКА подключения: " . $mysqli->connect_error);
    }
    echo "5. Подключение к БД успешно!<br>";
    $mysqli->close();
} catch (Exception $e) {
    die($e->getMessage());
}

// 3. Проверка ZIP расширения
echo "6. Проверяем доступность ZipArchive...<br>";
if (class_exists('ZipArchive')) {
    echo "7. ZipArchive доступен<br>";
} else {
    echo "7. ВНИМАНИЕ: ZipArchive недоступен<br>";
}

echo "8. Скрипт завершен успешно!";
?>