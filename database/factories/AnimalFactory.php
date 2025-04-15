<?php

namespace Database\Factories;

use App\Models\Animal;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Animal>
 */
class AnimalFactory extends Factory
{
    protected $model = Animal::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            // type_id 隨機配對一個分類資料
            'type_id' => Type::all()->random()->id,
            'name' => $this->faker->name, // 隨機名稱
            'birthday' => $this->faker->date(), // 隨機日期
            'area' => $this->faker->city, // 隨機城市名稱
            'fix' => $this->faker->boolean, // 隨機布林值
            'description' => $this->faker->text, // 隨機一段內容
            'personality' => $this->faker->text, // 隨機一段內容
            'user_id' => User::all()->random()->id // 隨機綁定一位會員
        ];
    }
}
