<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StoreController extends Controller
{
    public function index()
    {
        $shops = Shop::orderByDesc('id')->get();
        return view('admin.store.list', [
            'title' => 'Danh sách chi nhánh',
            'shops' => $shops
        ]);
    }

    public function create()
    {
        return view('admin.store.add', [
            'title' => 'Thêm Chi Nhánh Mới',
        ]);
    }

    public function store(Request $request)
    {
        try {
            $request->except('_token');
            Shop::create($request->input());
            Session::flash('success', 'Thên chi nhánh thành công');
        } catch (\Exception $err) {
            Session::flash('error', 'Thêm chi nhánh lỗi');
            return false;
        }
        return redirect()->back();
    }

}
