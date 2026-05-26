<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $table = DB::table('roles');

        $table->delete();

        // جلب كل الصلاحيات من config
        $permissions = array_values(Config::get('permissions.permissions'));

        $table->insert([
            'id' => 1,
            'name' => 'superadmin',
            'permissions' => json_encode($permissions),
        ]);
    }
}
