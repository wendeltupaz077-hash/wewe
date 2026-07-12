<?php
$db = new PDO('sqlite:database/database.sqlite');
$stmt = $db->query("SELECT name, type FROM sqlite_master WHERE name IN ('users_old','users')");
foreach ($stmt as $row) {
    echo $row['type'] . ':' . $row['name'] . "\n";
}
