<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterOrganizationRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      x={
 *          "logo": {
 *              "url": "https://via.placeholder.com/190x90.png?text=L5-Swagger"
 *          }
 *      },
 *      title="Adote um Pet API",
 *      description="API para adoção de pets",
 *
 *      @OA\Contact(
 *          email="redrodrigo.dev@gmail.com"
 *      ),
 *
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     tags={"Auth"},
     *     summary="Register a new client",
     *     description="Registers a new client and returns the client data with an access token",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "phone", "address", "zip_code", "number_house", "complement", "photo_url"},
     *
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="client@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="phone", type="string", example="1234567890"),
     *             @OA\Property(property="address", type="string", example="123 Main St"),
     *             @OA\Property(property="zip_code", type="string", example="12345-678"),
     *             @OA\Property(property="number_house", type="integer", example=123),
     *             @OA\Property(property="complement", type="string", example="Apt 4B"),
     *             @OA\Property(property="photo_url", type="string", example="https://example.com/photo.jpg")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Successful registration",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", format="email", example="client@example.com"),
     *                 @OA\Property(property="phone", type="string", example="1234567890"),
     *                 @OA\Property(property="address", type="string", example="123 Main St"),
     *                 @OA\Property(property="zip_code", type="string", example="12345-678"),
     *                 @OA\Property(property="number_house", type="integer", example=123),
     *                 @OA\Property(property="complement", type="string", example="Apt 4B"),
     *                 @OA\Property(property="photo_url", type="string", example="https://example.com/photo.jpg"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-07-13T10:12:34.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-07-13T10:12:34.000000Z")
     *             ),
     *             @OA\Property(property="message", type="string", example="User registered successfully"),
     *             @OA\Property(property="status_code", type="integer", example=201)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", example={"email": {"The email field is required."}})
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            // Separar dados do usuário e endereço
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'phone' => $data['phone'],
                'photo_url' => $data['photo_url'] ?? null,
            ];

            $addressData = $data['address'];

            // Criar usuário
            $user = User::create($userData);

            // Criar endereço
            $user->addresses()->create($addressData);

            // Carregar usuário com endereço para retornar
            $user->load('addresses');

            DB::commit();

            return $this->success($user, 'Usuário cadastrado com sucesso!', 201);

        } catch (\Exception $e) {
            DB::rollback();

            return $this->error('Erro ao cadastrar usuário: '.$e->getMessage(), 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Auth"},
     *     summary="Login client",
     *     description="Login a client and return an access token",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *
     *             @OA\Property(property="email", type="string", format="email", example="client@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password")
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="access_token", type="string", example="your-access-token")
     *             ),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            return $this->success([
                'user' => $user,
                'access_token' => $token,
            ], 'Login realizado com sucesso!', 200);
        }

        return $this->error('Credenciais inválidas', 401);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     tags={"Auth"},
     *     summary="Logout client",
     *     description="Logout a client by invalidating the token",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful logout",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logout successful"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success([], 'Logout realizado com sucesso!', 200);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/refresh",
     *     tags={"Auth"},
     *     summary="Refresh client access token",
     *     description="Refreshes the client's access token",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Access token refreshed",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Token refreshed successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="access_token", type="string", example="your-new-access-token")
     *             ),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function refresh(Request $request)
    {
        $user = $request->user();

        $user->currentAccessToken()->delete();

        $newToken = $user->createToken('authToken')->plainTextToken;

        return $this->success([
            'success' => true,
            'message' => 'Token atualizado com sucesso!',
            'access_token' => $newToken,
            'token_type' => 'Bearer',
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/me",
     *     tags={"Auth"},
     *     summary="Get authenticated user data",
     *     description="Fetch the authenticated user's data",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Authenticated user data",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User data fetched successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", format="email", example="client@example.com"),
     *                 @OA\Property(property="phone", type="string", example="1234567890"),
     *                 @OA\Property(property="address", type="string", example="123 Main St"),
     *                 @OA\Property(property="zip_code", type="string", example="12345-678"),
     *                 @OA\Property(property="number_house", type="integer", example=123),
     *                 @OA\Property(property="complement", type="string", example="Apt 4B"),
     *                 @OA\Property(property="photo_url", type="string", example="https://example.com/photo.jpg"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-07-13T10:12:34.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-07-13T10:12:34.000000Z")
     *             ),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated."),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function me(Request $request)
    {
        $user = $request->user();
        $user->load('addresses');

        return $this->success($user, 'Usuário autenticado', 200);
    }

    /**
     * Register a new organization.
     */
    public function registerOrganization(RegisterOrganizationRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            // Separar dados da organização e endereço
            $organizationData = [
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'user_type' => \App\Enums\UserType::ORGANIZATION,
                'organization_name' => $data['organization_name'],
                'name' => $data['organization_name'], // Para compatibilidade
                'cnpj' => $data['cnpj'],
                'responsible_name' => $data['responsible_name'],
                'phone' => $data['phone'],
                'mission_statement' => $data['mission_statement'],
                'website' => $data['website'] ?? null,
                'social_media' => $data['social_media'] ?? null,
                'verified' => false, // Aguardando verificação
            ];

            $addressData = $data['address'];

            // Criar organização
            $organization = User::create($organizationData);

            // Criar endereço
            $organization->addresses()->create($addressData);

            // Carregar com relacionamentos
            $organization->load('addresses');

            DB::commit();

            return $this->success($organization, 'Organização cadastrada com sucesso! Aguardando verificação.', 201);

        } catch (\Exception $e) {
            DB::rollback();

            return $this->error('Erro ao cadastrar organização: '.$e->getMessage(), 400);
        }
    }
}
