<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    protected $model = Admin::class;
    
    public function definition()
    {
        return [
            'name' => $this->faker->name,                           // ランダムな名前を生成
            'email' => $this->faker->unique()->safeEmail,           // ユニークなメールアドレスを生成
            'password' => bcrypt('password'),                      // ハッシュ化したパスワードを生成
            'remember_token' => Str::random(10),                   // ランダムなトークンを生成
        ];
    }
}
