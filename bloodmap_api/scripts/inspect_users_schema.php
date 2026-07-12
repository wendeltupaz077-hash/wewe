<?php
$db = new PDO('sqlite:database/database.sqlite');
$stmt = $db->query('PRAGMA table_info(users)');
foreach ($stmt as $c) {
    echo $c['cid'] . ':' . $c['name'] . ':' . $c['type'] . ':' . ($c['notnull'] ? 'NOTNULL' : 'NULL') . ':' . ($c['dflt_value'] === null ? 'NULL' : $c['dflt_value']) . "\n";
}
