<?php
$hosts = ['127.0.0.1', 'localhost'];
$passwords = ['', 'root', 'admin', 'password', '123456', '12345678'];

foreach ($hosts as $host) {
    foreach ($passwords as $pwd) {
        try {
            $pdo = new PDO("mysql:host=$host;port=3306", 'root', $pwd);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec('CREATE DATABASE IF NOT EXISTS tourraja CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
            echo "SUCCESS: Connected to host '$host' with password '$pwd'. Database 'tourraja' checked/created.\n";
            exit(0);
        } catch (Exception $e) {
            // Keep trying
        }
    }
}

echo "ERROR: Could not connect to MySQL root with localhost or 127.0.0.1.\n";
