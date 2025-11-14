<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Hash;

$user = App\Models\User::where('email', 'farid1306@gmail.com')->first();

if ($user) {
    echo 'Password hash: ' . $user->password . PHP_EOL;
    echo 'Check password123: ' . (Hash::check('password123', $user->password) ? 'matches' : 'does not match') . PHP_EOL;
} else {
    echo 'User not found' . PHP_EOL;
}
