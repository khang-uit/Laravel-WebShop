<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index(){
        $product_views = Product::orderBy('views', 'DESC')->take(20)->get();
        return view('admin.home', [
            'title' => 'Trang quản trị Admin',
            'product_views' => $product_views,
        ]);
    }

    public function daysOrder(){
        $from_date = Carbon::now('Asia/Ho_Chi_Minh')->subdays(60)->toDateString();
        $to_date = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

        $status = "Đã thanh toán";
        $result = Customer::selectRaw('sum(pty*price) as sum, customers.created_at')
            ->join('carts', 'customers.id', '=', 'carts.customer_id')
            ->whereBetween('customers.created_at', [$from_date, $to_date])
            ->where('customers.status', $status)
            ->groupBy('customers.created_at')
            ->orderBy('customers.created_at', 'ASC')
            ->get();
        foreach($result as $key => $val){
            $chart_data[] = array(
                'period' => $val->created_at,
                'sales' => $val->sum
            );
        }
        echo $data = json_encode($chart_data);

    }

    public function filterByDate(Request $request){
        $data = $request->all();
        $from_date = $data['from_date'];
        $to_date = $data['to_date'];
        $status = "Đã thanh toán";
        $result = Customer::selectRaw('sum(pty*price) as sum, customers.created_at')
            ->join('carts', 'customers.id', '=', 'carts.customer_id')
            ->whereBetween('customers.created_at', [$from_date, $to_date])
            ->where('customers.status', $status)
            ->groupBy('customers.created_at')
            ->orderBy('customers.created_at', 'ASC')
            ->get();
        foreach($result as $key => $val){
            $chart_data[] = array(
                'period' => $val->created_at,
                'sales' => $val->sum
            );
        }
        echo $data = json_encode($chart_data);

    }
}
