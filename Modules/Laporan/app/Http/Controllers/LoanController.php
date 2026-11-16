<?php
namespace Modules\Pustaka\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Pustaka\Http\Requests\SaveLoanRequest;
use Modules\Pustaka\Models\Book;
use Modules\Pustaka\Models\Loan;
use Illuminate\Support\Facades\DB;
class LoanController extends Controller
{
    public function index(Request $request)
    {
        $loans = Loan::with(['book', 'user'])->when($request->user()->role !== 'admin', fn($q) => $q->where('user_id', $request->user()->id))->latest()->paginate(10)->withQueryString();
        return view('loans.index', ['loans' => $loans, 'books' => Book::where('stock', '>', 0)->orderBy('title')->get()]);
    }
    public function store(SaveLoanRequest $request)
    {
        $data = $request->validated();
        $book = Book::findOrFail($data['book_id']);
        abort_if($book->stock < 1, 422, 'Stok buku sedang habis.');
        $data['user_id'] = $request->user()->id;
        $data['status'] = 'menunggu';
        $loan = Loan::create($data)->load(['book', 'user']);
        return $request->expectsJson() ? response()->json(['message' => 'Permintaan peminjaman berhasil dikirim.', 'loan' => $loan], 201) : back()->with('success', 'Permintaan peminjaman berhasil dikirim.');
    }
    public function update(SaveLoanRequest $request, Loan $loan)
    {
        abort_unless($request->user()->role === 'admin', 403);
        $status = $request->validated('status');
        DB::transaction(function () use ($loan, $status) {
            $loan = Loan::lockForUpdate()->findOrFail($loan->id);
            $old = $loan->status;
            if ($status === 'disetujui' && $old !== 'disetujui') {
                $book = Book::lockForUpdate()->findOrFail($loan->book_id);
                abort_if($book->stock < 1, 422, 'Stok buku habis.');
                $book->decrement('stock');
            }
            if ($old === 'disetujui' && in_array($status, ['dikembalikan', 'ditolak'], true)) {
                Book::whereKey($loan->book_id)->increment('stock');
            }
            if ($status === 'dikembalikan')
                $loan->returned_at = now();
            $loan->status = $status;
            $loan->save();
        });
        return $request->expectsJson() ? response()->json(['message' => 'Status peminjaman diperbarui.', 'loan' => $loan->fresh(['book', 'user'])]) : back()->with('success', 'Status peminjaman diperbarui.');
    }
    public function destroy(Request $request, Loan $loan)
    {
        abort_unless($request->user()->role === 'admin' || ($loan->user_id === $request->user()->id && $loan->status === 'menunggu'), 403);
        DB::transaction(function () use ($loan) {
            $loan = Loan::lockForUpdate()->findOrFail($loan->id);
            if ($loan->status === 'disetujui')
                Book::whereKey($loan->book_id)->increment('stock');
            $loan->delete();
        });
        return $request->expectsJson() ? response()->json(['message' => 'Data peminjaman dihapus.']) : back()->with('success', 'Data peminjaman dihapus.');
    }
}
