<?php

// The package's own vendor, or the one of the project it is installed into
$vendor = is_dir(__DIR__ . '/../vendor') ? __DIR__ . '/../vendor' : __DIR__ . '/../../..';
require_once $vendor . '/autoload.php';
require_once $vendor . '/yiisoft/yii2/Yii.php';

spl_autoload_register(static function (string $class): void {
    $prefixes = [
        'hiqdev\\recon\\dns\\tests\\' => __DIR__ . '/',
        'hiqdev\\recon\\dns\\' => __DIR__ . '/../src/',
    ];
    foreach ($prefixes as $prefix => $dir) {
        if (strpos($class, $prefix) === 0) {
            $file = $dir . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
            if (is_file($file)) {
                require $file;
            }

            return;
        }
    }
});
