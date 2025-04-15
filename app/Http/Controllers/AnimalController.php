<?php
// Controllers (控制器)：負責處理請求及回應

namespace App\Http\Controllers;

use App\Http\Resources\AnimalResource;
use App\Http\Resources\AnimalCollection;
use App\Models\Animal;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache; // 使用 Laravel 的 Cache Facades 功能

class AnimalController extends Controller
{
    /**
     * Display a listing of the resource. 查詢資料列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 使用網址設定為快取檔案名稱
        // 取得網址
        $url = $request->url();

        // 取得 query 的參數，例如?limit=5&page=2
        $queryParams = $request->query();

        // 使用參數第一個英文字將參數順序排序
        ksort($queryParams);

        // 用 http_build_query 將查詢參數轉為字串
        $queryString = http_build_query($queryParams);

        // 組合完整網址
        $fullUrl = "{$url}?{$queryString}";

        // 使用 Laravel 快取方法檢查是否有快取紀錄
        if (Cache::has($fullUrl)) {
            // 使用 return 直接回傳快取資料，不做其他程式邏輯
            return Cache::get($fullUrl);
        }

        // 設定預設值
        $limit = $request->limit ?? 5; // 未設定預設值時為5

        // 建立查詢建構器，分段的方式撰寫sql語句
        // 1 - 加入關聯查詢，希望明確地建立查詢建構器實例並逐步添加條件，可以保留 ::query()
        // 加上預處理 with 方法並傳入字串 type 對應到 Animal Model 設定好的關聯方法，使用預處理可以減少與資料庫的溝通
        $query = Animal::query()->with('type');
        // $query->with('type');

        // 2 - 加入關聯查詢，直接使用模型方法
        // $query = Animal::with('type');

        // 3 - 查詢 type 為 1 的所有動物資料
        // $query = Animal::with('type')->where('type_id', 1);

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
            $query->orderBy('id', 'asc');
        }

        $animals = $query->paginate($limit) // 使用分頁方法，最多回傳$limit筆資料
            ->appends($request->query());

        // 沒有快取紀錄記住資料，並設定 60 秒過期，快取名稱使用網址命名
        // 快取未過期前都不會使用到資料庫設備資源
        return Cache::remember($fullUrl, 60, function () use ($animals) {
            // return response($animals, Response::HTTP_OK);
            return (new AnimalCollection($animals))
                ->response()
                ->setStatusCode(Response::HTTP_OK);
        });
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
     * Store a newly created resource in storage. 新增資料
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) // 找到 store 方法
    {
        // debug zh_TW
        // dd(app()->getLocale()); // "zh_TW" // app\Http\Controllers\AnimalController.php:100
        // return __('validation.required'); // validation.required
        // $translations = trans('validation');
        // dd($translations);

        // 資料驗證
        $this->validate($request, [
            'type_id' => 'nullable|integer', // 允許 null 或整數
            'name' => 'required|string|max:255', // 必填文字最多 255 字元
            'birthday' => 'nullable|date', // 允許 null 或日期格式，使用 PHP strtotime 檢查傳入的日期字串
            'area' => 'nullable|string|max:255', // 允許 null 或文字最多 255 字元
            'fix' => 'required|boolean', // 必填並且為布林值
            'description' => 'nullable', // 允許 null
            'personality' => 'nullable' // 允許 null
        ]);

        // Animal Model 有 create 寫好的方法，把請求的內容，用all方法轉為陣列，傳入 create 方法中。
        $animal = Animal::create($request->all());

        // 使用 refresh 方法再查詢一次資料庫，得到該筆的完整資料
        $animal = $animal->refresh();

        // 回傳 animal 產生出來的實體物件資料，第二個參數設定狀態碼，可以直接寫 201 表示創建成功的狀態螞或用下面 Response 功能 
        // return response($animal, Response::HTTP_CREATED);

        // 使用 Resource 統一輸出格式 - 單一動物格式
        return (new AnimalResource($animal))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource. 查詢單一資料
     *
     * @param  \App\Models\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function show(Animal $animal)
    {
        // 查詢單一資料

        // 關聯查詢: 查詢動物時，一併帶出分類資料
        // $animal = Animal::with('type')->find('1');

        // return response($animal, Response::HTTP_OK);

        // 使用 Resource 統一輸出格式 - 單一動物格式
        return (new AnimalResource($animal))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
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
     * Update the specified resource in storage. 修改資料
     *
     * @param  \Illuminate\Http\Request  $request 預計修改的內容
     * @param  \App\Models\Animal  $animal 要修改哪一個 ID 的資料
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Animal $animal)
    {
        // 資料驗證
        $this->validate($request, [
            'type_id' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'birthday' => 'nullable|date',
            'area' => 'nullable|string|max:255',
            'fix' => 'required|boolean',
            'description' => 'nullable',
            'personality' => 'nullable'
        ]);

        // 資料表找到的資料，使用 Laravel Model 的 update() 方法更新資料
        $animal->update($request->all());

        // 回傳資料，並給予 200 HTTP 狀態碼代表 OK
        // return response($animal, Response::HTTP_OK);

        // 使用 Resource 統一輸出格式 - 單一動物格式
        return (new AnimalResource($animal))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage. 刪除資料
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
