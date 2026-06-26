<?php
header('Content-Type: text/html; charset=utf-8');

echo "<h1>Database Setup</h1>";

try {
    // Connect
    $pdo = new PDO("mysql:host=localhost;charset=utf8mb4", 'root', '', 
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $pdo->exec("SET NAMES utf8mb4");
    $pdo->exec("DROP DATABASE IF EXISTS smmd_school");
    $pdo->exec("CREATE DATABASE smmd_school CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE smmd_school");
    
    echo "<p>✓ Database created</p>";
    
    // Read SQL file line by line
    $file = fopen(__DIR__ . '/database.sql', 'r');
    $stmt = '';
    $lineNum = 0;
    
    while (($line = fgets($file)) !== false) {
        $lineNum++;
        $line = trim($line);
        
        // Skip comments and empty lines
        if (empty($line) || substr($line, 0, 2) === '--') continue;
        
        $stmt .= " " . $line;
        
        // Check if statement is complete
        if (substr($line, -1) === ';') {
            $stmt = trim($stmt);
            if (!empty($stmt)) {
                try {
                    $pdo->exec($stmt);
                } catch (Exception $e) {
                    echo "<p style='color:red'>Line $lineNum Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
            }
            $stmt = '';
        }
    }
    fclose($file);
    
    echo "<p>✓ SQL imported successfully</p>";
    
    // Verify
    $count = $pdo->query("SELECT COUNT(*) FROM settings")->fetchColumn();
    echo "<p>✓ Settings table has $count records</p>";
    
    $name = $pdo->query("SELECT setting_value FROM settings WHERE setting_key='school_name_bn'")->fetch(PDO::FETCH_ASSOC);
    echo "<p><strong>School Name:</strong> " . htmlspecialchars($name['setting_value'] ?? 'N/A') . "</p>";
    
    echo "<p><a href='/'>← Go to Homepage</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color:red;font-weight:bold'>ERROR: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
