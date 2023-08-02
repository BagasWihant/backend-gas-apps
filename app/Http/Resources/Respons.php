<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Respons extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public $success,$resource,$message;

    public function __construct($success,$message,$resource){
        parent::__construct($resource);
        $this->message = $message;
        $this->success = $success;

    }
    public function toArray(Request $request): array
    {
        return [
            'success'   => $this->success,
            'message'   => $this->message,
            'data'      => $this->resource
        ];
    }
}
