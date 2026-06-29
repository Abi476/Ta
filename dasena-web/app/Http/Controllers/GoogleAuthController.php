<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
  public function redirect()
  {
    return Socialite::driver('google')->redirect();
  }

  public function callback()
  {
    try {
      $googleUser = Socialite::driver('google')->user();
      $user = User::updateOrCreate([
        'email' => $googleUser->email,
      ], [
        'name' => $googleUser->name,
        'google_id' => $googleUser->id,
        'password' => bcrypt(Str::random(16))
      ]);

      Auth::login($user);
      return redirect()->route('dashboard');

    } catch (\Exception $e) {
      return redirect('/login')->withErrors(['msg' => 'Terjadi kesalahan saat login dengan Google.']);
    }
  }
}