<?php
// Models(模型)：跟資料庫溝通的橋樑，定義存取資料表、關聯處理、欄位等

namespace App\Models;

use Carbon\Carbon; // 操作時間的套件
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Animal extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * 可以被批量賦值的屬性。
     *
     * @var array
     */
    protected $fillable = [
        'type_id',
        'name',
        'birthday',
        'area',
        'fix',
        'description',
        'personality',
        'user_id',
    ];

    // 為什麼要設定 $fillable? 因為像AnimalController 這樣的撰寫方式，把整個使用者請求的陣列直接用create 的方法寫入，會有安全性的問題，所以必須用 $fillable來限制哪些欄位可以被批量寫入。

    // 假設今天是User被創建的動作，如果沒有這一層的保護，使用者的請求如果有包含權限的欄位值，就可以把User設定成管理員的權限。

    /**
     * 取得 Animal 的分類
     */
    public function type()
    {
        // Animal 的 Model 也要加上反向的關聯，這樣就可以從 Animal 這個方向去查詢 type
        // belongsTo(類別名稱，參照欄位，主鍵)
        return $this->belongsTo('App\Models\Type');
    }

    public function getAgeAttribute()
    {
        // 加入 getAgeAttribute()
        // 這是一個 Laravel 方便的功能，在 Model 寫一個方法，名稱命名為 get某某某Attribute 例如 getAgeAttribute。必須用駝峰式的命名方式，設定完成後可以使用 $animal->age 這個方法，Animal Model 的實體物件會自動訪問這個方法，運行完成後回傳結果
        $diff = Carbon::now()->diff($this->birthday);
        return "{$diff->y}歲{$diff->m}月";
    }
}
