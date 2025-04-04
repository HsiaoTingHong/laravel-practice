<?php
// Controllers (控制器)：負責處理請求及回應

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AnimalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 設定預設值
        $limit = $request->limit ?? 5; // 未設定預設值時為5

        // 建立查詢建構器，分段的方式撰寫sql語句
        $query = Animal::query();

        // 篩選欄位條件邏輯，如果有設定filters參數
        if (isset($request->filters)) {
            $filters = explode(',', $request->filters);
            foreach ($filters as $key => $filter) {
                list($key, $value) = explode(':', $filter);
                $query->where($key, 'like', "%$value%");
            }
        }

        //排列順序，定義sorts參數用來排序
        if (isset($request->sorts)) {
            $sorts = explode(',', $request->sorts);
            foreach ($sorts as $key => $sort) {
                list($key, $value) = explode(':', $sort);
                if ($value == 'asc' || $value == 'desc') {
                    $query->orderBy($key, $value);
                }
            }
        } else {
            // 如果沒有設定排序條件，預設ID由大到小
            // 使用 Model orderBy 方法加入 sql 語法排序條件，依照 ID 由大到小排序
            $query->orderBy('id', 'desc');
        }

        $animals = $query->paginate($limit) // 使用分頁方法，最多回傳$limit筆資料
            ->appends($request->query());

        return response($animals, Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) // 找到 store 方法
    {
        // Animal Model 有 create 寫好的方法，把請求的內容，用all方法轉為陣列，傳入 create 方法中。
        $animal = Animal::create($request->all());

        // 使用 refresh 方法再查詢一次資料庫，得到該筆的完整資料
        $animal = $animal->refresh();

        // 回傳 animal 產生出來的實體物件資料，第二個參數設定狀態碼，可以直接寫 201 表示創建成功的狀態螞或用下面 Response 功能 
        return response($animal, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function show(Animal $animal)
    {
        // 查詢單一資料
        return response($animal, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function edit(Animal $animal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request 預計修改的內容
     * @param  \App\Models\Animal  $animal 要修改哪一個 ID 的資料
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Animal $animal)
    {
        // 資料表找到的資料，使用 Laravel Model 的 update() 方法更新資料
        $animal->update($request->all());

        // 回傳資料，並給予 200 HTTP 狀態碼代表 OK
        return response($animal, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    // 路由有設定 animal 變數，這裡設定它是 Animal 模型，所以會自動找出該ID的實體物件
    public function destroy(Animal $animal)
    {
        // 把這個實體物件刪除
        $animal->delete();
        // 回傳 null 並且給予 204 狀態碼
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
