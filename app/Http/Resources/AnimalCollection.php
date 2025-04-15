<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AnimalCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // 處理動物集合格式
        return[
            // 使用 AnimalResource 類別轉換，使用他的靜態方法 collection 轉換集合內的每一筆資料
            'data' => AnimalResource::collection($this->collection)
        ];
    }
}
