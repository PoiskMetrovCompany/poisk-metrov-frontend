<?php

$host = 'localhost';
$dbname = 'poisk_metrov_test';
$username = 'poiskmetrov';
$password = 'meters';
$dumpFile = __DIR__ . '/Data/dump.sql';

if (!file_exists($dumpFile)) {
    die("Файл дампа не найден: {$dumpFile}\n");
}

dump("Загрузка данных в тестовую базу данных...\n");
$command = "mysql -u {$username} -p{$password} {$dbname} < {$dumpFile}";
exec($command, $output, $returnVar);

if ($returnVar !== 0) {
    dump("Ошибка при импорте дампа:\n");
    dump(implode("\n", $output));
    exit(1);
}

dump("Дамп успешно импортирован!\n");
