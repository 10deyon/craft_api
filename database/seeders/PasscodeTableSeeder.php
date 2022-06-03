<?php

namespace Database\Seeders;

use App\Models\Passcode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PasscodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Passcode::truncate();
        
        Passcode::create([
            "passcode" => Hash::make("qwertyuiop"),
        ]);
    }
}
