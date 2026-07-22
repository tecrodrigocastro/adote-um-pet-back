<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AddressResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'street', type: 'string', example: 'Rua das Flores'),
        new OA\Property(property: 'neighborhood', type: 'string', example: 'Centro'),
        new OA\Property(property: 'number_house', type: 'integer', example: 123),
        new OA\Property(property: 'complement', type: 'string', example: 'Apto 4B', nullable: true),
        new OA\Property(property: 'zip_code', type: 'string', example: '12345-678'),
        new OA\Property(property: 'city', type: 'string', example: 'São Paulo'),
        new OA\Property(property: 'state', type: 'string', example: 'SP'),
    ],
)]
final class AddressResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'street' => $this->street,
            'neighborhood' => $this->neighborhood,
            'number_house' => $this->number_house,
            'complement' => $this->complement,
            'zip_code' => $this->zip_code,
            'city' => $this->city,
            'state' => $this->state,
        ];
    }
}
