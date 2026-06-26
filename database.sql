-- =============================================
-- SMMD School Database Setup
-- cPanel এ phpMyAdmin খুলে এই SQL টা run করো
-- =============================================

CREATE DATABASE IF NOT EXISTS smmd_school CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE smmd_school;

-- ── ADMIN USERS ──
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin: username=admin, password=smmd2025
INSERT INTO admin_users (username, password, name) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator');
-- ⚠️ উপরের password হলো "password" — প্রথমে login করে বদলাও

-- ── NOTICES ──
CREATE TABLE IF NOT EXISTS notices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    body TEXT,
    type ENUM('exam','event','holiday','admission','other') DEFAULT 'other',
    notice_date DATE,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO notices (title, body, type, notice_date) VALUES
('JSC পরীক্ষার সময়সূচি ২০২৫', 'JSC পরীক্ষা আগামী ১ নভেম্বর ২০২৫ থেকে শুরু হবে।', 'exam', '2025-01-15'),
('৬ষ্ঠ শ্রেণিতে ভর্তি বিজ্ঞপ্তি ২০২৫', 'ভর্তির শেষ তারিখ ৩১ জানুয়ারি ২০২৫।', 'admission', '2025-01-10'),
('বার্ষিক ক্রীড়া প্রতিযোগিতা ২০২৫', '১৫ ফেব্রুয়ারি বার্ষিক ক্রীড়া প্রতিযোগিতা।', 'event', '2025-01-05');

-- ── TEACHERS ──
CREATE TABLE IF NOT EXISTS teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    subject VARCHAR(100),
    qualification VARCHAR(200),
    phone VARCHAR(20),
    email VARCHAR(100),
    photo VARCHAR(255),
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO teachers (name, subject, qualification, sort_order) VALUES
('মোঃ আব্দুর রহিম', 'প্রধান শিক্ষক', 'এম.এ, বি.এড (ঢাকা বিশ্ববিদ্যালয়)', 1),
('মোসাঃ কামরুন নাহার', 'বাংলা বিভাগ', 'এম.এ (বাংলা), রাজশাহী বিশ্ববিদ্যালয়', 2),
('মোঃ হাসানুজ্জামান', 'গণিত বিভাগ', 'এম.এস.সি (গণিত), যশোর বিশ্ববিদ্যালয়', 3),
('সুলতানা পারভীন', 'বিজ্ঞান বিভাগ', 'এম.এস.সি (পদার্থবিজ্ঞান)', 4),
('মোঃ ইকবাল হোসেন', 'ইংরেজি বিভাগ', 'এম.এ (ইংরেজি), ঢাকা বিশ্ববিদ্যালয়', 5);

-- ── RESULTS ──
CREATE TABLE IF NOT EXISTS results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_no VARCHAR(20),
    student_name VARCHAR(100) NOT NULL,
    exam_type VARCHAR(50),
    division VARCHAR(50),
    gpa DECIMAL(3,2),
    grade VARCHAR(5),
    year INT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO results (roll_no, student_name, exam_type, division, gpa, grade, year) VALUES
('১০১২৩৪', 'রাহিমা বেগম', 'SSC', 'বিজ্ঞান', 5.00, 'A+', 2024),
('১০১২৩৫', 'মোঃ সাইফুল ইসলাম', 'SSC', 'বিজ্ঞান', 5.00, 'A+', 2024),
('১০১২৩৬', 'তানভীর আহমেদ', 'SSC', 'মানবিক', 4.83, 'A+', 2024),
('৭০১২৩৪', 'আয়েশা সিদ্দিকা', 'JSC', NULL, 5.00, 'A+', 2024),
('৭০১২৩৫', 'মোঃ আরিফুল হক', 'JSC', NULL, 4.67, 'A+', 2024);

-- ── ADMISSIONS ──
CREATE TABLE IF NOT EXISTS admissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    class VARCHAR(20),
    father_name VARCHAR(100),
    mother_name VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── GALLERY ──
CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200),
    filename VARCHAR(255) NOT NULL,
    category VARCHAR(50) DEFAULT 'general',
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── SITE SETTINGS ──
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO settings (setting_key, setting_value) VALUES
('school_name_bn', 'সাগরদাঁড়ি মাইকেল মধুসূদন প্রতিষ্ঠান'),
('school_name_en', 'Sagordari Michael Madhusudhan Institution'),
('phone1', '০১XXXXXXXXX'),
('phone2', '০১XXXXXXXXX'),
('email', 'smmdschool@gmail.com'),
('address', 'সাগরদাঁড়ি, কেশবপুর উপজেলা, যশোর, বাংলাদেশ'),
('principal_name', 'মোঃ আব্দুর রহিম'),
('principal_message', 'আমরা বিশ্বাস করি প্রতিটি শিশুর মধ্যে অপার সম্ভাবনা লুকিয়ে আছে।'),
('established_year', '১৯৬৫'),
('facebook_url', ''),
('youtube_url', ''),
('google_map_embed', ''),
('ticker_notices', '২০২৫ সালের JSC পরীক্ষার সময়সূচি প্রকাশিত হয়েছে|৬ষ্ঠ শ্রেণিতে ভর্তি আবেদন চলছে|বার্ষিক ক্রীড়া প্রতিযোগিতা আগামী ১৫ ফেব্রুয়ারি'),
('total_students', '1200'),
('total_teachers', '45'),
('pass_rate', '98');
