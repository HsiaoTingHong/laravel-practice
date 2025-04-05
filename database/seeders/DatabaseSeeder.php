<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Animal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // 取消外鍵約束
        Schema::disableForeignKeyConstraints();

        // 清空 animals 資料表 ID 歸零
        Animal::truncate();

        // 清空 users 資料表 ID 歸零
        User::truncate();

        // 建立 5 筆會員測試資料
        User::factory(5)->create();
        
        // 建立 30 筆動物測試資料
        Animal::factory(30)->create();

        // 開啟外鍵約束
        Schema::enableForeignKeyConstraints();
    }
}
