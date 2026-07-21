<?php

use App\Exceptions\Auth\NoActiveTokenException;
use Illuminate\Http\Request;

it('renders itself as a 401 json response using the app error shape', function () {
    $exception = new NoActiveTokenException;

    $response = $exception->render(Request::create('/'));

    expect($response->getStatusCode())->toBe(401)
        ->and($response->getData(true))->toBe([
            'success' => false,
            'message' => 'Nenhum token de acesso ativo para esta sessão.',
            'data' => null,
        ]);
});
