<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $table = DB::table('admins');

        $table->delete();

        $table->insert([
            [
                'id' => 8,
                'super_admin' => 1,
                'name' => 'admin',
                'email' => 'sallam@gmail.com',
                'phone' => '123456',
                'role_id' => 1,
                'password' => Hash::make('sallam@gmail.com'),
            ],
        ]);
    }
}
