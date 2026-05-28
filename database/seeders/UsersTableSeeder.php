<?php

namespace App\Http\Controllers\database\seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use function Database\Seeders\bcrypt;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['id' => 1],
            [
                'name'            => 'Admin',
                'email'           => 'admin@admin.com',
                'password'        => bcrypt('password'),
                'remember_token'  => null,
                'two_factor_code' => '',
            ]
        );
    }
}
