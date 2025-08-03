<?php

use Illuminate\Support\Facades\Mail;
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

Mail::raw('Test email body', function($message) {
    $message->to('recipient@example.com')->subject('Test Email');
});
echo "Email sent!";