<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SignUpTest extends TestCase
{
    use DatabaseTransactions;

    public function testSignUp()
    {
        $user = factory(User::class)->make();
        $params = [
            'username' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
        ];
        $response = $this->postJson('/api/signup', $params);
        $response->assertStatus(200);
    }

    public function testSignUpDuplicatedEmailUsername()
    {
        $user = User::query()->first();
        $params = [
            'username' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
        ];
        $response = $this->postJson('/api/signup', $params);
        $response->assertStatus(422);
    }
}
