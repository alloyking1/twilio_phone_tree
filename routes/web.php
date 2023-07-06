<?php

use App\Http\Controllers\ProfileController;
use App\Http\Livewire\PhoneTreeLivewireController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::any('/call', [PhoneTreeLivewireController::class, 'call'])->name('phone-tree.call');
Route::any('/process-call', [PhoneTreeLivewireController::class, 'processCall'])->name('phone-tree.process-call');
Route::any('/speak-to-agent', [PhoneTreeLivewireController::class, 'speakToAgent'])->name('phone-tree.speak-to-agent');
Route::any('/agent-accept-call', [PhoneTreeLivewireController::class, 'agentAcceptCall'])->name('phone-tree.agent-accept-call');


require __DIR__ . '/auth.php';
