<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'admin@seblakumi.ai';
$password = 'kakasayangumi';

$user = User::where('email', $email)->first();

if ($user) {
    $user->role = 'admin';
    $user->password = Hash::make($password);
    $user->save();
    echo "User {$email} updated. Role set to ADMIN. Password reset.\n";
} else {
    User::create([
        'name' => 'Super Admin',
        'email' => $email,
        'password' => Hash::make($password),
        'role' => 'admin',
    ]);
    echo "User {$email} created with role ADMIN.\n";
}
