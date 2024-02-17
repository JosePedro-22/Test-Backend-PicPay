<?php

namespace Tests\Feature\app\Http\Controller;

use App\Models\User;
use Tests\TestCase;

class TransactionController extends TestCase
{
    public function testUserShouldBeWrongProvider()
    {
        //dados do usuarios
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer 2|SpQOAb0ZFvmsJ7pMvIsY5XcZiKl5qVuqPPQ0fFxn084726a0'
        ];

        $user = User::where('email', 'josepedro@gmail.com')->first();

        $payload = [
            'provider' => 'nada',
            'payee_id' => 'nada',
            'amount' => 123
        ];

        $request = $this->actingAs($user, 'users')
            ->postJson(route('postTransaction'), $payload, $headers);

        $request->assertStatus(422);
        $request->assertJson(['errors' => ['main' => "The selected provider is invalid."]]);

    }
    public function testUserShouldBeExistingOnProviderToTransfer()
    {
        //dados do usuarios
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer 2|SpQOAb0ZFvmsJ7pMvIsY5XcZiKl5qVuqPPQ0fFxn084726a0'
        ];

        $user = User::where('email', 'josepedro@gmail.com')->first();

        $payload = [
            'provider' => 'user',
            'payee_id' => 'nada',
            'amount' => 123
        ];

        $request = $this->actingAs($user, 'users')
                        ->postJson(route('postTransaction'), $payload, $headers);

        $request->assertStatus(404);

    }

    public function testUserShouldBeAValidUserToTransfer()
    {
        //dados do usuarios
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer 2|SpQOAb0ZFvmsJ7pMvIsY5XcZiKl5qVuqPPQ0fFxn084726a0'
        ];

        $user = User::where('email', 'josepedro@gmail.com')->first();

        $payload = [
            'provider' => 'user',
            'payee_id' => 'nada',
            'amount' => 123
        ];

        $request = $this->actingAs($user, 'users')
            ->postJson(route('postTransaction'), $payload, $headers);

        $request->assertStatus(404);

    }

    public function testUserShouldNotBeToTransfer()
    {
        //dados do usuarios
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer 2|SpQOAb0ZFvmsJ7pMvIsY5XcZiKl5qVuqPPQ0fFxn084726a0'
        ];

        $user = User::where('email', 'josepedro@gmail.com')->first();

        $payload = [
            'provider' => 'user',
            'payee_id' => 1,
            'amount' => 123
        ];

        $request = $this->actingAs($user, 'retailer')
            ->postJson(route('postTransaction'), $payload, $headers);

        $request->assertStatus(404);
//        $request->assertJson(['errors' => ['main' => "Retailer is not authorized to make transactions"]]);
    }
}
