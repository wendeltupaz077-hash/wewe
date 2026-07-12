<?php
$db = new PDO('sqlite:database/database.sqlite');
$tables = ['users', 'users_old'];
foreach ($tables as $table) {
    $stmt = $db->query("SELECT count(*) AS cnt FROM {$table}");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $table . ': ' . ($row['cnt'] ?? '0') . "\n";
}
$cols = $db->query("PRAGMA table_info(users)")->fetchAll(PDO::FETCH_ASSOC);
echo "\nusers columns:\n";
foreach ($cols as $c) {
    echo $c['name'] . ' ' . $c['type'] . ' ' . ($c['notnull'] ? 'NOT NULL' : 'NULL') . ' default=' . ($c['dflt_value'] === null ? 'NULL' : $c['dflt_value']) . "\n";
}
$cols = $db->query("PRAGMA table_info(users_old)")->fetchAll(PDO::FETCH_ASSOC);
echo "\nusers_old columns:\n";
foreach ($cols as $c) {
    echo $c['name'] . ' ' . $c['type'] . ' ' . ($c['notnull'] ? 'NOT NULL' : 'NULL') . ' default=' . ($c['dflt_value'] === null ? 'NULL' : $c['dflt_value']) . "\n";
}
