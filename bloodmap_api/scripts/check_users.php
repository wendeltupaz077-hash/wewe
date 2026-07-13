<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo 'total_users=' . User::count() . PHP_EOL;
$emails = ['admin@smartblood.ph', 'superadmin@bloodmap.ph'];
foreach ($emails as $email) {
    $user = User::where('email', $email)->first();
    if ($user) {
        echo $email . ': FOUND' . PHP_EOL;
        echo '  id=' . $user->id . PHP_EOL;
        echo '  role=' . $user->role . PHP_EOL;
        echo '  status=' . $user->status . PHP_EOL;
        echo '  is_registered=' . ((int) $user->is_registered) . PHP_EOL;
        echo '  is_first_login=' . ((int) $user->is_first_login) . PHP_EOL;
        echo '  check_password123=' . (Hash::check('Password123', $user->password) ? 'yes' : 'no') . PHP_EOL;
        echo '  password_hash=' . $user->password . PHP_EOL;
    } else {
        echo $email . ': MISSING' . PHP_EOL;
    }
}
