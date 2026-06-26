<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/includes/config.php';
$db = getDB();

// সব data database থেকে নাও
$settings = [];
foreach ($db->query("SELECT setting_key, setting_value FROM settings")->fetchAll() as $r)
    $settings[$r['setting_key']] = $r['setting_value'];

$notices = $db->query("SELECT * FROM notices WHERE is_active=1 ORDER BY notice_date DESC LIMIT 6")->fetchAll();
$teachers = $db->query("SELECT * FROM teachers WHERE is_active=1 ORDER BY sort_order ASC LIMIT 8")->fetchAll();
$gallery = $db->query("SELECT * FROM gallery WHERE is_active=1 ORDER BY sort_order ASC, created_at DESC LIMIT 8")->fetchAll();

function s($arr, $key, $default='') { return htmlspecialchars($arr[$key] ?? $default); }

$tickerItems = explode('|', $settings['ticker_notices'] ?? '');
?>
<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= s($settings,'school_name_bn') ?></title>
<meta name="description" content="<?= s($settings,'school_name_en') ?> — শিক্ষার আলো ছড়ানোর প্রতিষ্ঠান।">
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
.modal-overlay{display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.75);backdrop-filter:blur(6px);align-items:center;justify-content:center;padding:2rem;}
.modal-box{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:2.5rem;max-width:520px;width:100%;position:relative;}
.modal-close{position:absolute;top:1rem;right:1rem;background:none;border:none;color:var(--text-muted);font-size:1.4rem;cursor:pointer;}
.modal-title{font-family:var(--font-display);font-size:1.4rem;font-weight:700;margin-bottom:1rem;}
.modal-body{font-size:14px;color:var(--text-secondary);line-height:1.8;}
.principal-card{background:var(--bg-card);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);padding:2rem;display:flex;gap:1.5rem;align-items:flex-start;margin-top:2rem;}
.principal-avatar{width:72px;height:72px;border-radius:50%;flex-shrink:0;background:var(--accent-dim);border:2px solid var(--border);display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:1.6rem;font-weight:700;color:var(--accent);}
.principal-name{font-size:16px;font-weight:600;margin-bottom:2px;}
.principal-title{font-size:12px;color:var(--accent);margin-bottom:8px;font-weight:500;}
.principal-quote{font-size:13px;color:var(--text-secondary);line-height:1.7;font-style:italic;}
</style>
</head>
<body>

<!-- HEADER -->
<header class="header-top">
  <div class="header-inner">
    <div class="header-brand">
      <div class="header-logo">
        <img src="uploads/gallery/1.jpeg" alt="Logo" onerror="this.style.display='none';this.parentElement.textContent='SM'" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
      </div>
      <div class="header-text">
        <h2 class="header-name"><?= s($settings,'school_name_bn') ?></h2>
        <p class="header-sub"><?= s($settings,'school_name_en') ?></p>
      </div>
    </div>
  </div>
</header>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
  <div class="nav-inner">
    <div class="nav-links">
      <a href="#about">আমাদের সম্পর্কে</a>
      <a href="#notices">নোটিশ বোর্ড</a>
      <a href="#teachers">শিক্ষকমণ্ডলী</a>
      <a href="#results">ফলাফল</a>
      <a href="#gallery">গ্যালারি</a>
      <a href="#contact">যোগাযোগ</a>
      <a href="#admission" class="nav-cta">ভর্তি তথ্য</a>
    </div>
    <div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>
  </div>
  <div class="mobile-menu" id="mobileMenu">
    <a href="#about">আমাদের সম্পর্কে</a>
    <a href="#notices">নোটিশ বোর্ড</a>
    <a href="#teachers">শিক্ষকমণ্ডলী</a>
    <a href="#results">ফলাফল</a>
    <a href="#gallery">গ্যালারি</a>
    <a href="#contact">যোগাযোগ</a>
    <a href="#admission">ভর্তি তথ্য</a>
  </div>
</nav>

<!-- TICKER -->
<div class="notice-bar">
  <span class="notice-label">সর্বশেষ</span>
  <div class="ticker-wrap">
    <div class="ticker">
      <?php foreach(array_merge($tickerItems,$tickerItems) as $item): ?>
      <span><?= htmlspecialchars(trim($item)) ?></span>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- HERO -->
<section class="hero" id="home">
  <div class="hero-bg"></div>
  <div class="hero-grid"></div>
  <div class="hero-content">
    <div class="hero-badge"><div class="hero-badge-dot"></div>প্রতিষ্ঠিত <?= s($settings,'established_year','১৯৬৫') ?> · সাগরদাঁড়ি</div>
    <h1 class="hero-title">
      <?= s($settings,'school_name_bn') ?>
      <span class="line2">আলোর পথে এগিয়ে চলো</span>
    </h1>
    <p class="hero-desc">সাগরদাঁড়ির বুকে বছরের পর বছর ধরে শিক্ষার আলো ছড়িয়ে আসছে আমাদের বিদ্যালয়। মহাকবি মাইকেল মধুসূদন দত্তের নামাঙ্কিত এই প্রতিষ্ঠান প্রজন্মের পর প্রজন্ম গড়ে তুলছে।</p>
    <div class="hero-actions">
      <a href="#admission" class="btn-primary"><i class="ti ti-school"></i> ভর্তি তথ্য জানুন</a>
      <a href="#about" class="btn-outline"><i class="ti ti-info-circle"></i> আমাদের সম্পর্কে</a>
    </div>
    <div class="hero-stats">
      <div><div class="hero-stat-num" data-target="<?= intval($settings['established_year'] ? (date('Y') - intval(str_replace('১৯','19',$settings['established_year']??'1965'))) : 60) ?>" data-suffix="+">0+</div><div class="hero-stat-label">বছরের অভিজ্ঞতা</div></div>
      <div><div class="hero-stat-num" data-target="<?= intval($settings['total_students']??1200) ?>" data-suffix="+">0+</div><div class="hero-stat-label">শিক্ষার্থী</div></div>
      <div><div class="hero-stat-num" data-target="<?= intval($settings['total_teachers']??45) ?>" data-suffix="+">0+</div><div class="hero-stat-label">অভিজ্ঞ শিক্ষক</div></div>
      <div><div class="hero-stat-num" data-target="<?= intval($settings['pass_rate']??98) ?>" data-suffix="%">0%</div><div class="hero-stat-label">পাসের হার</div></div>
    </div>
  </div>
</section>

<!-- ABOUT -->
<section id="about">
  <div class="section-inner">
    <div class="about-grid">
      <div class="about-visual reveal">
        <img src="uploads/gallery/3.jpg" alt="School" style="width:100%;height:100%;object-fit:cover;border-radius:12px;">
        <div class="about-badge-float">
          <span class="year"><?= s($settings,'established_year','১৯৬৫') ?></span>
          <span class="label">থেকে শিক্ষার আলো<br>ছড়িয়ে যাচ্ছে</span>
        </div>
      </div>
      <div class="about-text reveal">
        <span class="section-tag">আমাদের সম্পর্কে</span>
        <h2 class="section-title">শিক্ষা, সংস্কৃতি ও মানবতার<br>বিদ্যাপীঠ</h2>
        <p class="section-desc" style="margin-bottom:1rem;"><strong><?= s($settings,'school_name_bn') ?></strong> — মহাকবি মাইকেল মধুসূদন দত্তের জন্মস্থান সাগরদাঁড়িতে অবস্থিত এই প্রতিষ্ঠান <?= s($settings,'established_year','১৯৪৪') ?> সালে স্থানীয় উদ্যোগে প্রতিষ্ঠিত হয়েছিল। আজ আট দশকেরও বেশি সময় ধরে এই বিদ্যালয়টি শিক্ষার আলো ছড়িয়ে আসছে।</p>
        <p class="section-desc">আমাদের মূলমন্ত্র <em>"পড়েছো তোমার প্রভুর নামে"</em> — শুধুমাত্র শিক্ষাগত দক্ষতা নয়, বরং মানবিক মূল্যবোধ, নৈতিকতা, সৃজনশীলতা এবং নেতৃত্বের গুণাবলী বিকাশের মাধ্যমে আগামীর দায়িত্বশীল নাগরিক তৈরি করা আমাদের লক্ষ্য।</p>
        <div class="feature-pills">
          <span class="pill"><span class="icon">🏆</span> SSC পাসের হার <?= s($settings,'pass_rate','৯৮') ?>%</span>
          <span class="pill"><span class="icon">🔬</span> আধুনিক বিজ্ঞান ল্যাব</span>
          <span class="pill"><span class="icon">💻</span> কম্পিউটার ল্যাব</span>
          <span class="pill"><span class="icon">📚</span> সমৃদ্ধ গ্রন্থাগার</span>
          <span class="pill"><span class="icon">⚽</span> খেলার মাঠ</span>
          <span class="pill"><span class="icon">🎭</span> সাংস্কৃতিক কার্যক্রম</span>
        </div>
        <?php if (!empty($settings['principal_name'])): ?>
        <div class="principal-card reveal">
          <div class="principal-avatar"><?= mb_substr($settings['principal_name'],0,1,'UTF-8') ?></div>
          <div>
            <div class="principal-name"><?= s($settings,'principal_name') ?></div>
            <div class="principal-title">প্রধান শিক্ষক</div>
            <div class="principal-quote">"<?= s($settings,'principal_message') ?>"</div>
          </div>
        </div>
        <?php endif; ?>
        
        <!-- School Info Details -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:2rem;padding:1.5rem;background:var(--bg-secondary);border-radius:var(--radius-lg);">
          <div><div style="font-size:12px;color:var(--text-muted);font-weight:500;margin-bottom:4px;">প্রতিষ্ঠা সাল</div><div style="font-size:1.1rem;font-weight:600;"><?= s($settings,'established_year','১৯৪৪') ?></div></div>
          <div><div style="font-size:12px;color:var(--text-muted);font-weight:500;margin-bottom:4px;">শ্রেণীসমূহ</div><div style="font-size:1.1rem;font-weight:600;"><?= s($settings,'grades_offered','ষষ্ঠ - দশম') ?></div></div>
          <div><div style="font-size:12px;color:var(--text-muted);font-weight:500;margin-bottom:4px;">স্কুল কোড</div><div style="font-size:1.1rem;font-weight:600;"><?= s($settings,'school_code','115820') ?></div></div>
          <div><div style="font-size:12px;color:var(--text-muted);font-weight:500;margin-bottom:4px;">ক্যাম্পাস সাইজ</div><div style="font-size:1.1rem;font-weight:600;"><?= s($settings,'campus_size','10 acres') ?></div></div>
          <div><div style="font-size:12px;color:var(--text-muted);font-weight:500;margin-bottom:4px;">স্কুল সময়</div><div style="font-size:1.1rem;font-weight:600;"><?= s($settings,'school_hours','9:00 AM - 2:30 PM') ?></div></div>
          <div><div style="font-size:12px;color:var(--text-muted);font-weight:500;margin-bottom:4px;">মাধ্যম</div><div style="font-size:1.1rem;font-weight:600;"><?= s($settings,'languages','Bengali & English') ?></div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- NOTICES -->
<section id="notices" style="background:var(--bg-secondary);">
  <div class="section-inner">
    <div class="section-header reveal">
      <span class="section-tag">নোটিশ বোর্ড</span>
      <h2 class="section-title">সর্বশেষ বিজ্ঞপ্তি</h2>
      <p class="section-desc">গুরুত্বপূর্ণ তথ্য ও ঘোষণা সম্পর্কে সবসময় আপডেট থাকুন।</p>
    </div>
    <div class="notice-grid">
      <?php
      $typeLabel = ['exam'=>'পরীক্ষা','event'=>'অনুষ্ঠান','holiday'=>'ছুটি','admission'=>'ভর্তি','other'=>'অন্যান্য'];
      $typeBadge = ['exam'=>'badge-exam','event'=>'badge-event','holiday'=>'badge-holiday','admission'=>'badge-admission','other'=>'badge-admission'];
      foreach ($notices as $n):
        $title = htmlspecialchars($n['title']);
        $body = htmlspecialchars($n['body']);
      ?>
      <div class="notice-card reveal" onclick="openNotice('<?= addslashes($title) ?>', '<?= addslashes($body) ?>')">
        <div class="notice-meta">
          <span class="notice-date"><i class="ti ti-calendar"></i> <?= date('d M Y', strtotime($n['notice_date'])) ?></span>
          <span class="notice-badge <?= $typeBadge[$n['type']] ?? 'badge-admission' ?>"><?= $typeLabel[$n['type']] ?? '' ?></span>
        </div>
        <div class="notice-title"><?= $title ?></div>
        <div class="notice-body"><?= mb_substr($body, 0, 120, 'UTF-8') . (mb_strlen($body,'UTF-8')>120?'...':'') ?></div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($notices)): ?>
      <div style="color:var(--text-muted);font-size:14px;padding:2rem;">কোনো নোটিশ নেই।</div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- TEACHERS -->
<section id="teachers">
  <div class="section-inner">
    <div class="section-header reveal">
      <span class="section-tag">শিক্ষকমণ্ডলী</span>
      <h2 class="section-title">আমাদের অভিজ্ঞ শিক্ষকগণ</h2>
      <p class="section-desc">বিশেষজ্ঞ ও নিবেদিতপ্রাণ শিক্ষকমণ্ডলী শিক্ষার্থীদের সাফল্যের পথে পথপ্রদর্শক।</p>
    </div>
    <div class="teachers-grid">
      <?php foreach ($teachers as $t):
        $initials = mb_substr($t['name'], 0, 2, 'UTF-8');
      ?>
      <div class="teacher-card reveal">
        <?php if ($t['photo']): ?>
        <img src="/uploads/<?= htmlspecialchars($t['photo']) ?>" alt="" style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin:0 auto 1rem;display:block;border:2px solid var(--border);">
        <?php else: ?>
        <div class="teacher-avatar"><?= $initials ?></div>
        <?php endif; ?>
        <div class="teacher-name"><?= htmlspecialchars($t['name']) ?></div>
        <div class="teacher-subject"><?= htmlspecialchars($t['subject']) ?></div>
        <div class="teacher-qual"><?= htmlspecialchars($t['qualification']) ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- RESULTS -->
<section id="results" style="background:var(--bg-secondary);">
  <div class="section-inner">
    <div class="section-header reveal">
      <span class="section-tag">পরীক্ষার ফলাফল</span>
      <h2 class="section-title">বোর্ড পরীক্ষার ফলাফল</h2>
    </div>
    <div class="results-tabs reveal">
      <button class="rtab active" id="tab-ssc" onclick="switchTab('ssc')">SSC <?= date('Y') ?></button>
      <button class="rtab" id="tab-jsc" onclick="switchTab('jsc')">JSC <?= date('Y') ?></button>
    </div>
    <?php foreach (['ssc'=>'SSC','jsc'=>'JSC'] as $tabId=>$examType):
      $res = $db->prepare("SELECT * FROM results WHERE exam_type=? AND is_active=1 ORDER BY gpa DESC");
      $res->execute([$examType]);
      $rows = $res->fetchAll();
      $gradeClass = ['A+'=>'grade-ap','A'=>'grade-a','A-'=>'grade-am','B'=>'grade-b'];
    ?>
    <div id="panel-<?=$tabId?>" class="result-panel reveal" <?=$tabId!=='ssc'?'style="display:none"':''?>>
      <div class="results-table-wrap">
        <table>
          <thead><tr><th>রোল নম্বর</th><th>শিক্ষার্থীর নাম</th><?=$examType==='SSC'?'<th>বিভাগ</th>':''?><th>GPA</th><th>গ্রেড</th></tr></thead>
          <tbody>
          <?php if (empty($rows)): ?>
          <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:2rem;">কোনো ফলাফল নেই</td></tr>
          <?php else: foreach($rows as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['roll_no']) ?></td>
            <td><?= htmlspecialchars($r['student_name']) ?></td>
            <?php if($examType==='SSC'): ?><td><?= htmlspecialchars($r['division']??'—') ?></td><?php endif; ?>
            <td style="font-weight:600"><?= $r['gpa'] ?></td>
            <td><span class="result-grade <?= $gradeClass[$r['grade']]??'grade-b' ?>"><?= $r['grade'] ?></span></td>
          </tr>
          <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- GALLERY -->
<section id="gallery">
  <div class="section-inner">
    <div class="section-header reveal">
      <span class="section-tag">ফটো গ্যালারি</span>
      <h2 class="section-title">আমাদের মুহূর্তগুলো</h2>
    </div>
    <?php if (!empty($gallery)): ?>
    <div class="gallery-grid reveal">
      <?php foreach ($gallery as $i => $g): ?>
      <div class="gallery-item <?= $i===0?'large':'' ?>">
        <img src="/uploads/gallery/<?= htmlspecialchars($g['filename']) ?>" alt="<?= htmlspecialchars($g['title']??'') ?>" style="width:100%;height:100%;object-fit:cover;">
        <div class="gallery-overlay"><i class="ti ti-zoom-in" style="font-size:<?=$i===0?'2':'1.5'?>rem;color:white;"></i></div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p style="color:var(--text-muted);text-align:center;padding:3rem;">Admin panel থেকে gallery তে ছবি upload করুন।</p>
    <?php endif; ?>
  </div>
</section>

<!-- ADMISSION -->
<section id="admission" style="background:var(--bg-secondary);">
  <div class="section-inner">
    <div class="section-header reveal">
      <span class="section-tag">ভর্তি তথ্য</span>
      <h2 class="section-title">আমাদের বিদ্যালয়ে যোগ দিন</h2>
    </div>
    <div class="admission-grid">
      <div class="admission-steps reveal">
        <h3 style="font-family:var(--font-display);font-size:1.4rem;margin-bottom:1.5rem;">ভর্তির ধাপসমূহ</h3>
        <div class="adm-step"><div class="adm-step-num">১</div><div><div class="adm-step-title">আবেদন ফর্ম পূরণ</div><div class="adm-step-desc">অনলাইনে বা সরাসরি বিদ্যালয়ে এসে আবেদন ফর্ম পূরণ করুন।</div></div></div>
        <div class="adm-step"><div class="adm-step-num">২</div><div><div class="adm-step-title">কাগজপত্র জমা</div><div class="adm-step-desc">জন্ম সনদ, আগের বিদ্যালয়ের সনদ, ৪ কপি পাসপোর্ট ছবি।</div></div></div>
        <div class="adm-step"><div class="adm-step-num">৩</div><div><div class="adm-step-title">ভর্তি পরীক্ষা</div><div class="adm-step-desc">বাংলা, ইংরেজি ও গণিতে ভর্তি পরীক্ষা দিন।</div></div></div>
        <div class="adm-step"><div class="adm-step-num">৪</div><div><div class="adm-step-title">ভর্তি নিশ্চিতকরণ</div><div class="adm-step-desc">নির্ধারিত ফি জমা দিয়ে ভর্তি নিশ্চিত করুন।</div></div></div>
      </div>
      <div class="admission-form reveal">
        <div id="admForm">
          <div class="form-title">ভর্তির আবেদন করুন</div>
          <div class="form-subtitle">ফর্মটি পূরণ করুন, আমরা শীঘ্রই যোগাযোগ করব।</div>
          <div class="form-row">
            <div class="form-group"><label class="form-label">শিক্ষার্থীর নাম</label><input type="text" class="form-input" id="adm_name" placeholder="পুরো নাম"></div>
            <div class="form-group"><label class="form-label">শ্রেণি</label><select class="form-select" id="adm_class"><option value="">বেছে নিন</option><option>৬ষ্ঠ শ্রেণি</option><option>৭ম শ্রেণি</option><option>৮ম শ্রেণি</option><option>৯ম শ্রেণি</option><option>১০ম শ্রেণি</option></select></div>
          </div>
          <div class="form-row">
            <div class="form-group"><label class="form-label">পিতার নাম</label><input type="text" class="form-input" id="adm_father" placeholder="পিতার নাম"></div>
            <div class="form-group"><label class="form-label">মাতার নাম</label><input type="text" class="form-input" id="adm_mother" placeholder="মাতার নাম"></div>
          </div>
          <div class="form-group"><label class="form-label">মোবাইল নম্বর</label><input type="tel" class="form-input" id="adm_phone" placeholder="০১XXXXXXXXX"></div>
          <div class="form-group"><label class="form-label">ঠিকানা</label><textarea class="form-textarea" id="adm_address" placeholder="সম্পূর্ণ ঠিকানা..."></textarea></div>
          <button class="btn-primary" style="width:100%;justify-content:center;" onclick="submitAdmission()"><i class="ti ti-send"></i> আবেদন জমা দিন</button>
        </div>
        <div id="admSuccess" style="display:none;text-align:center;padding:2rem;">
          <div style="font-size:3rem;margin-bottom:1rem;">✅</div>
          <div style="font-size:1.1rem;font-weight:600;margin-bottom:0.5rem;">আবেদন সফলভাবে জমা হয়েছে!</div>
          <div style="font-size:13px;color:var(--text-secondary);">আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CONTACT -->
<section id="contact">
  <div class="section-inner">
    <div class="section-header reveal">
      <span class="section-tag">যোগাযোগ</span>
      <h2 class="section-title">আমাদের সাথে যোগাযোগ করুন</h2>
    </div>
    <div class="contact-grid">
      <div class="contact-info reveal">
        <div class="contact-card"><div class="contact-icon"><i class="ti ti-map-pin"></i></div><div><div class="contact-card-title">ঠিকানা</div><div class="contact-card-value"><?= s($settings,'address') ?></div></div></div>
        <div class="contact-card"><div class="contact-icon"><i class="ti ti-phone"></i></div><div><div class="contact-card-title">ফোন নম্বর</div><div class="contact-card-value"><?= s($settings,'phone1') ?><?= $settings['phone2'] ? '<br>'.htmlspecialchars($settings['phone2']) : '' ?></div></div></div>
        <div class="contact-card"><div class="contact-icon"><i class="ti ti-mail"></i></div><div><div class="contact-card-title">ইমেইল</div><div class="contact-card-value"><?= s($settings,'email') ?></div></div></div>
        <div class="contact-card"><div class="contact-icon"><i class="ti ti-clock"></i></div><div><div class="contact-card-title">অফিস সময়</div><div class="contact-card-value">শনি–বৃহস্পতি: সকাল ৯টা – বিকেল ৪টা</div></div></div>
      </div>
      <div class="reveal">
        <?php if (!empty($settings['google_map_embed'])): ?>
          <?= $settings['google_map_embed'] ?>
        <?php else: ?>
        <div class="map-wrap"><div class="map-icon">🗺️</div><div>Google Map এখানে যোগ করুন</div><div style="font-size:12px;">Admin → সাইট সেটিং → Google Map Embed Code</div></div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-inner">
    <div class="footer-grid">
      <div>
        <div class="footer-brand-name"><?= s($settings,'school_name_bn') ?></div>
        <div class="footer-brand-tagline">মহাকবি মাইকেল মধুসূদন দত্তের স্মৃতিবিজড়িত সাগরদাঁড়িতে অবস্থিত এই বিদ্যালয় বছরের পর বছর ধরে শিক্ষার আলো ছড়িয়ে আসছে।</div>
        <div style="display:flex;gap:10px;">
          <?php if(!empty($settings['facebook_url'])): ?>
          <a href="<?= htmlspecialchars($settings['facebook_url']) ?>" target="_blank" style="width:36px;height:36px;border-radius:8px;background:var(--bg-card);border:1px solid var(--border-subtle);display:flex;align-items:center;justify-content:center;color:var(--text-secondary);text-decoration:none;"><i class="ti ti-brand-facebook"></i></a>
          <?php endif; ?>
          <?php if(!empty($settings['youtube_url'])): ?>
          <a href="<?= htmlspecialchars($settings['youtube_url']) ?>" target="_blank" style="width:36px;height:36px;border-radius:8px;background:var(--bg-card);border:1px solid var(--border-subtle);display:flex;align-items:center;justify-content:center;color:var(--text-secondary);text-decoration:none;"><i class="ti ti-brand-youtube"></i></a>
          <?php endif; ?>
        </div>
      </div>
      <div class="footer-col"><div class="footer-col-title">দ্রুত লিংক</div><ul><li><a href="#about">আমাদের সম্পর্কে</a></li><li><a href="#notices">নোটিশ বোর্ড</a></li><li><a href="#teachers">শিক্ষকমণ্ডলী</a></li><li><a href="#results">পরীক্ষার ফলাফল</a></li><li><a href="#gallery">গ্যালারি</a></li></ul></div>
      <div class="footer-col"><div class="footer-col-title">ভর্তি</div><ul><li><a href="#admission">ভর্তি তথ্য</a></li><li><a href="#admission">আবেদন করুন</a></li></ul></div>
      <div class="footer-col"><div class="footer-col-title">যোগাযোগ</div><ul><li><a href="#"><?= s($settings,'address') ?></a></li><li><a href="#"><?= s($settings,'phone1') ?></a></li><li><a href="#"><?= s($settings,'email') ?></a></li></ul></div>
    </div>
    <div class="footer-bottom">
      <div class="footer-bottom-text">© <?= date('Y') ?> <span><?= s($settings,'school_name_bn') ?></span>। সকল অধিকার সংরক্ষিত।</div>
    </div>
  </div>
</footer>

<!-- NOTICE MODAL -->
<div class="modal-overlay" id="noticeModal">
  <div class="modal-box">
    <button class="modal-close" onclick="closeModal()"><i class="ti ti-x"></i></button>
    <div class="modal-title" id="modalTitle"></div>
    <div class="modal-body" id="modalBody"></div>
  </div>
</div>

<script src="assets/js/main.js"></script>
<script>
async function submitAdmission() {
  const name = document.getElementById('adm_name').value.trim();
  const phone = document.getElementById('adm_phone').value.trim();
  if (!name || !phone) { alert('নাম ও ফোন নম্বর দেওয়া আবশ্যক!'); return; }
  const data = {
    student_name: name,
    class: document.getElementById('adm_class').value,
    father_name: document.getElementById('adm_father').value,
    mother_name: document.getElementById('adm_mother').value,
    phone: phone,
    address: document.getElementById('adm_address').value
  };
  try {
    const res = await fetch('/api/index.php?action=submit_admission', {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(data)});
    const json = await res.json();
    if (json.success) { document.getElementById('admForm').style.display='none'; document.getElementById('admSuccess').style.display='block'; }
    else alert(json.error || 'সমস্যা হয়েছে!');
  } catch(e) { alert('সার্ভার সমস্যা!'); }
}
</script>
</body>
</html>
