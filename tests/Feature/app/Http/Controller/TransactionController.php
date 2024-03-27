<?php

namespace Tests\Feature\app\Http\Controller;

use App\Models\Retailer;
use App\Models\User;
use Tests\TestCase;

class TransactionController extends TestCase
{
    public function testUserShouldBeWrongProvider()
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer 5|Ba0Q4E9c3EMCjvSfsDXlsQGLFyDBvxRwsLk8NknNf622089d'
        ];

        $user = User::where('email', 'teste@teste.com.br')->first();

        $payload = [
            'provider' => 'nada',
            'payee_id' => 'nada',
            'amount' => 123
        ];

        $request = $this->actingAs($user, 'users')
            ->postJson(route('postTransaction'), $payload, $headers);


        $request->assertStatus(422);

        $request->assertJson([
            'errors' => [
                'provider' =>[
                    "The selected provider is invalid."
                ]
            ]
        ]);

    }

    public function testUserShouldBeExistingOnProviderToTransfer()
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer 5|Ba0Q4E9c3EMCjvSfsDXlsQGLFyDBvxRwsLk8NknNf622089d'
        ];

        $user = User::where('email', 'teste@teste.com.br')->first();

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
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer 5|Ba0Q4E9c3EMCjvSfsDXlsQGLFyDBvxRwsLk8NknNf622089d'
        ];

        $user = User::where('email', 'teste@teste.com.br')->first();

        $payload = [
            'provider' => 'user',
            'payee_id' => 'nada',
            'amount' => 123
        ];

        $request = $this->actingAs($user, 'users')
            ->postJson(route('postTransaction'), $payload, $headers);

        $request->assertStatus(404);

    }

    public function testRetailerShouldNotTransfer()
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer 6|DcWLSL1Vjt3dywOj4dG1iZDGwUpdFZ73c2WZTct6a0020439'
        ];

        $user = Retailer::where('email', 'pedro@pedro.com')->first();

        $payload = [
            'provider' => 'user',
            'payee_id' => 'nada',
            'amount' => 123
        ];

        $request = $this->actingAs($user, 'retailers')
            ->postJson(route('postTransaction'), $payload, $headers);

        $request->assertStatus(401);
        $request->assertJson(['errors' => ['main' => "Retailer is not authorized to make transactions"]]);
    }

    public function testUserShouldHaveMoneyToPerformSomeTransaction()
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer 5|Ba0Q4E9c3EMCjvSfsDXlsQGLFyDBvxRwsLk8NknNf622089d'
        ];

        $user = User::where('email', 'teste@teste.com.br')->first();

        $payload = [
            'provider' => 'user',
            'payee_id' => 1,
            'amount' => 8001
        ];

        $request = $this->actingAs($user, 'users')
            ->postJson(route('postTransaction'), $payload, $headers);

        $request->assertStatus(422);
        $request->assertJson([
            'errors' => [
                'main' => 'balance in the card is not enough'
            ]
        ]);
    }

    public function testUserTransferMoneyWithNotAuthorized()
    {

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer 5|Ba0Q4E9c3EMCjvSfsDXlsQGLFyDBvxRwsLk8NknNf622089d'
        ];

        $userPayer = User::where('email', 'teste@teste.com.br')->first();

        $userPayed = Retailer::where('email', 'pedro@pedro.com')->first();

        $payload = [
            'provider' => 'user',
            'payee_id' => $userPayed->id,
            'amount' => 100
        ];

        $request = $this->actingAs($userPayer, 'users')
            ->postJson(route('postTransaction'), $payload, $headers);

        $request->assertStatus(422);
        $request->assertJson([
            'errors' => [
                'main' => 'Service is not responding. Try again later.'
            ]
        ]);
    }

    public function testUserCanTransferMoney()
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer 1|6AvCnhovQSGNirzuXFcO0n5LTsDvcmGZUUHMxgs16314c329'
        ];

        $userPayer = User::where('email', 'teste@teste.com.br')->first();
//        $userPayer->wallet->deposit(1000);
//        $userPayer->wallet->withDraw(1000);

        $userPayed = Retailer::where('email', 'pedro@pedro.com')->first();

        $payload = [
            'provider' => 'user',
            'payee_id' => $userPayed->id,
            'amount' => 100
        ];

        $request = $this->actingAs($userPayer, 'users')
            ->postJson(route('postTransaction'), $payload, $headers);

        $request->assertStatus(200);
    }
}
