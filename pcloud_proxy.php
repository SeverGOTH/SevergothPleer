<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

// Ссылка на публичную папку pCloud
$pcloud_folder_url = 'https://e.pcloud.link/publink/show?code=kZVPn6ZlGdFGPzdD74GxvmpwORzQBcLM24X';

// Используем cURL для имитации браузера
$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL            => $pcloud_folder_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS      => 10,
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
]);

$html = curl_exec($ch);

if ($html === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка загрузки pCloud: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Парсим HTML
$dom = new DOMDocument();
@$dom->loadHTML($html);
$xpath = new DOMXPath($dom);

// Ищем ссылки на аудиофайлы
$nodes = $xpath->query("//a[contains(@href, '.mp3') or contains(@href, '.flac')]");

$tracks = [];

foreach ($nodes as $node) {
    $url = $node->getAttribute('href');

    // Добавляем домен pCloud, если URL относительный
    if (strpos($url, 'http') !== 0) {
        $url = ' https://e.pcloud.link ' . $url;
    }

    // Убираем параметры после знака "#" или "?"
    $url = strtok($url, '#');
    $url = strtok($url, '?');

    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $title = basename($url);
        $tracks[] = ['title' => $title, 'url' => $url];
    }
}

if (empty($tracks)) {
    http_response_code(404);
    echo json_encode(['error' => 'Аудиофайлы не найдены']);
    exit;
}

echo json_encode(['tracks' => $tracks]);
?>