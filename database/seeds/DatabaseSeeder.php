<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'api_user',
            'email' => str_random(10).'@gmail.com',
            'password' => bcrypt('secret'),
            'api_token' => '19p8KTInIk5K4a74RWGToyrqiQTKLViwG5Y3TauvxLTQ3HjzcOmDtcx6RV3j',
        ]);
    }
}
