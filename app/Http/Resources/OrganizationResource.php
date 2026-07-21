<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrganizationResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'org@example.com'),
        new OA\Property(property: 'organization_name', type: 'string', example: 'Abrigo Patinhas Felizes'),
        new OA\Property(property: 'cnpj', type: 'string', example: '12345678000199'),
        new OA\Property(property: 'responsible_name', type: 'string', example: 'Maria Souza'),
        new OA\Property(property: 'phone', type: 'string', example: '1234567890'),
        new OA\Property(property: 'mission_statement', type: 'string', example: 'Resgatar e encontrar lares para animais abandonados.'),
        new OA\Property(property: 'website', type: 'string', example: 'https://patinhasfelizes.org', nullable: true),
        new OA\Property(property: 'verified', type: 'boolean', example: false),
        new OA\Property(
            property: 'addresses',
            type: 'array',
            items: new OA\Items(ref: AddressResource::class),
        ),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2023-07-13T10:12:34.000000Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2023-07-13T10:12:34.000000Z'),
    ],
)]
final class OrganizationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'organization_name' => $this->organization_name,
            'cnpj' => $this->cnpj,
            'responsible_name' => $this->responsible_name,
            'phone' => $this->phone,
            'mission_statement' => $this->mission_statement,
            'website' => $this->website,
            'verified' => $this->verified,
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
