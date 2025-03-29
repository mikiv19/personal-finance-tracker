<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/config.php';

// Full database reset
$db->exec('DROP SCHEMA public CASCADE');
$db->exec('CREATE SCHEMA public');
$db->exec(file_get_contents(__DIR__ . '/../database/schema.sql'));

// Optional: Add test data
$db->exec("INSERT INTO users (email, password_hash) VALUES ('test@example.com', '".password_hash('password123', PASSWORD_DEFAULT)."')");