<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

function testAttempt($email, $password) {
    $credentials = ['email' => $email, 'password' => $password];
    $attempt = Auth::attempt($credentials);
    echo "Attempting login for {$email} with '{$password}': ";
    echo $attempt ? "SUCCESS\n" : "FAILED\n";
    if ($attempt) {
        $user = Auth::user();
        echo "  id={$user->id} role={$user->role} status={$user->status} is_first_login=".((int)$user->is_first_login)."\n";
        Auth::logout();
    }
    $user = User::where('email', $email)->first();
    echo "  hash check with '{$password}': ".(Hash::check($password, $user->password) ? 'yes' : 'no')."\n";
}

testAttempt('admin@smartblood.ph', 'Password123');
testAttempt('admin@smartblood.ph', 'Password123!');
testAttempt('superadmin@bloodmap.ph', 'Password123!');
