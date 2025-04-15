<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TypeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // 處理分類集合格式
        // return parent::toArray($request);
        return [
            // 使用集合的 transform 方法將每一筆資料一個一個處理
            'data' => $this->collection->transform(function ($type) {
                return [
                    'id' => $type->id,
                    'name' => $type->name,
                    'sort' => $type->sort,
                ];
            })
        ];
    }
}
