<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/includes/config.php';
$db = getDB();

// Get settings
$settings = [];
foreach ($db->query("SELECT setting_key, setting_value FROM settings")->fetchAll() as $r)
    $settings[$r['setting_key']] = $r['setting_value'];

echo "<pre style='background:#222;color:#0f0;padding:20px;font-family:monospace;'>";
echo "=== SCHOOL INFORMATION ===\n\n";

echo "School Name (Bengali): " . $settings['school_name_bn'] . "\n";
echo "School Name (English): " . $settings['school_name_en'] . "\n";
echo "Established Year: " . $settings['established_year'] . "\n";
echo "Address: " . $settings['address'] . "\n";
echo "Phone: " . $settings['phone1'] . "\n";
echo "Email: " . $settings['email'] . "\n";
echo "Principal: " . $settings['principal_name'] . "\n";
echo "Motto: " . $settings['motto'] . "\n";
echo "Campus: " . $settings['campus_size'] . "\n";
echo "Hours: " . $settings['school_hours'] . "\n";

echo "\n=== DATA COUNTS ===\n\n";
echo "Gallery: " . $db->query("SELECT COUNT(*) FROM gallery WHERE is_active=1")->fetchColumn() . "\n";
echo "Notices: " . $db->query("SELECT COUNT(*) FROM notices WHERE is_active=1")->fetchColumn() . "\n";
echo "Teachers: " . $db->query("SELECT COUNT(*) FROM teachers WHERE is_active=1")->fetchColumn() . "\n";
echo "Results: " . $db->query("SELECT COUNT(*) FROM results WHERE is_active=1")->fetchColumn() . "\n";
echo "Admins: " . $db->query("SELECT COUNT(*) FROM admin_users")->fetchColumn() . "\n";

echo "\n=== FILE CHECKS ===\n\n";
$files = ['assets/css/style.css', 'assets/js/main.js', 'assets/images/logo.png', 'assets/images/school-bg.jpg.jpg'];
foreach ($files as $f) {
    $exists = file_exists(__DIR__ . '/' . $f) ? 'YES' : 'NO';
    $size = file_exists(__DIR__ . '/' . $f) ? filesize(__DIR__ . '/' . $f) . ' bytes' : 'N/A';
    echo "$f: $exists ($size)\n";
}

echo "\n=== UPLOAD DIRECTORY ===\n\n";
$uploads = glob(__DIR__ . '/uploads/gallery/*');
echo "Images in gallery: " . count($uploads) . "\n";
foreach ($uploads as $img) {
    echo "  - " . basename($img) . "\n";
}

echo "\n=== ADMIN USER ===\n\n";
$admin = $db->query("SELECT username, name FROM admin_users")->fetch();
echo "Username: " . ($admin['username'] ?? 'NOT FOUND') . "\n";
echo "Name: " . ($admin['name'] ?? 'NOT FOUND') . "\n";

echo "\n=== CHARACTER SET ===\n\n";
echo "Database: " . $db->query("SELECT @@character_set_database")->fetchColumn() . "\n";
echo "Connection: utf8mb4\n";

echo "\n</pre>";
?>
