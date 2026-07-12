<?php
$db = new PDO('sqlite:database/database.sqlite');
$db->exec('DROP TABLE IF EXISTS users');
$db->exec('ALTER TABLE users_old RENAME TO users');
echo "RESTORED\n";
