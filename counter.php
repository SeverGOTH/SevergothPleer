<?php
$counterFile = 'counter.txt';

// Убедитесь, что файл счетчика существует, если нет, создайте его и установите 0
if (!file_exists($counterFile)) {
    file_put_contents($counterFile, '0');
}

// Получаем текущее значение счетчика
$currentCount = (int)file_get_contents($counterFile);

// Увеличиваем счетчик на 1
$newCount = $currentCount + 1;

// Записываем новое значение обратно в файл
file_put_contents($counterFile, $newCount);

// Выводим новое значение счетчика
echo $newCount;
?>