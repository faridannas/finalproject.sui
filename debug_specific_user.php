<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$users = User::where('email', 'farid1306@gmail.com')->get();

echo "Users with email farid1306@gmail.com:" . PHP_EOL;

foreach ($users as $user) {
    echo "ID: " . $user->id . ", Name: " . $user->name . ", Email: " . $user->email . ", Password: " . $user->password . ", Role: " . $user->role . PHP_EOL;
}
