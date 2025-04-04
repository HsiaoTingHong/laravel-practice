<?php
// migrations 資要庫遷移
// 可以把建立資料表結構給這個檔案處理

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 運行這個檔案的時候會執行 up 方法裡面的程式
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->id(); // 使用遞增整數設定一個 ID 欄位
            $table->unsignedInteger('type_id')->comment('動物分類');
            $table->string('name')->comment('動物的暱稱');
            $table->date('birthday')->nullable()->comment('生日');
            $table->string('area')->nullable()->comment('所在地區');
            $table->boolean('fix')->default(false)->comment('結紮情形');
            $table->text('description')->nullable()->comment('簡單敘述');
            $table->text('personality')->nullable()->comment('動物個性');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * 執行恢復資料流程時就會跑 down 方法裡面的程式
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animals');
    }
};
