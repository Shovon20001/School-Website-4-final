<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/includes/config.php';

$db = getDB();

// Get all settings
$settings = [];
foreach ($db->query("SELECT setting_key, setting_value FROM settings")->fetchAll() as $r) {
    $settings[$r['setting_key']] = $r['setting_value'];
}

// Helper function
function s($arr, $key, $default='') { 
    return htmlspecialchars($arr[$key] ?? $default); 
}

?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Testing Page</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f9f9f9; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1, h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .section { margin: 20px 0; padding: 15px; background: #f5f5f5; border-radius: 5px; }
        .item { margin: 10px 0; padding: 10px; background: white; border-left: 4px solid #007bff; }
        .label { font-weight: bold; color: #555; }
        .value { color: #333; word-break: break-all; }
        .error { background: #ffebee; border-left-color: #d32f2f; color: #d32f2f; }
        .success { background: #e8f5e9; border-left-color: #388e3c; color: #388e3c; }
        img { max-width: 200px; margin: 10px 0; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        table td, table th { padding: 10px; border: 1px solid #ddd; text-align: left; }
        table th { background: #007bff; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Website Testing & Diagnostics</h1>

        <!-- SETTINGS SECTION -->
        <h2>📋 School Settings</h2>
        <div class="section">
            <div class="item">
                <div class="label">School Name (Bengali):</div>
                <div class="value"><?= s($settings, 'school_name_bn') ?></div>
            </div>
            <div class="item">
                <div class="label">School Name (English):</div>
                <div class="value"><?= s($settings, 'school_name_en') ?></div>
            </div>
            <div class="item">
                <div class="label">Established Year:</div>
                <div class="value"><?= s($settings, 'established_year') ?></div>
            </div>
            <div class="item">
                <div class="label">Address:</div>
                <div class="value"><?= s($settings, 'address') ?></div>
            </div>
            <div class="item">
                <div class="label">Phone 1:</div>
                <div class="value"><?= s($settings, 'phone1') ?></div>
            </div>
            <div class="item">
                <div class="label">Email:</div>
                <div class="value"><?= s($settings, 'email') ?></div>
            </div>
            <div class="item">
                <div class="label">Principal Name:</div>
                <div class="value"><?= s($settings, 'principal_name') ?></div>
            </div>
            <div class="item">
                <div class="label">Principal Message:</div>
                <div class="value"><?= s($settings, 'principal_message') ?></div>
            </div>
            <div class="item">
                <div class="label">Motto:</div>
                <div class="value"><?= s($settings, 'motto') ?></div>
            </div>
            <div class="item">
                <div class="label">Campus Size:</div>
                <div class="value"><?= s($settings, 'campus_size') ?></div>
            </div>
            <div class="item">
                <div class="label">School Hours:</div>
                <div class="value"><?= s($settings, 'school_hours') ?></div>
            </div>
            <div class="item">
                <div class="label">Grades:</div>
                <div class="value"><?= s($settings, 'grades_offered') ?></div>
            </div>
        </div>

        <!-- GALLERY SECTION -->
        <h2>📸 Gallery Images</h2>
        <div class="section">
            <?php
            $gallery = $db->query("SELECT * FROM gallery WHERE is_active=1 ORDER BY sort_order ASC LIMIT 8")->fetchAll();
            if (!empty($gallery)) {
                echo "<table>";
                echo "<tr><th>ID</th><th>Title</th><th>Filename</th><th>Preview</th></tr>";
                foreach ($gallery as $g) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($g['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($g['title'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($g['filename']) . "</td>";
                    echo "<td><img src='/uploads/gallery/" . htmlspecialchars($g['filename']) . "' alt='' style='max-width:100px;'></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<div class='item error'>No gallery images found</div>";
            }
            ?>
        </div>

        <!-- NOTICES SECTION -->
        <h2>📝 Notices</h2>
        <div class="section">
            <?php
            $notices = $db->query("SELECT * FROM notices WHERE is_active=1 ORDER BY notice_date DESC LIMIT 5")->fetchAll();
            if (!empty($notices)) {
                foreach ($notices as $n) {
                    echo "<div class='item'>";
                    echo "<div class='label'>" . htmlspecialchars($n['title']) . "</div>";
                    echo "<div class='value'>" . htmlspecialchars(substr($n['body'], 0, 100)) . "...</div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='item error'>No notices found</div>";
            }
            ?>
        </div>

        <!-- TEACHERS SECTION -->
        <h2>👨‍🏫 Teachers</h2>
        <div class="section">
            <?php
            $teachers = $db->query("SELECT * FROM teachers WHERE is_active=1 ORDER BY sort_order ASC LIMIT 5")->fetchAll();
            if (!empty($teachers)) {
                echo "<table>";
                echo "<tr><th>Name</th><th>Subject</th><th>Qualification</th><th>Photo</th></tr>";
                foreach ($teachers as $t) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($t['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($t['subject']) . "</td>";
                    echo "<td>" . htmlspecialchars($t['qualification']) . "</td>";
                    echo "<td>";
                    if ($t['photo']) {
                        echo "<img src='/uploads/" . htmlspecialchars($t['photo']) . "' alt='' style='max-width:80px;'>";
                    } else {
                        echo "No photo";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<div class='item error'>No teachers found</div>";
            }
            ?>
        </div>

        <!-- ADMIN CHECK -->
        <h2>🔐 Admin Panel</h2>
        <div class="section">
            <?php
            $admin = $db->query("SELECT username, name FROM admin_users LIMIT 1")->fetch();
            if ($admin) {
                echo "<div class='item success'>";
                echo "<div class='label'>Admin User: " . htmlspecialchars($admin['username']) . "</div>";
                echo "<div class='value'>Name: " . htmlspecialchars($admin['name']) . "</div>";
                echo "<div class='value'>Login URL: <a href='/admin/login.php'>/admin/login.php</a></div>";
                echo "</div>";
            } else {
                echo "<div class='item error'>No admin user found</div>";
            }
            ?>
        </div>

        <!-- FILE STRUCTURE -->
        <h2>📁 File Structure</h2>
        <div class="section">
            <?php
            $required_files = [
                'assets/css/style.css' => 'CSS Stylesheet',
                'assets/js/main.js' => 'JavaScript',
                'assets/images/logo.png' => 'Logo',
                'assets/images/school-bg.jpg.jpg' => 'Background Image',
                'admin/login.php' => 'Admin Login',
                'api/index.php' => 'API Endpoint',
                'includes/config.php' => 'Config File'
            ];
            
            $base = __DIR__;
            foreach ($required_files as $file => $label) {
                $exists = file_exists("$base/$file");
                $class = $exists ? 'success' : 'error';
                echo "<div class='item $class'>";
                echo "<div class='label'>$label</div>";
                echo "<div class='value'>$file: " . ($exists ? '✅ Found' : '❌ Missing') . "</div>";
                echo "</div>";
            }
            ?>
        </div>

        <!-- ENCODING TEST -->
        <h2>🔤 Text Encoding Test</h2>
        <div class="section">
            <div class="item">
                <div class="label">Database Encoding:</div>
                <div class="value">
                    <?php
                    $charset = $db->query("SELECT @@character_set_database")->fetch();
                    echo htmlspecialchars($charset[0]);
                    ?>
                </div>
            </div>
            <div class="item">
                <div class="label">Connection Charset:</div>
                <div class="value">utf8mb4</div>
            </div>
            <div class="item">
                <div class="label">Bengali Text Test:</div>
                <div class="value">সাগরদাঁড়ি মাইকেল মধুসূদন ইনস্টিটিউশন</div>
            </div>
            <div class="item">
                <div class="label">Bengali Numerals Test:</div>
                <div class="value">১ ২ ৩ ৪ ৫ ৬ ৭ ৮ ৯ ০</div>
            </div>
        </div>

    </div>
</body>
</html>
