<?php
use App\Models\User;
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = User::first();
if ($user) {
    $user->password = bcrypt('password');
    $user->save();
    echo "Password Reset for user: " . $user->email . "\n";
} else {
    echo "No user found.\n";
}
