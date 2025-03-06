<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table("users")->truncate();

        DB::table("users")->insert([
            [
                "username" => "admin",
                "name" => "Admin",
                "email" => "Admin@gmail.com",
                "status" => 1,

                "password" => Hash::make("admin"),
            ],
            [
                "username" => "user1",
                "name" => "User 1",
                "email" => "user1@gmail.com",
                "status" =>2,
                "password" => Hash::make("password"),
            ]

        ]);
    }

}
