<?php

namespace App\Http\Controllers\database\seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        Role::updateOrCreate(
            ['id' => 1],
            ['title' => 'Admin']
        );

        Role::updateOrCreate(
            ['id' => 2],
            ['title' => 'User']
        );
    }
}
