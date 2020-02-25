<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    private const DEFAULT_STRUCTURE = ['data' => ['*' => 'access_token', 'refresh_token']];

    public function testLogin()
    {
        $pass = 'Secret12345!';
        $user = factory(User::class)->create(['verified' => true]);

        $params = ['email' => $user->email, 'password' => $pass];
        $r = $this->post('api/login', $params);
        $r->assertStatus(200);
        $r->assertJsonStructure(self::DEFAULT_STRUCTURE);
    }

    public function testLoginUnVerifiedAccount()
    {
        $pass = 'Secret12345!';
        $user = factory(User::class)->create();

        $params = ['email' => $user->email, 'password' => $pass];
        $r = $this->post('api/login', $params);
        $r->assertStatus(422);

        $data = $r->json();
        $this->assertEquals('INVALID_NOT_VERIFIED', data_get($data, 'error.errors.email.code'));
    }

    public function testLoginInvalidCredentials()
    {
        $pass = 'WrongPassword1!';
        $user = factory(User::class)->create(['verified' => true]);

        $params = ['email' => $user->email, 'password' => $pass];
        $r = $this->post('api/login', $params);
        $r->assertStatus(401);

        $data = $r->json();
        $this->assertEquals('INVALID_CREDENTIALS', data_get($data, 'error.code'));
    }
}
