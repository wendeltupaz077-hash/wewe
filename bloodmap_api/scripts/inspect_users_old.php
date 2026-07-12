<?php
$db = new PDO('sqlite:database/database.sqlite');
foreach ($db->query("SELECT name, type FROM sqlite_master WHERE name = 'users_old'") as $row) {
    echo $row['type'] . ':' . $row['name'] . "\n";
}
$stmt = $db->query("SELECT count(*) AS cnt FROM sqlite_master WHERE name = 'users_old'");
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo 'exists:' . ($result['cnt'] ?? 0) . "\n";
if ($result['cnt'] > 0) {
    $stmt2 = $db->query('SELECT count(*) AS cnt FROM users_old');
    $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    echo 'rows:' . ($row2['cnt'] ?? 0) . "\n";
}
