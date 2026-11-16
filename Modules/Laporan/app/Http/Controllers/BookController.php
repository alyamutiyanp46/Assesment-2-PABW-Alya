<?php

namespace Modules\Pustaka\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Pustaka\Http\Requests\SaveBookRequest;
use Modules\Pustaka\Models\Book;
use Modules\Pustaka\Models\Category;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $books = Book::with('category')->when($q, function ($query) use ($q) {
            $query->where(function ($search) use ($q) {
                $like = '%' . $q . '%';
                $search->where('title', 'like', $like)->orWhere('author', 'like', $like)->orWhere('isbn', 'like', $like);
            });
        })->latest()->paginate(8)->withQueryString();
        return view('books.index', ['books' => $books, 'categories' => Category::orderBy('name')->get(), 'q' => $q]);
    }

    // Simpan FormData dan kembalikan JSON agar jQuery/fetch dapat memperbarui daftar tanpa refresh.
    public function store(SaveBookRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('cover'))
            $data['cover_path'] = $request->file('cover')->store('covers', 'public');
        unset($data['cover']);
        return response()->json(['message' => 'Buku berhasil ditambahkan.', 'book' => Book::create($data)->load('category')], 201);
    }

    public function update(SaveBookRequest $request, Book $book)
    {
        $data = $request->validated();
        if ($request->hasFile('cover')) {
            if ($book->cover_path)
                Storage::disk('public')->delete($book->cover_path);
            $data['cover_path'] = $request->file('cover')->store('covers', 'public');
        }
        unset($data['cover']);
        $book->update($data);
        return response()->json(['message' => 'Data buku berhasil diperbarui.', 'book' => $book->fresh('category')]);
    }

    public function destroy(Book $book)
    {
        if ($book->loans()->exists())
            return response()->json(['message' => 'Buku memiliki riwayat peminjaman sehingga tidak dapat dihapus.'], 422);
        if ($book->cover_path)
            Storage::disk('public')->delete($book->cover_path);
        $book->delete();
        return response()->json(['message' => 'Buku berhasil dihapus.']);
    }
}
