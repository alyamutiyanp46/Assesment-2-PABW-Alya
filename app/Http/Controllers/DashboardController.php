<?php
namespace App\Http\Controllers;
use Modules\Pustaka\Models\Book;
use Modules\Pustaka\Models\Loan;
class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = request()->user();
        return view('dashboard', [
            'bookCount' => Book::count(),
            'loanCount' => $user->role === 'admin' ? Loan::where('status', 'menunggu')->count() : $user->loans()->whereIn('status', ['menunggu', 'disetujui'])->count(),
            'recentLoans' => ($user->role === 'admin' ? Loan::query() : $user->loans())->with(['book', 'user'])->latest()->take(5)->get(),
        ]);
    }
}
