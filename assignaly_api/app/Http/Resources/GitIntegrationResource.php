<?php

namespace App\Http\Resources;

use App\Models\GitIntegration;
use Illuminate\Http\Resources\Json\JsonResource;

class GitIntegrationResource extends JsonResource
{
    /**
     * @mixin GitIntegration
     */
    public function toArray($request)
    {
        return [
            'username' => $this->username,
            'network_name' => $this->network->name,
        ];
    }
}
