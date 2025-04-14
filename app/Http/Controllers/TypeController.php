<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource. 查詢資料列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 分類資料量少直接全部輸出
        $types = Type::get();

        // 關聯查詢: 查詢狗狗分類中的所有動物
        // $animals = Type::find('1')->animals;

        return response([
            'data' => $types // 輸出使用 data 包住
            // 'data' => $animals // 輸出使用 data 包住
        ], Response::HTTP_OK);
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
    public function store(Request $request)
    {
        // 驗證資料
        $this->validate($request, [
            // 另一種驗證的寫法，使用陣列傳入驗證關鍵字
            'name' => [
                'required',
                'max:50',
                // type 資料表中的 name 欄位資料是唯一值
                Rule::unique('types', 'name')
            ],
            'sort' => 'nullable|integer',
        ]);

        // 如果沒有傳入 sort 欄位內容
        if (!isset($request->sort)) {
            // 找到目前資料表的排序欄位最大值
            $max = Type::max('sort');
            // 最大值加一寫入請求的資料中
            $request['sort'] = $max + 1;
        }

        // 寫入資料庫
        $type = Type::create($request->all());

        return response([
            'data' => $type
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource. 查詢單一資料
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        return response([
            'data' => $type
        ], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Type $type)
    {
        //
    }

    /**
     * Update the specified resource in storage. 修改資料
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        // 驗證資料
        $this->validate($request, [
            'name' => [
                'max:50',
                // 更新時排除自己的名稱後，檢查是否為唯一值
                Rule::unique('types', 'name')->ignore($type->name, 'name')
            ],
            'sort' => 'nullable|integer',
        ]);

        // 更新資料
        $type->update($request->all());

        return response([
            'data' => $type
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage. 刪除資料
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        // 把這個實體物件刪除
        $type->delete();
        // 回傳 null 並且給予 204 狀態碼
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
