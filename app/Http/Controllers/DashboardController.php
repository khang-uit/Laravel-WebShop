<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('dashboard', [
                'title' => "Tìm cửa hàng"
            ]);
        }        
    }

    public function nearestDataDashboard(Request $request){

        $shops = Shop::select(['id', 'name'])
            ->when($request->long and $request->lat, function ($query) use ($request) {
                $query->addSelect(DB::raw("ST_Distance_Sphere(
                        POINT('$request->long', '$request->lat'), POINT(longitude, latitude)
                    ) as distance"))
                    ->orderBy('distance');
            })
            ->when($request->shopName, function ($query, $shopName) {
                $query->where('shops.name', 'like', "%{$shopName}%");
            })
            ->take(9)
            ->get();
            
        return response()->json([
            'shops' => $shops,
        ]);
    }
}