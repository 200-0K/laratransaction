<?php

namespace Err0r\Laratransaction\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'transactionable' => $this->whenLoaded('transactionable'),
            'status' => new TransactionStatusResource($this->whenLoaded('status')),
            'type' => new TransactionTypeResource($this->whenLoaded('type')),
            'payment_method' => new PaymentMethodResource($this->whenLoaded('paymentMethod')),
            'amount' => $this->amount,
            'currency' => $this->currency,
            // 'gateway' => $this->gateway,
            // 'gateway_transaction_id' => $this->gateway_transaction_id,
            'metadata' => $this->metadata,
            'processed_at' => $this->processed_at,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // 'deleted_at' => $this->deleted_at,
        ];
    }
}
