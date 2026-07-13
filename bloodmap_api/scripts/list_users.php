<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$users = User::orderBy('id')->get(['id', 'email', 'name', 'role', 'status', 'is_registered', 'is_first_login']);
foreach ($users as $user) {
    echo "id={$user->id} email={$user->email} role={$user->role} status={$user->status} is_registered=" . ((int) $user->is_registered) . " is_first_login=" . ((int) $user->is_first_login) . PHP_EOL;
}
