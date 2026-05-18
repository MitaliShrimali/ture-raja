<?php
$my_ini_path = 'C:/xampp/mysql/bin/my.ini';
if (file_exists($my_ini_path)) {
    $content = file_get_contents($my_ini_path);
    // Find all occurrences of port
    if (preg_match_all("/port\s*=\s*(\d+)/i", $content, $m)) {
        echo "Found ports in my.ini: " . implode(', ', $m[1]) . "\n";
    }
}

$config_inc = 'C:/xampp/phpMyAdmin/config.inc.php';
if (file_exists($config_inc)) {
    $content = file_get_contents($config_inc);
    if (preg_match("/\['port'\]\s*=\s*['\"](.*?)['\"]/i", $content, $m)) {
        echo "Found port in config.inc.php: '" . $m[1] . "'\n";
    }
}

// Try connecting to 3306 and 3307 with empty password and 'root'
$ports = [3306, 3307, 3308];
$passwords = ['', 'root', 'admin', 'password'];

foreach ($ports as $port) {
    foreach ($passwords as $pwd) {
        try {
            $pdo = new PDO("mysql:host=127.0.0.1;port=$port", 'root', $pwd);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec('CREATE DATABASE IF NOT EXISTS tourraja CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
            echo "SUCCESS: Connected on port $port with password '$pwd'. Database 'tourraja' checked/created.\n";
            exit(0);
        } catch (Exception $e) {
            // echo "Failed on port $port, pwd '$pwd': " . $e->getMessage() . "\n";
        }
    }
}

echo "ERROR: Port scanning / connection failed.\n";
