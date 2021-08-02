<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KeySeederValid extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('keys')->insert([
            'api_key' => env('TEST_MAILER_API_KEY'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
