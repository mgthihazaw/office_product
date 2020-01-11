<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'module_serial' => $this->module_serial,
            'hdd_serial' => $this->hdd_serial,
            'hardware_id' => $this->hardware_id,
            'update_key' => $this->update_key,
            'tablet_key' => $this->tablet_key,
            'tabscreen_key' => $this->tabscreen_key,
            'client_remark' => $this->client_remark,
            'admin_remark' => $this->admin_remark,
            'paid' => $this->paid,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'username' => $this->user->name,
        ];
    }
}