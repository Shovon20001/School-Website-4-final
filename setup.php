<?php
/**
 * Database Setup Script
 */
require_once __DIR__ . '/includes/config.php';

header('Content-Type: text/html; charset=utf-8');

echo '<h1>Database Setup</h1>';

try {
    // Delete old SQLite database if it exists
    $db_path = __DIR__ . '/smmd_school.db';
    if (file_exists($db_path)) {
        @unlink($db_path);
        echo '<p>✓ Old database deleted</p>';
    }
    
    // Get fresh connection (forces reinitialization)
    $pdo = new PDO("sqlite:" . $db_path, '', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    $pdo->exec("PRAGMA foreign_keys = ON");
    
    // Create all tables
    $tables = [
        "CREATE TABLE admin_users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            name TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE notices (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            body TEXT,
            type TEXT DEFAULT 'other',
            notice_date DATE,
            is_active TINYINT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE teachers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            subject TEXT,
            qualification TEXT,
            phone TEXT,
            email TEXT,
            photo TEXT,
            sort_order INTEGER DEFAULT 0,
            is_active TINYINT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            setting_key TEXT NOT NULL UNIQUE,
            setting_value TEXT
        )",
        "CREATE TABLE gallery (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT,
            filename TEXT NOT NULL,
            category TEXT DEFAULT 'general',
            sort_order INTEGER DEFAULT 0,
            is_active TINYINT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE results (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            roll_no TEXT,
            student_name TEXT NOT NULL,
            exam_type TEXT,
            division TEXT,
            gpa REAL,
            grade TEXT,
            year INTEGER,
            is_active TINYINT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE admissions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            student_name TEXT NOT NULL,
            class TEXT,
            father_name TEXT,
            mother_name TEXT,
            phone TEXT,
            address TEXT,
            status TEXT DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    ];
    
    foreach ($tables as $sql) {
        $pdo->exec($sql);
        echo '<p>✓ Table created</p>';
    }
    
    // Insert default admin
    $pdo->prepare("INSERT INTO admin_users (username, password, name) VALUES (?, ?, ?)")
        ->execute(['admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator']);
    echo '<p>✓ Admin user created (username: admin, password: password)</p>';
    
    // Insert default notices
    $notices = [
        ['JSC পরীক্ষার সময়সূচি २०२५', 'JSC পরীক্ষা আগামী १ নভেম্বর २०२५ থেকে শুরু হবে।', 'exam', '2025-01-15'],
        ['६ষ্ঠ শ্রেণিতে ভর্তি বিজ্ঞপ্তি २०२५', 'ভর্তির শেষ তারিখ ३१ জানুয়ারি २०२५।', 'admission', '2025-01-10'],
        ['বার্ষিক ক্রীড়া প্রতিযোগিতা २०२५', '१५ ফেব্রুয়ারি বার্ষিক ক্রীড়া প্রতিযোগিতা।', 'event', '2025-01-05']
    ];
    $stmt = $pdo->prepare("INSERT INTO notices (title, body, type, notice_date) VALUES (?, ?, ?, ?)");
    foreach ($notices as $n) {
        $stmt->execute($n);
    }
    echo '<p>✓ Sample notices added</p>';
    
    // Insert default settings
    $settings = [
        'school_name_bn' => 'সাগরদাঁড়ি মাইকেল মধুসূদন প্রতিষ্ঠান',
        'school_name_en' => 'Sagordari Michael Madhusudhan Institution',
        'email' => 'smmdschool@gmail.com',
        'address' => 'সাগরদাঁড়ি, কেশবপুর উপজেলা, যশোর, বাংলাদেশ',
        'principal_name' => 'মোঃ আব্দুর রহিম',
        'principal_message' => 'আমরা বিশ্বাস করি প্রতিটি শিশুর মধ্যে অপার সম্ভাবনা লুকিয়ে আছে।',
        'established_year' => '१९६५',
        'ticker_notices' => 'স্বাগতম সাগরদাঁড়ি মাইকেল মধুসূদন প্রতিষ্ঠানে',
        'total_students' => '1200',
        'total_teachers' => '45',
        'pass_rate' => '98'
    ];
    $settingsStmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
    foreach ($settings as $key => $value) {
        $settingsStmt->execute([$key, $value]);
    }
    echo '<p>✓ Settings configured</p>';
    
    echo '<h2 style="color: green;">✓ Database Setup Complete!</h2>';
    echo '<p><a href="/">Go to Homepage</a></p>';
    
} catch (Exception $e) {
    echo '<h2 style="color: red;">Error: ' . htmlspecialchars($e->getMessage()) . '</h2>';
    echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
}
?>
