<?php
$db = new PDO('sqlite:database/database.sqlite');
$stmt = $db->query("PRAGMA table_info(users)");
foreach ($stmt as $row) {
    if ($row['name'] === 'email') {
        echo 'email notnull=' . $row['notnull'] . ' default=' . ($row['dflt_value'] === null ? 'NULL' : $row['dflt_value']) . "\n";
    }
}
