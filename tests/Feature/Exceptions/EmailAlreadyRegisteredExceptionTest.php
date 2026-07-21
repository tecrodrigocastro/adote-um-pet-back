<?php

use App\Exceptions\Auth\EmailAlreadyRegisteredException;
use Illuminate\Http\Request;

it('renders itself as a 409 json response using the app error shape', function () {
    $exception = new EmailAlreadyRegisteredException('john@example.com');

    $response = $exception->render(Request::create('/'));

    expect($response->getStatusCode())->toBe(409)
        ->and($response->getData(true))->toBe([
            'success' => false,
            'message' => 'O email john@example.com já está em uso.',
            'data' => null,
        ]);
});
