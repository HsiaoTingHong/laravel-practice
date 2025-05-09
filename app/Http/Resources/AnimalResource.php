<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnimalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // 處理單一動物格式
        return [
            'id' => $this->id,
            'type_id' => $this->type_id,
            'type_name' => $this->type->name,
            'name' => $this->name,
            'birthday' => $this->birthday,
            'age' => $this->age,
            'area' => $this->area,
            'fix' => $this->fix,
            'description' => $this->description,
            'personality' => $this->personality,
            'created_at' => (string)$this->created_at, // (string)強制轉為字串型態
            'updated_at' => (string)$this->updated_at,
            'user_id' => $this->user_id,
        ];
    }
}
