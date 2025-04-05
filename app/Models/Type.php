<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    /**
     * 可以被批量賦值的屬性。
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sort'
    ];
    /**
     * 取得 Type 的動物
     */
    public function animals()
    {
        // 在 Type 的 Model 中加入與 Animal 的一對多關聯
        // 建立一個方法名為 animals()，實做呼叫自身 Model 類別的 hasMany() 方法來設定類別
        // hasMany(類別名稱，參照欄位，主鍵)
        return $this->hasMany('App\Models\Animal', 'type_id', 'id');
    }
}
