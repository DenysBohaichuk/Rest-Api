<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'position' => $this->positions->first()->name ?? null,
            'position_id' => (string) ($this->positions->first()->id ?? null),
            'registration_timestamp' => $this->created_at->timestamp,
            'photo' => asset('storage/' . $this->photo),
        ];
    }
}
