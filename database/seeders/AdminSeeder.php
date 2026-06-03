<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@sik.ac.id'],
            [
                'name'     => 'Administrator',
                'email'    => 'admin@sik.ac.id',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            ]
        );
    }
}
