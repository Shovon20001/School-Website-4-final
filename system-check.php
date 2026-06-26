<?php
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/includes/config.php';

$issues = [];
$passes = [];

try {
    // Test 1: Database Connection
    $db = getDB();
    $test = $db->query("SELECT 1")->fetch();
    $passes[] = "✅ Database Connection";
} catch (Exception $e) {
    $issues[] = "❌ Database Connection: " . $e->getMessage();
}

try {
    // Test 2: Settings Load
    $settings = [];
    $res = $db->query("SELECT setting_key, setting_value FROM settings");
    $count = 0;
    foreach ($res->fetchAll() as $r) {
        $settings[$r['setting_key']] = $r['setting_value'];
        $count++;
    }
    if ($count > 0) {
        $passes[] = "✅ Settings Loaded: $count records";
    } else {
        $issues[] = "❌ Settings Empty";
    }
} catch (Exception $e) {
    $issues[] = "❌ Settings Load Error: " . $e->getMessage();
}

// Test 3: Check Key Settings
$required_keys = ['school_name_bn', 'address', 'principal_name'];
foreach ($required_keys as $key) {
    if (!empty($settings[$key])) {
        $passes[] = "✅ Setting '$key': " . substr($settings[$key], 0, 30) . "...";
    } else {
        $issues[] = "❌ Missing Setting: $key";
    }
}

// Test 4: Gallery
try {
    $gallery = $db->query("SELECT COUNT(*) FROM gallery WHERE is_active=1")->fetchColumn();
    $passes[] = "✅ Gallery Images: $gallery";
} catch (Exception $e) {
    $issues[] = "❌ Gallery Error: " . $e->getMessage();
}

// Test 5: Notices
try {
    $notices = $db->query("SELECT COUNT(*) FROM notices WHERE is_active=1")->fetchColumn();
    $passes[] = "✅ Notices: $notices";
} catch (Exception $e) {
    $issues[] = "❌ Notices Error: " . $e->getMessage();
}

// Test 6: Teachers
try {
    $teachers = $db->query("SELECT COUNT(*) FROM teachers WHERE is_active=1")->fetchColumn();
    $passes[] = "✅ Teachers: $teachers";
} catch (Exception $e) {
    $issues[] = "❌ Teachers Error: " . $e->getMessage();
}

// Test 7: Files Exist
$files = [
    'assets/css/style.css',
    'assets/js/main.js',
    'admin/login.php',
    'api/index.php'
];

foreach ($files as $f) {
    if (file_exists(__DIR__ . '/' . $f)) {
        $size = filesize(__DIR__ . '/' . $f);
        $passes[] = "✅ File Exists: $f (" . round($size/1024, 2) . " KB)";
    } else {
        $issues[] = "❌ Missing File: $f";
    }
}

// Test 8: Admin User
try {
    $admin = $db->query("SELECT username FROM admin_users WHERE username='admin'")->fetch();
    if ($admin) {
        $passes[] = "✅ Admin User: admin";
    } else {
        $issues[] = "❌ Admin User 'admin' not found";
    }
} catch (Exception $e) {
    $issues[] = "❌ Admin Query Error: " . $e->getMessage();
}

// Test 9: Charset
try {
    $charset = $db->query("SELECT @@character_set_database")->fetchColumn();
    $passes[] = "✅ Database Charset: $charset";
} catch (Exception $e) {
    $issues[] = "❌ Charset Check Error: " . $e->getMessage();
}

// Test 10: Image Directory
if (is_dir(__DIR__ . '/uploads/gallery')) {
    $images = count(glob(__DIR__ . '/uploads/gallery/*'));
    $passes[] = "✅ Upload Directory: $images images";
} else {
    $issues[] = "❌ Upload Directory Missing";
}

?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>System Check</title>
    <style>
        * { margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 30px; text-align: center; }
        .section { margin: 20px 0; }
        .section-title { font-size: 18px; font-weight: bold; color: #333; margin: 15px 0 10px 0; border-bottom: 2px solid #007bff; padding-bottom: 5px; }
        .item { padding: 10px; margin: 5px 0; border-radius: 5px; font-size: 14px; }
        .pass { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
        .fail { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }
        .summary { font-size: 16px; font-weight: bold; margin: 20px 0; padding: 15px; border-radius: 5px; }
        .summary-pass { background: #d4edda; color: #155724; }
        .summary-fail { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Website System Check</h1>

        <?php if (!empty($issues)): ?>
        <div class="section">
            <div class="section-title">⚠️ Issues Found (<?= count($issues) ?>)</div>
            <?php foreach ($issues as $issue): ?>
            <div class="item fail"><?= htmlspecialchars($issue) ?></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="section">
            <div class="section-title">✅ Checks Passed (<?= count($passes) ?>)</div>
            <?php foreach ($passes as $pass): ?>
            <div class="item pass"><?= htmlspecialchars($pass) ?></div>
            <?php endforeach; ?>
        </div>

        <div class="summary <?= empty($issues) ? 'summary-pass' : 'summary-fail' ?>">
            <?php 
            if (empty($issues)) {
                echo "🎉 All checks passed! Website is ready to use.";
            } else {
                echo "⚠️ Please fix the " . count($issues) . " issue(s) above.";
            }
            ?>
        </div>

        <div style="margin-top: 30px; padding: 15px; background: #e7f3ff; border-radius: 5px;">
            <h3 style="color: #0066cc; margin-bottom: 10px;">📍 Next Steps:</h3>
            <ul style="margin-left: 20px;">
                <li><strong>Homepage:</strong> <a href="/">http://127.0.0.1:8000</a></li>
                <li><strong>Admin Panel:</strong> <a href="/admin/login.php">http://127.0.0.1:8000/admin/login.php</a></li>
                <li><strong>Credentials:</strong> admin / password</li>
                <li><strong>Test Pages:</strong> <a href="/test-all.php">/test-all.php</a>, <a href="/check.php">/check.php</a></li>
            </ul>
        </div>
    </div>
</body>
</html>
