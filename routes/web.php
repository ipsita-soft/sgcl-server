<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderMail;
use App\Models\FeeReminders;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/reset-password', [UserAuthController::class,'reset'])->name('password.reset');

Route::get('/send-test-email', function () {
    $reminder = FeeReminders::create([
        'message' => 'Test reminder message',
        'date' => now()->addDays(7),
        'send_to' => 1 // Assuming user ID 1 exists
    ]);
    $user = User::find(2); // Assuming user ID 1 exists
    if ($user) {
        Mail::to($user->email)->send(new ReminderMail($reminder));
        return 'Test email sent';
    } else {
        return 'User not found';
    }
});
