<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user
        $adminRole = Role::query()->where('name', 'admin')->first();

        try {
            $user = new User;
            $user->email = 'admin@admin.com';
            $user->name = 'admin';
            $user->password = Hash::make('pass123!');
            $user->save();

            $user->role()->associate($adminRole);
        } catch (\Exception $exception) {
            \Log::error("USER SEEDER : {$exception->getMessage()}");
        }

    }
}
