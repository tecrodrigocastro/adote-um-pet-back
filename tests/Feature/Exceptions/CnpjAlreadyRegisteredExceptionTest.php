<?php

use App\Exceptions\Auth\CnpjAlreadyRegisteredException;
use Illuminate\Http\Request;

it('renders itself as a 409 json response using the app error shape', function () {
    $exception = new CnpjAlreadyRegisteredException('12345678000199');

    $response = $exception->render(Request::create('/'));

    expect($response->getStatusCode())->toBe(409)
        ->and($response->getData(true))->toBe([
            'success' => false,
            'message' => 'O CNPJ 12345678000199 já está em uso.',
            'data' => null,
        ]);
});
