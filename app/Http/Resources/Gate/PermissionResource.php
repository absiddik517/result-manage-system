<?php

namespace App\Http\Resources\Gate;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
      //return parent::toArray($request);
      return [
        'id' => $this->id,
                'name'       => $this->name,
        'guard_name'       => $this->guard_name,
        'group_name'       => $this->group_name,
        'delete_url' => route('gate.permission.delete', $this->id),
        'edit_url'   => route('gate.permission.update', $this->id),
      ];
    }
}
