<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\UpdatePhotoRequest;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints for user management"
 * )
 */
class UserController extends Controller
{
    /**
     * @OA\Put(
     *     path="/api/users/profile",
     *     tags={"Users"},
     *     summary="Update user profile",
     *     description="Update the authenticated user's profile information",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="newpassword123"),
     *             @OA\Property(property="phone", type="string", example="1234567890"),
     *             @OA\Property(property="address", type="string", example="123 Main St"),
     *             @OA\Property(property="zip_code", type="string", example="12345-678"),
     *             @OA\Property(property="number_house", type="integer", example=123),
     *             @OA\Property(property="complement", type="string", example="Apt 4B")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Usuário atualizado com sucesso!"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *                 @OA\Property(property="phone", type="string", example="1234567890"),
     *                 @OA\Property(property="address", type="string", example="123 Main St"),
     *                 @OA\Property(property="zip_code", type="string", example="12345-678"),
     *                 @OA\Property(property="number_house", type="integer", example=123),
     *                 @OA\Property(property="complement", type="string", example="Apt 4B"),
     *                 @OA\Property(property="photo_url", type="string", example="photos/user-photo.jpg"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-01T10:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-12-01T10:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function updateUser(Request $request)
    {
        $user = $request->user();

        $data = $request->all();

        if ($request->has('password')) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return $this->success($user, 'Usuário atualizado com sucesso!', 200);
    }

    /**
     * @OA\Post(
     *     path="/api/users/photo",
     *     tags={"Users"},
     *     summary="Update user profile photo",
     *     description="Update the authenticated user's profile photo",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="photo_url",
     *                     type="string",
     *                     format="binary",
     *                     description="The profile photo file to upload"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Photo updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Foto atualizada com sucesso!"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *                 @OA\Property(property="phone", type="string", example="1234567890"),
     *                 @OA\Property(property="address", type="string", example="123 Main St"),
     *                 @OA\Property(property="zip_code", type="string", example="12345-678"),
     *                 @OA\Property(property="number_house", type="integer", example=123),
     *                 @OA\Property(property="complement", type="string", example="Apt 4B"),
     *                 @OA\Property(property="photo_url", type="string", example="photos/new-photo.jpg"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-01T10:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-12-01T10:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function updatePhoto(UpdatePhotoRequest $request)
    {
        $user = $request->user();

        $photo = $request->file('photo_url');

        $path = $photo->store('photos', 'public');

        $user->photo_url = $path;

        $user->save();

        return $this->success($user, 'Foto atualizada com sucesso!', 200);
    }
}
