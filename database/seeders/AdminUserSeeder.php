<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'name' => 'Muneeb Ansari',
            'email' => 'muneeb.ansari@gmail.com',
            'password' => Hash::make('pass2word'),
            'role' => 'admin'
        ]);
    }
}
