<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = User::find(1);
if ($user) {
    $user->role = 'admin';
    $user->password = Hash::make('password'); // Reset password to 'password'
    $user->save();
    echo "User ID 1 updated to admin. Email: {$user->email}, Password: password\n";
} else {
    echo "User ID 1 not found. Creating admin user...\n";
    User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]);
    echo "Admin user created. Email: admin@example.com, Password: password\n";
}
