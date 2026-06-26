<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/includes/config.php';

$db = getDB();

// Clear corrupted data
$db->exec("DELETE FROM settings");

// Insert all data using PHP PDO (which handles UTF-8 properly)
$data = [
    'school_name_bn' => 'সাগরদাঁড়ি মাইকেল মধুসূদন ইনস্টিটিউশন',
    'school_name_en' => 'Sagardari Michael Madhusadan Institution',
    'established_year' => '১৯৪৪',
    'address' => 'ডাকবাংলা রোড, সাগরদাঁড়ি, কেশবপুর, যশোর ৭৪৫০, বাংলাদেশ',
    'phone1' => '০১৭XXXXXXXX',
    'phone2' => '০১৬XXXXXXXX',
    'email' => 'smmdschool@gmail.com',
    'principal_name' => 'Shamol Kumar Chowdhury',
    'principal_message' => 'শিক্ষার আলোয় আলোকিত ভবিষ্যৎ গড়ে তুলুন',
    'school_code' => '115820',
    'campus_size' => '১০ একর',
    'school_hours' => 'সকাল ৯টা - বিকেল ২টা ৩০ মিনিট',
    'grades_offered' => 'ষষ্ঠ থেকে দশম শ্রেণী',
    'motto' => 'পড়েছো তোমার প্রভুর নামে',
    'languages' => 'বাংলা ও ইংরেজি',
    'campus_type' => 'গ্রামীণ',
    'affiliation' => 'যশোর শিক্ষা বোর্ড',
    'pass_rate' => '৯৮',
    'total_students' => '১২০০',
    'total_teachers' => '৪৫',
    'location_details' => 'ডাকবাংলা রোড, সাগরদাঁড়ি, কেশবপুর, যশোর ৭৪৫০, বাংলাদেশ',
    'history' => 'সাগরদাঁড়ি মাইকেল মধুসূদন ইনস্টিটিউশন ১৯৪৪ সালে স্থানীয় উদ্যোগে প্রতিষ্ঠিত হয়েছিল। এই প্রতিষ্ঠানটি মহাকবি মাইকেল মধুসূদন দত্তের নামে নামকরণ করা হয়েছে।',
    'ticker_notices' => 'অনলাইন ক্লাস চলছে|ভর্তি পরীক্ষা শীঘ্রই|পরীক্ষার ফলাফল দেখুন',
    'google_map_embed' => '<iframe width="100%" height="400" style="border:1px solid var(--border);border-radius:var(--radius-lg);" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3625.7815!2d89.1618!3d22.8189!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39ff1d1234567890%3A0xabcd1234567890!2z0KXQvdC00LXQvdGC0LXQvdGBINC80L3Rg9Cy0LXQvdGC0YvQvSDQktC90YHQuNGH0LXQu9GM0YbQtSDQn9C10YDQs9C90YHQutCwINCY0LzQv9C90YHQutC-!5e0!3m2!1sbn!2sbd!4v2026" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
];

$stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");

$inserted = 0;
$errors = [];

foreach ($data as $key => $value) {
    try {
        $stmt->execute([$key, $value]);
        $inserted++;
    } catch (Exception $e) {
        $errors[] = "$key: " . $e->getMessage();
    }
}

echo "<html><head><meta charset='utf-8'><style>body { font-family: Arial; margin: 20px; }</style></head><body>";
echo "<h1>✅ Settings Fixed</h1>";
echo "<p><strong>Inserted:</strong> $inserted records</p>";

if (!empty($errors)) {
    echo "<h2>Errors:</h2>";
    foreach ($errors as $err) {
        echo "<p>❌ $err</p>";
    }
}

// Verify
echo "<h2>Verification:</h2>";
$verify = $db->query("SELECT setting_key, setting_value FROM settings WHERE setting_key='school_name_bn'")->fetch();
echo "<p>School Name: " . htmlspecialchars($verify['setting_value']) . "</p>";

echo "<hr>";
echo "<p><a href='/'>← Back to Homepage</a></p>";
echo "</body></html>";
?>
