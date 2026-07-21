<?php

use App\Exceptions\Auth\InvalidCredentialsException;
use Illuminate\Http\Request;

it('renders itself as a 401 json response using the app error shape', function () {
    $exception = new InvalidCredentialsException;

    $response = $exception->render(Request::create('/'));

    expect($response->getStatusCode())->toBe(401)
        ->and($response->getData(true))->toBe([
            'success' => false,
            'message' => 'Credenciais inválidas.',
            'data' => null,
        ]);
});
