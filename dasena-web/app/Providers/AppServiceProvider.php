<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
  public function register(): void
  {
    //
  }

  public function boot(): void
  {
    ResetPassword::toMailUsing(function (object $notifiable, string $token) {
      $url = url(route('password.reset', [
        'token' => $token,
        'email' => $notifiable->getEmailForPasswordReset(),
      ], false));

      return (new MailMessage)
        ->subject('Permintaan Reset Kata Sandi - Dasena')
        ->view('emails.reset-password', [
          'url' => $url,
          'user' => $notifiable 
        ]);
    });
  }
}