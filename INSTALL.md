# 🏫 SMMD School — সম্পূর্ণ Installation Guide

## ফাইল স্ট্রাকচার
```
smmd-school/
├── index.php              ← মূল ওয়েবসাইট (database থেকে dynamic)
├── .htaccess              ← URL routing & security
├── database.sql           ← Database setup file
│
├── includes/
│   └── config.php         ← ⚠️ DB credentials এখানে দাও
│
├── api/
│   └── index.php          ← Public API (website এর জন্য)
│
├── admin/
│   ├── index.php          ← Admin Dashboard
│   ├── login.php          ← Login page
│   ├── logout.php         ← Logout
│   └── actions.php        ← সব CRUD operations
│
├── assets/
│   ├── css/style.css
│   ├── js/main.js
│   └── images/
│
└── uploads/
    └── gallery/           ← Gallery ছবি এখানে upload হবে
```

---

## 🚀 cPanel এ Deploy করার ধাপ

### ধাপ ১ — Database তৈরি করো
1. cPanel → **MySQL Databases** এ যাও
2. নতুন database তৈরি করো: `smmd_school`
3. নতুন user তৈরি করো: `smmd_user` + password দাও
4. User কে database এ **All Privileges** দাও
5. **phpMyAdmin** খুলো → `smmd_school` select করো
6. **Import** ট্যাবে যাও → `database.sql` file upload করো → Go

### ধাপ ২ — Config আপডেট করো
`includes/config.php` ফাইলে এই লাইনগুলো বদলাও:
```php
define('DB_USER', 'smmd_user');        // তোমার db username
define('DB_PASS', 'তোমার_password');   // তোমার db password
define('DB_NAME', 'smmd_school');      // database name
define('SITE_URL', 'https://yourdomain.com'); // তোমার domain
```

### ধাপ ৩ — Files Upload করো
1. cPanel → **File Manager** → `public_html` ফোল্ডারে যাও
2. পুরো `smmd-school` ফোল্ডারের **সব files** `public_html` এ upload করো
   (ফোল্ডার না, ভেতরের files গুলো সরাসরি)
3. `uploads/gallery/` ফোল্ডারের permission **755** করো:
   - File Manager → uploads → gallery → Right click → Permissions → 755

### ধাপ ৪ — Test করো
- ওয়েবসাইট: `https://yourdomain.com`
- Admin: `https://yourdomain.com/admin`
- Login: username=`admin`, password=`password`
- **প্রথমেই পাসওয়ার্ড বদলাও!**

---

## 🔐 Admin Panel Features

| Feature | কী করা যাবে |
|---|---|
| নোটিশ বোর্ড | যোগ, সম্পাদনা, মুছুন — সাথে সাথে website এ দেখাবে |
| পরীক্ষার ফলাফল | SSC/JSC ফলাফল database এ সংরক্ষণ |
| শিক্ষকমণ্ডলী | শিক্ষক যোগ/সম্পাদনা/মুছুন |
| গ্যালারি | ছবি upload → সাথে সাথে website এ দেখাবে |
| ভর্তির আবেদন | অনুমোদন/বাতিল + CSV export |
| সাইট সেটিং | ফোন, ঠিকানা, principal নাম, stats |
| পাসওয়ার্ড | Admin password পরিবর্তন |

---

## ⚠️ গুরুত্বপূর্ণ Security

1. **প্রথম login এর পর পাসওয়ার্ড অবশ্যই বদলাও**
2. `includes/config.php` কখনো publicly accessible করো না
3. `.htaccess` ফাইল upload করতে ভুলো না

---

## 🆘 সমস্যা হলে

**Database connection error?**
→ `config.php` এ credentials ঠিক আছে কিনা দেখো

**Upload কাজ করছে না?**
→ `uploads/gallery/` ফোল্ডারের permission 755 করো

**Admin login হচ্ছে না?**
→ `database.sql` সঠিকভাবে import হয়েছে কিনা দেখো
