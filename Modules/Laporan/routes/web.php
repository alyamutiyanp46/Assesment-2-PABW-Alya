<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Modules\Laporan\Http\Controllers\BookController;
use Modules\Laporan\Http\Controllers\LoanController;
use Modules\Laporan\Models\Laporan;

Route::middleware(['web', 'auth'])->group(function () {
    // Sajikan sampul lewat Laravel agar gambar tidak bergantung pada symlink Windows.
    Route::get('/books/{book}/cover', function (Book $book) {
        abort_unless($book->cover_path && Storage::disk('public')->exists($book->cover_path), 404);
        return Storage::disk('public')->response($book->cover_path);
    })->name('books.cover');

    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::middleware('role:admin')->group(function () {
        Route::post('/books', [BookController::class, 'store'])->name('books.store');
        Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
        Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    });
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::patch('/loans/{loan}', [LoanController::class, 'update'])->middleware('role:admin')->name('loans.update');
    Route::delete('/loans/{loan}', [LoanController::class, 'destroy'])->name('loans.destroy');
});
