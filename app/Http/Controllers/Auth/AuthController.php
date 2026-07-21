<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\LogoutUserAction;
use App\Actions\Auth\RefreshUserTokenAction;
use App\Actions\Auth\RegisterOrganizationAction;
use App\Actions\Auth\RegisterUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterOrganizationRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: 'API para adoção de pets',
    title: 'Adote um Pet API',
    contact: new OA\Contact(email: 'redrodrigo.dev@gmail.com'),
    license: new OA\License(name: 'Apache 2.0', url: 'https://www.apache.org/licenses/LICENSE-2.0.html'),
    x: [
        'logo' => ['url' => 'https://via.placeholder.com/190x90.png?text=L5-Swagger'],
    ],
)]
class AuthController extends Controller
{
    #[OA\Post(
        path: '/api/auth/register',
        tags: ['Auth'],
        summary: 'Register a new client',
        description: 'Registers a new client and returns the created user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: RegisterRequest::class),
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Successful registration',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Usuário cadastrado com sucesso!'),
                        new OA\Property(property: 'data', ref: UserResource::class),
                    ],
                ),
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(property: 'errors', type: 'object', example: ['email' => ['The email field is required.']]),
                    ],
                ),
            ),
            new OA\Response(
                response: 409,
                description: 'Email already registered (concurrent registration)',
                content: new OA\JsonContent(ref: '#/components/schemas/ApiErrorResponse'),
            ),
        ],
    )]
    public function register(RegisterRequest $request, RegisterUserAction $action): JsonResponse
    {
        $user = $action->handle($request->toDto());

        return $this->success(new UserResource($user), 'Usuário cadastrado com sucesso!', 201);
    }

    #[OA\Post(
        path: '/api/auth/login',
        tags: ['Auth'],
        summary: 'Login client',
        description: 'Login a client and return an access token',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: LoginRequest::class),
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful login',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Login realizado com sucesso!'),
                        new OA\Property(
                            property: 'data',
                            properties: [
                                new OA\Property(property: 'user', ref: UserResource::class),
                                new OA\Property(property: 'access_token', type: 'string', example: '1|abcdef123456'),
                            ],
                            type: 'object',
                        ),
                    ],
                ),
            ),
            new OA\Response(
                response: 401,
                description: 'Invalid credentials',
                content: new OA\JsonContent(ref: '#/components/schemas/ApiErrorResponse'),
            ),
        ],
    )]
    public function login(LoginRequest $request, LoginUserAction $action): JsonResponse
    {
        $result = $action->handle($request->toDto());

        return $this->success([
            'user' => new UserResource($result->user),
            'access_token' => $result->token,
        ], 'Login realizado com sucesso!', 200);
    }

    #[OA\Post(
        path: '/api/auth/logout',
        tags: ['Auth'],
        summary: 'Logout client',
        description: 'Logout a client by invalidating the current access token',
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful logout',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Logout realizado com sucesso!'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items, example: []),
                    ],
                ),
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(ref: '#/components/schemas/ApiErrorResponse'),
            ),
        ],
    )]
    public function logout(Request $request, LogoutUserAction $action): JsonResponse
    {
        $action->handle($request->user());

        return $this->success([], 'Logout realizado com sucesso!', 200);
    }

    #[OA\Post(
        path: '/api/auth/refresh',
        tags: ['Auth'],
        summary: 'Refresh client access token',
        description: "Revokes the client's current access token and issues a new one",
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Access token refreshed',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Token atualizado com sucesso!'),
                        new OA\Property(
                            property: 'data',
                            properties: [
                                new OA\Property(property: 'access_token', type: 'string', example: '1|abcdef123456'),
                                new OA\Property(property: 'token_type', type: 'string', example: 'Bearer'),
                            ],
                            type: 'object',
                        ),
                    ],
                ),
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(ref: '#/components/schemas/ApiErrorResponse'),
            ),
        ],
    )]
    public function refresh(Request $request, RefreshUserTokenAction $action): JsonResponse
    {
        $token = $action->handle($request->user());

        return $this->success([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 'Token atualizado com sucesso!', 200);
    }

    #[OA\Get(
        path: '/api/auth/me',
        tags: ['Auth'],
        summary: 'Get authenticated user data',
        description: "Fetch the authenticated user's data",
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Authenticated user data',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Usuário autenticado'),
                        new OA\Property(property: 'data', ref: UserResource::class),
                    ],
                ),
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(ref: '#/components/schemas/ApiErrorResponse'),
            ),
        ],
    )]
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('addresses');

        return $this->success(new UserResource($user), 'Usuário autenticado', 200);
    }

    #[OA\Post(
        path: '/api/auth/register/organization',
        tags: ['Auth'],
        summary: 'Register a new organization',
        description: 'Registers a new organization account, pending verification',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: RegisterOrganizationRequest::class),
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Organization registered, pending verification',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Organização cadastrada com sucesso! Aguardando verificação.'),
                        new OA\Property(property: 'data', ref: OrganizationResource::class),
                    ],
                ),
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(property: 'errors', type: 'object', example: ['cnpj' => ['CNPJ já cadastrado']]),
                    ],
                ),
            ),
            new OA\Response(
                response: 409,
                description: 'Email or CNPJ already registered (concurrent registration)',
                content: new OA\JsonContent(ref: '#/components/schemas/ApiErrorResponse'),
            ),
        ],
    )]
    public function registerOrganization(RegisterOrganizationRequest $request, RegisterOrganizationAction $action): JsonResponse
    {
        $organization = $action->handle($request->toDto());

        return $this->success(new OrganizationResource($organization), 'Organização cadastrada com sucesso! Aguardando verificação.', 201);
    }
}
