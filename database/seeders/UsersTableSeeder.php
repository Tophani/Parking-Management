<?php

use Illuminate\Database\Seeder; 

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
   		DB::table('users')->insert([
            'name'     => 'Administrator',
            'email'    => 'admin@example.com',
            'password' => bcrypt('123456'),
            'photo'    => null,
            'remember_token'    => null,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => null,
            'status'        => 9, 
            'is_admin'      => 1, 
        ]);
    }
}
