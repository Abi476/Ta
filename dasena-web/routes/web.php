<?php

use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\User\UjiKlasifikasiController;
use App\Http\Controllers\Admin\PreprocessingController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\User\HasilAnalisisController;
use App\Http\Controllers\Admin\KamusNormalisasiController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ContactMessageController;

Route::get('/', function () {
  return view('landing.index');
})->name('home');

// Route Contact Landing
Route::post('/contact/send', [LandingController::class, 'storeContact'])->name('contact.send');

// Route Google Auth
Route::middleware('guest')->group(function () {
  Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
  Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
});

// Route Reset Password 
Route::get('/forgot-password', function () {
  return view('forgot-password'); })->middleware('guest')->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('guest')->name('password.email');

// Route Umum
Route::middleware(['auth'])->group(function () {

  //Route Dashboard
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');  

  //Route Hasil Analisis
  Route::get('/hasilanalisis', [HasilAnalisisController::class, 'index'])->name('hasilanalisis');
  Route::get('/hasilanalisis/export', [HasilAnalisisController::class, 'export'])->name('hasilanalisis.export');
  Route::post('/hasilanalisis/prediksi-auto', [HasilAnalisisController::class, 'prediksiAuto'])->name('hasilanalisis.prediksi');

  //Route Uji klasifikasi
  Route::get('/uji-klasifikasi', [UjiKlasifikasiController::class, 'index'])->name('uji.klasifikasi');
  Route::post('/uji-klasifikasi/analisis', [UjiKlasifikasiController::class, 'analisis'])->name('uji.klasifikasi.analisis');
  
  // Route Menu Profile Bawaan Breeze
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Route Khusus Admin
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {

  //Route Dashboard Admin
  Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

  //Route Upfile
  Route::get('/upfile', [UploadController::class, 'index'])->name('upfile');
  Route::post('/upfile/process', [UploadController::class, 'process'])->name('upfile.process');
  Route::get('/upfile/download-template', [UploadController::class, 'downloadTemplate'])->name('upfile.template');
  Route::delete('/upfile/{id}', [UploadController::class, 'destroy'])->name('upfile.destroy');

  // Route Preprocessing
  Route::get('/preprocessing', [PreprocessingController::class, 'index'])->name('preprocessing'); 
  Route::post('/preprocessing/process', [PreprocessingController::class, 'process'])->name('preprocessing.process');
  Route::post('/preprocessing/filter-clean', [PreprocessingController::class, 'filterAndCleanData'])->name('preprocessing.filterClean');

  // Route Kamus
  Route::get('/kamus', [KamusNormalisasiController::class, 'index'])->name('kamus');
  Route::post('/kamus', [KamusNormalisasiController::class, 'store'])->name('kamus.store');
  Route::get('/kamus/template', [KamusNormalisasiController::class, 'downloadTemplateKamus'])->name('kamus.template');
  Route::post('/kamus/import', [KamusNormalisasiController::class, 'import'])->name('kamus.import');
  Route::put('/kamus/{id}', [KamusNormalisasiController::class, 'update'])->name('kamus.update');
  Route::delete('/kamus/{id}', [KamusNormalisasiController::class, 'destroy'])->name('kamus.destroy');

  //Route Manage User
  Route::get('/users', [UserManagementController::class, 'index'])->name('users');
  Route::patch('/users/{id}/role', [UserManagementController::class, 'updateRole'])->name('users.updateRole');
  Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');

  //Route Pesan Masuk
  Route::get('/messages', [ContactMessageController::class, 'index'])->name('messages.index');
  Route::get('/messages/{id}', [ContactMessageController::class, 'show'])->name('messages.show');
  Route::post('/messages/{id}/reply', [ContactMessageController::class, 'reply'])->name('messages.reply');
  Route::delete('/messages/{id}', [ContactMessageController::class, 'destroy'])->name('messages.destroy');

});

require __DIR__ . '/auth.php';