<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed phone_verified
 * @property mixed id
 * @property mixed email
 * @property mixed phone
 * @property mixed name
 * @property mixed last_name
 */
class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'phone' => [
                'number' => $this->phone,
                'verified' => $this->phone_verified,
            ],
            'name' => [
                'first' => $this->name,
                'last' => $this->last_name,
            ],
        ];
    }
}

/**
 * @SWG\Definition(
 *     definition="Profile",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="email", type="string"),
 *     @SWG\Property(property="phone", type="object",
 *         @SWG\Property(property="number", type="string"),
 *         @SWG\Property(property="verified", type="boolean"),
 *     ),
 *     @SWG\Property(property="name", type="object",
 *         @SWG\Property(property="first", type="string"),
 *         @SWG\Property(property="last", type="string"),
 *     ),
 * )
 */