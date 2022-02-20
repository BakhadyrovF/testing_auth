<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user_success()
    {
        $user =  [
            "name" => "Firuzbek",
            "email" => "fbb@gmail.com",
            "password" => 111,
            "password_confirmation" => 111
        ];

        $response = $this->postJson(route("register"), $user);

        $response->assertCreated();
    }

    public function test_unproccessable_register_user()
    {
        $response = $this->postJson(route("register"), []);

        $response->assertUnprocessable();
    }

    public function test_login_user_success()
    {
        User::factory()->create([
            "name" => "Firuzbek",
            "email" => "fbb@gmail.com",
            "password" => bcrypt(111),
        ]);

        $response = $this->postJson(route("login"), ["email" => "fbb@gmail.com", "password" => 111]);

        $response->assertStatus(202);
    }

    public function test_unproccessable_login_user()
    {
        $response = $this->postJson(route("login"), []);

        $response->assertUnprocessable();
    }

    public function test_fail_to_login_user()
    {
        User::factory()->create([
            "name" => "Firuzbek",
            "email" => "fbb@gmail.com",
            "password" => bcrypt(111),
        ]);

        $response = $this->postJson(route("login"), ["email" => "fbb@gmail.com", "password" => 112]);


        $response->assertStatus(401);
    }

    public function test_logout_success()
    {
        $user = User::factory()->create([
            "name" => "Firuzbek",
            "email" => "fbb@gmail.com",
            "password" => bcrypt(111),
        ]);

        $token = $user->createToken("testToken")->plainTextToken;

        $response = $this->getJson(route("logout"), ["Authorization" => "Bearer $token"]);

        $response->assertSuccessful();
    }

    public function test_logout_fail()
    {
        $response = $this->getJson(route("logout"), []);

        $response->assertUnauthorized();
    }
}
