<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/includes/config.php';
$db = getDB();

$settings = [];
foreach ($db->query("SELECT setting_key, setting_value FROM settings")->fetchAll() as $r)
    $settings[$r['setting_key']] = $r['setting_value'];

echo "<pre>";
echo "School Name BN: " . htmlspecialchars($settings['school_name_bn'] ?? 'NOT SET') . "\n";
echo "Address: " . htmlspecialchars($settings['address'] ?? 'NOT SET') . "\n";
echo "Established Year: " . htmlspecialchars($settings['established_year'] ?? 'NOT SET') . "\n";
echo "School Code: " . htmlspecialchars($settings['school_code'] ?? 'NOT SET') . "\n";
echo "</pre>";
?>
