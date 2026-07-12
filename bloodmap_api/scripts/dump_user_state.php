<?php
$db = new PDO('sqlite:database/database.sqlite');
$tables = ['users', 'users_old'];
foreach ($tables as $table) {
    $exists = false;
    foreach ($db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$table'") as $row) {
        $exists = true;
    }
    echo "$table exists: " . ($exists ? 'yes' : 'no') . "\n";
    if ($exists) {
        $stmt = $db->query("SELECT count(*) AS cnt FROM $table");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "$table rows: " . ($row['cnt'] ?? '0') . "\n";
        $cols = $db->query("PRAGMA table_info($table)")->fetchAll(PDO::FETCH_ASSOC);
        echo "$table columns:\n";
        foreach ($cols as $c) {
            echo "  {$c['name']} {$c['type']} " . ($c['notnull'] ? 'NOT NULL' : 'NULL') . " default=" . ($c['dflt_value'] === null ? 'NULL' : $c['dflt_value']) . "\n";
        }
    }
    echo "\n";
}
