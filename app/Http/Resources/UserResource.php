<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'client@example.com'),
        new OA\Property(property: 'phone', type: 'string', example: '1234567890'),
        new OA\Property(property: 'photo_url', type: 'string', example: 'https://example.com/photo.jpg', nullable: true),
        new OA\Property(
            property: 'addresses',
            type: 'array',
            items: new OA\Items(ref: AddressResource::class),
        ),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2023-07-13T10:12:34.000000Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2023-07-13T10:12:34.000000Z'),
    ],
)]
final class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'photo_url' => $this->photo_url,
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
