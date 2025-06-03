<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotebookController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TestPageController;
use App\Http\Controllers\Admin\TestBuilderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\MaterialController;


Route::get('/', [NotebookController::class, 'index'])->name('home');


Route::get('/about', function () {
    return view('about', ['title' => 'О проекте']);
})->name('about');

Route::get('/vacancies', function () {
    return view('vacancies', ['title' => 'Вакансии']);
})->name('vacancies.index');

Route::get('/news', function () {
    return view('news', ['title' => 'Новости']);
})->name('news.index');


Route::get('/tests', [NotebookController::class, 'index'])->name('tests.index');
Route::get('/notebook', function() {
    return redirect('/');
})->name('notebook.index');


Route::get('/tests/{code}', [TestPageController::class, 'showDynamicTest'])->name('tests.dynamic');


Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/admin');
    }
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {
        return redirect()->intended('/admin');
    }

    return back()->withErrors(['email' => 'Неверные данные']);
})->name('login.post');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/results/{id}', [AdminController::class, 'show'])->name('admin.results.show');
    Route::delete('/admin/results/{id}', [AdminController::class, 'destroy'])->name('admin.results.destroy');
    Route::get('/admin/export', [AdminController::class, 'export'])->name('admin.export');


    Route::prefix('admin/tests')->name('admin.tests.')->group(function () {
        Route::get('/', [TestBuilderController::class, 'index'])->name('index');
        Route::post('/', [TestBuilderController::class, 'store'])->name('store');
        Route::get('/{test}', [TestBuilderController::class, 'show'])->name('show');
        Route::put('/{test}', [TestBuilderController::class, 'update'])->name('update');
        Route::delete('/{test}', [TestBuilderController::class, 'destroy'])->name('destroy');
        Route::post('/{test}/categories', [TestBuilderController::class, 'addCategory'])->name('add-category');
        Route::post('/{test}/questions', [TestBuilderController::class, 'addQuestion'])->name('add-question');
        Route::post('/{test}/questions/bulk', [TestBuilderController::class, 'bulkStoreQuestions'])->name('bulk-add-questions');
    });


    Route::delete('/admin/categories/{category}', [TestBuilderController::class, 'destroyCategory'])->name('admin.categories.destroy');
    Route::delete('/admin/questions/{question}', [TestBuilderController::class, 'destroyQuestion'])->name('admin.questions.destroy');


    Route::get('/admin/questions/{question}/data', [TestBuilderController::class, 'getQuestionData'])->name('admin.questions.data');
    Route::post('/admin/questions/{question}/update-text', [TestBuilderController::class, 'updateQuestionText'])->name('admin.questions.update-text');
    Route::put('/admin/questions/{question}', [TestBuilderController::class, 'updateQuestion'])->name('admin.questions.update');
});


Route::get('/profile', function() {
    return redirect('/admin');
})->name('profile');

Route::get('/download-materials', [MaterialController::class, 'downloadMaterials'])->name('download.materials');
Route::get('/qr/test/{testId}', [MaterialController::class, 'generateTestQr'])->name('qr.test');
