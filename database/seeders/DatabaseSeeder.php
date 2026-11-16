<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Pustaka\Models\Book;
use Modules\Pustaka\Models\Category;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@exsample'], ['name' => 'Admin Demo', 'password' => Hash::make('password'), 'role' => 'admin']);
        User::updateOrCreate(['email' => 'user@exsample'], ['name' => 'User Warga Demo', 'password' => Hash::make('password'), 'role' => 'user']);
    }
}
