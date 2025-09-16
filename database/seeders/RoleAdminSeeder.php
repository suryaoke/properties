<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create([
            'name' => 'admin',

        ]);
        $agenRole = Role::create([
            'name' => 'agen',
        ]);

        $user = User::create([
            'name' => 'admin',
            'email'=> 'admin@gmail.com',
            'phone' => '083182718860',
            'photo' => 'contoh.png',
            'password' => bcrypt('12345678')

        ]);
        $user->assignRole($adminRole);

    }
}
