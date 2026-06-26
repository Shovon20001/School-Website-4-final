<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/includes/config.php';

try {
    $db = getDB();
    
    // Test 1: Settings
    $settings = [];
    $result = $db->query("SELECT setting_key, setting_value FROM settings LIMIT 5");
    $count = count($result->fetchAll());
    
    // Test 2: Check admin user
    $admin = $db->query("SELECT username FROM admin_users WHERE username='admin'")->fetch();
    
    // Test 3: Check tables structure
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    echo json_encode([
        'status' => 'OK',
        'database' => 'Connected',
        'settings_count' => $count,
        'admin_exists' => !empty($admin),
        'tables' => $tables,
        'charset' => 'utf8mb4'
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
