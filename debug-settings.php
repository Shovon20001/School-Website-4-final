<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/includes/config.php';
$db = getDB();

$settings = [];
foreach ($db->query("SELECT setting_key, setting_value FROM settings ORDER BY setting_key")->fetchAll() as $r) {
    $settings[$r['setting_key']] = $r['setting_value'];
}

// Show problematic fields
$check = [
    'school_name_bn' => 'School Name Bengali',
    'address' => 'Address',
    'established_year' => 'Year',
    'phone1' => 'Phone 1',
    'phone2' => 'Phone 2',
    'motto' => 'Motto',
    'languages' => 'Languages',
    'campus_size' => 'Campus Size',
    'school_hours' => 'School Hours',
    'grades_offered' => 'Grades'
];

echo "<h2>Settings Check:</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Key</th><th>Value</th><th>Length</th></tr>";

foreach ($check as $key => $label) {
    $val = $settings[$key] ?? 'MISSING';
    echo "<tr>";
    echo "<td><strong>$label</strong></td>";
    echo "<td>" . htmlspecialchars($val) . "</td>";
    echo "<td>" . strlen($val) . "</td>";
    echo "</tr>";
}
echo "</table>";
?>
