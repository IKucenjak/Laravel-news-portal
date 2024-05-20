<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\TopHeadlinesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ArticlesController;

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
    return view('auth/register');
});

Route::middleware('auth')->group(function () {
    Route::get('/topNews', [TopHeadlinesController::class, 'paginated'])->name('top-headlines');
    Route::get('/articles', [ArticlesController::class, 'list'])->name('articles');
    Route::get('/dashboard', [NewsController::class, 'paginated'])->name('dashboard');
    Route::get('/articles/comments', [CommentController::class, 'viewComments'])->name('articles.view-comments');
    
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/comments/{comment_id}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{comment_id}', [CommentController::class, 'update'])->name('comments.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/settings', [SettingsController::class, 'show'])->name('settings.show');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/users/favorites/', [FavoritesController::class, 'create'])->name('favorites.add');
    Route::get('/users/favorites/', [FavoritesController::class, 'list'])->name('favorites.list');
    Route::delete('/users/favorites/{favourite_id}', [FavoritesController::class, 'delete'])->name('favorites.delete');
});

Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users/{user_id}', [AdminDashboardController::class, 'edit'])->name('admin.edit-user');
    Route::put('/admin/users/{user_id}', [AdminDashboardController::class, 'update'])->name('admin.update-user');
    Route::delete('/admin/users/{user_id}', [AdminDashboardController::class, 'deleteUser'])->name('admin.delete-user');
    
    Route::get('/admin/users/{user_id}/favorites', [AdminDashboardController::class, 'list'])->name('admin.user-favorites');
    Route::delete('/admin/users/{user_id}/favorites/{favorites_id}', [AdminDashboardController::class, 'deleteUserFavorite'])->name('delete-favorite');

    Route::get('/admin/users/{user_id}/comments', [CommentController::class, 'list'])->name('admin.user-comments');
    Route::delete('/comments/{comment_id}', [CommentController::class, 'delete'])->name('comments.delete');
});





require __DIR__.'/auth.php';