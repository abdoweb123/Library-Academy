<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RefTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $table = DB::table('ref_types');

        $table->delete();

        $now = now();

        $table->insert([
            [
                'id' => 1,
                'title' => 'Advance',
                'deleted_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'title' => 'Agst Reference',
                'deleted_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'title' => 'New Reference',
                'deleted_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'title' => 'On Account',
                'deleted_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
