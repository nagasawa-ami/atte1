<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
        use RefreshDatabase, WithFaker;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'i-love-laravel'), // パスワードは bcrypt でハッシュ化する
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/'); // リダイレクト先はアプリケーションによって異なる
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('correct-password'),
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_new_user_registration()
    {
        $response = $this->post('/register', [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $password = 'i-love-laravel', // パスワードは明確に指定する
            'password_confirmation' => $password,
        ]);

        $this->assertCount(1, User::all()); // ユーザーが1つだけ存在することを確認
        $response->assertRedirect('/'); // リダイレクト先はアプリケーションによって異なる
    }
}
    

