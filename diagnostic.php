<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/includes/config.php';

$errors = [];
$warnings = [];
$success = [];

try {
    $db = getDB();
    
    // Check 1: Database Connection
    $test = $db->query("SELECT 1")->fetch();
    $success[] = "✅ Database Connection: OK";
    
    // Check 2: Settings
    $settings = [];
    foreach ($db->query("SELECT setting_key, setting_value FROM settings")->fetchAll() as $r) {
        $settings[$r['setting_key']] = $r['setting_value'];
    }
    
    $required = ['school_name_bn', 'address', 'principal_name', 'email'];
    foreach ($required as $key) {
        if (empty($settings[$key])) {
            $errors[] = "❌ Missing: $key";
        } else {
            $success[] = "✅ Setting '$key': OK";
        }
    }
    
    // Check 3: Tables Data
    $tables = [
        'gallery' => 'Gallery Images',
        'notices' => 'Notices',
        'teachers' => 'Teachers',
        'results' => 'Results',
        'admin_users' => 'Admin Users'
    ];
    
    foreach ($tables as $table => $label) {
        $count = $db->query("SELECT COUNT(*) as cnt FROM $table")->fetch()['cnt'];
        if ($count > 0) {
            $success[] = "✅ $label: $count records";
        } else {
            $warnings[] = "⚠️  $label: Empty (0 records)";
        }
    }
    
    // Check 4: Files
    $required_files = [
        'assets/css/style.css',
        'assets/js/main.js',
        'admin/login.php',
        'api/index.php'
    ];
    
    $base = __DIR__;
    foreach ($required_files as $file) {
        if (file_exists("$base/$file")) {
            $success[] = "✅ File exists: $file";
        } else {
            $errors[] = "❌ Missing file: $file";
        }
    }
    
} catch (Exception $e) {
    $errors[] = "❌ Database Error: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Website Diagnostic Check</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .section { margin: 20px 0; }
        .error { color: #d32f2f; font-size: 14px; padding: 8px; background: #ffebee; margin: 5px 0; border-radius: 4px; }
        .warning { color: #f57c00; font-size: 14px; padding: 8px; background: #fff3e0; margin: 5px 0; border-radius: 4px; }
        .success { color: #388e3c; font-size: 14px; padding: 8px; background: #e8f5e9; margin: 5px 0; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Website Diagnostic Check</h1>
        
        <?php if (!empty($errors)): ?>
        <div class="section">
            <h2>Errors Found</h2>
            <?php foreach ($errors as $e): ?>
            <div class="error"><?= $e ?></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($warnings)): ?>
        <div class="section">
            <h2>Warnings</h2>
            <?php foreach ($warnings as $w): ?>
            <div class="warning"><?= $w ?></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
        <div class="section">
            <h2>All Good ✅</h2>
            <?php foreach ($success as $s): ?>
            <div class="success"><?= $s ?></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
