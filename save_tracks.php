<?php
$trackFile = 'tracks.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    $title = $_POST['title'] ?? null;
    $url = $_POST['url'] ?? null;
    $index = isset($_POST['index']) ? (int)$_POST['index'] : null;

    if (!file_exists($trackFile)) {
        file_put_contents($trackFile, '');
    }

    $content = file_get_contents($trackFile);
    $tracks = array_filter(preg_split('/\r\n|\r|\n/', $content));
    
    switch ($action) {
        case 'add':
            if ($title && $url) {
                $tracks[] = $title . '|' . $url;
                file_put_contents($trackFile, implode("\n", $tracks));
                echo "✅ Трек добавлен";
            } else {
                echo "❌ Неверные данные";
            }
            break;

        case 'delete':
            if (isset($index) && array_key_exists($index, $tracks)) {
                array_splice($tracks, $index, 1);
                file_put_contents($trackFile, implode("\n", $tracks));
                echo "🗑️ Трек удален";
            } else {
                echo "❌ Не удалось удалить трек";
            }
            break;

        default:
            echo "⚠️ Неизвестное действие";
    }
} else {
    echo "❌ Доступ запрещен";
}
?>