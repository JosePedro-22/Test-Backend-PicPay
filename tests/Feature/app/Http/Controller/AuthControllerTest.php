<?php

namespace Feature\app\Http\Controller;

use App\Models\User;
use Tests\CreatesApplication;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use CreatesApplication;

    public function testUserShouldNotAuthenticateWithWrongProvider()
    {
        //dados do usuarios
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer 2|SpQOAb0ZFvmsJ7pMvIsY5XcZiKl5qVuqPPQ0fFxn084726a0'
        ];

        $payload = [
            'email' => 'josepedro@gmail.com',
            'password' => 'password123'
        ];

        $request = $this->postJson(route('authenticate', ['provider' => 'nada']), $payload, $headers);

        $request->assertStatus(422);
        $request->assertJson(['errors' => ['main' => 'Wrong provider provided']]);
    }

    public function testUserShouldSendWrongPassword()
    {
        //dados do usuarios
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer 2|SpQOAb0ZFvmsJ7pMvIsY5XcZiKl5qVuqPPQ0fFxn084726a0'
        ];

        $payload = [
            'email' => 'josepedro@gmail.com',
            'password' => 'senha123'
        ];

        $request = $this->postJson(route('authenticate', ['provider' => 'user']), $payload, $headers);

        $request->assertStatus(401);
        $request->assertJson(['errors' => ['main' => 'Wrong Credentials']]);
    }

    public function testUserCanAuthenticate()
    {
        //dados do usuarios
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer 2|SpQOAb0ZFvmsJ7pMvIsY5XcZiKl5qVuqPPQ0fFxn084726a0'
        ];

        $payload = [
            'email' => 'josepedro@gmail.com',
            'password' => 'password123'
        ];

        $request = $this->postJson(route('authenticate', ['provider' => 'user']), $payload, $headers);

        $request->assertStatus(200);
        $request->assertJsonStructure(['token', 'expires_at', 'provider']);
    }
}
