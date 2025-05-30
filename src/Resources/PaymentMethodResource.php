<?php

namespace Err0r\Laratransaction\Resources;

use Err0r\Laratransaction\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/** @mixin PaymentMethod */
class PaymentMethodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->when(! empty($this->description), $this->description),
        ];
    }
}
