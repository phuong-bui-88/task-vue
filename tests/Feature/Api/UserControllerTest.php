<?php

namespace Tests\Feature\Api;

use App\Exceptions\InvalidLoginException;
use App\Models\Token;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase; // Ensure the database is refreshed after each test

    public function testLoginWithValidCredentials()
    {
        $user = User::factory(1)->create([
            'email' => 'test@example.com',
            'name' => 'admin',
            'password' => bcrypt('password'),
        ]);

        // Send a POST request to the login route with valid credentials
        $response = $this->json('POST', '/api/user/login', [
            'name' => 'test@example.com',
            'password' => 'password',
        ]);

        // Assert that the response has a 200 status code
        $response->assertStatus(200);

        // Send a POST request to the login route with valid credentials
        $response = $this->json('POST', '/api/user/login', [
            'name' => 'admin',
            'password' => 'password',
        ]);

        // Assert that the response contains a 'token' key
        $response->assertJsonStructure(['token']);
    }

    public function testLoginWithInvalidCredentials()
    {
        // Send a POST request to the login route with invalid credentials
        $response = $this->json('POST', '/api/user/login', [
            'name' => 'invalid@example.com',
            'password' => 'invalidpassword',
        ]);

        // Assert that the response has a 401 status code
        $response->assertStatus(404);
    }

    public function testStoreUser()
    {
        // Send a POST request to the store route with user data
        $response = $this->json('POST', '/api/users', [
            'name' => 'newuser',
            'email' => 'newuser@example.com',
            'password' => 'password',
        ]);

        // Assert that the response has a 201 status code
        $response->assertStatus(201);

        // Assert that the response contains user data
        $response->assertJsonStructure([
            'data' => [
                'id', 'name', 'created_at',
            ],
        ]);
    }

    public function testForgotPasswordWithValidEmail()
    {
        // Create a user for testing
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        // Send a POST request to the forgotPassword route with a valid email
        $response = $this->json('POST', '/api/user/forgot-password', [
            'email' => 'test@example.com',
        ]);

        // Assert that the response is 'ok'
        $response->assertSeeText('ok');
    }

    public function testForgotPasswordWithInvalidEmail()
    {
        // Send a POST request to the forgotPassword route with an invalid email
        $response = $this->json('POST', 'api/user/forgot-password', [
            'email' => 'nonexistent@example.com',
        ]);

        // Assert that the response has a 201 status code
        $response->assertStatus(404);
    }

    public function testResetPasswordWithValidToken()
    {
        // Create a user for testing
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        // Create a password reset token for the user
        $token = app('auth.password.broker')->createToken($user);
        Token::updateOrCreate(['key' => $user->email], ['token' => $token]);

        // Send a POST request to the resetPassword route with a valid token
        $response = $this->json('POST', '/api/user/reset-password', [
            'token' => $token,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        // Assert that the response is true (indicating a successful password reset)
        $response->assertExactJson([1]);
    }

    public function testResetPasswordWithInvalidToken()
    {
        // Send a POST request to the resetPassword route with an invalid token
        $response = $this->json('POST', '/api/user/reset-password', [
            'token' => 'invalidtoken',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        // Assert that the response has a 422 status code
        $response->assertStatus(404);
    }
}
