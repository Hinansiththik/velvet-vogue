<?php
$env = file(__DIR__ . '/../.env');

foreach ($env as $line) {
    $line = trim($line);

    if ($line === '' || strpos($line, '#') === 0) {
        continue;
    }

    list($key, $value) = explode('=', $line, 2);
    $_ENV[$key] = $value;
}
?>