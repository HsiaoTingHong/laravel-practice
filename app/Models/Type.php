<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Type extends Model
{
    use HasFactory;
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
        // 雖然我們在資料庫的部分，沒有做關聯，但在撰寫Model時可以先建立好模型關聯。方便用簡潔的方式取的關聯資料
        // 在 Type 的 Model 中加入與 Animal 的一對多關聯
        // 建立一個方法名為 animals()，實做呼叫自身 Model 類別的 hasMany() 方法來設定類別
        // hasMany(類別名稱，參照欄位，主鍵)
        return $this->hasMany('App\Models\Animal', 'type_id', 'id');
    }
}
