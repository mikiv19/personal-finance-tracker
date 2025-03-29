
<?php
require __DIR__ . '/../app/config.php';
try {
    $stmt = $db->query("SELECT version()");
    echo "PostgreSQL Version: " . $stmt->fetchColumn();
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}