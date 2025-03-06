<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;



class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0"); // Tắt kiểm tra khóa ngoại
        DB::table('departments')->truncate(); // Xóa dữ liệu và reset ID
        DB::statement("SET FOREIGN_KEY_CHECKS=1"); // Bật lại kiểm tra khóa ngoại

        DB::table("departments")->insert([
            "name" => "Quản trị",
            
        ]);

        echo "seeding ok";

    }
}
