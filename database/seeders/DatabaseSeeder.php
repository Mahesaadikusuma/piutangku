<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $user = User::create([
            'name' => 'Mahesa Adi Kusuma',
            'email' => 'esa@gmail.com',
            'password' => Hash::make('esa12345')
        ]);
        $user->assignRole($admin);
        User::factory(1000)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // php artisan db:seed --class=IndoRegionSeeder
    }
}
