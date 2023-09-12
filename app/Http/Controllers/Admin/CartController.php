<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\CartService;
use App\Models\Cart;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CartController extends Controller
{
    protected $cart;
    public function __construct(CartService $cart){
        $this->cart = $cart;
    }
    

    public function index(){
        return view('admin.carts.customer', [
            'title' => 'Danh sach don dat hang',
            'customers' => $this->cart->getCustomer()
        ]);
    }

    public function show(Customer $customer){
        return view('admin.carts.detail', [
            'title' => 'Chi tiet don hang: ' . $customer->name,
            'customer' => $customer,
            'carts' => $customer->carts()->with(['product' => function ($query){
                $query->select('id', 'name', 'thumb');
            }])->get()
        ]);
    }

    public function printOrder($checkout_code){
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($this->print_order_convert($checkout_code));
        return $pdf->stream();
    }

    public function print_order_convert($checkout_code){
        $customer = Customer::where('id', $checkout_code)->get();
        
        $cart_details = Cart::where('customer_id', $checkout_code)->get();

        $order_details_product = Cart::with('product')->where('customer_id', $checkout_code)->get();
        $output = '
            <style>
                body{
                    font-family: DejaVu Sans;
                }
                .table-styling{
                    border: 1px solid #000;
                    width: 100%;
                }
                .table-styling thead tr th{
                    border: 1px solid #000;
                }

                .table-styling tbody tr td{
                    border: 1px solid #000;
                }

            </style>
            <h1>
                <center>Cửa hàng thú cưng Pet88</center>
            </h1>
            <p>Người đặt hàng</p>
            <table class="table table-styling table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Tên khách hàng</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Email</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
        ';

        foreach($customer as $key => $cus){
            $output .= '
                <tr>
                    <td>'. $cus->name .'</td>
                    <td>'. $cus->phone .'</td>
                    <td>'. $cus->address .'</td>
                    <td>'. $cus->email .'</td>
                    <td>'. $cus->content .'</td>
                </tr>
            ';
        }

        $output .= '
                </tbody>
            </table>
            <p>Danh sách sản phẩm</p>
            <table class="table table-styling table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Giá sản phẩm</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
        ';
        foreach($order_details_product as $key => $product){
            $output .= '
                <tr>
                    <td>'. $product->product->id .'</td>
                    <td>'. $product->product->name .'</td>
                    <td>'. $product->pty .'</td>
                    <td>'. $product->price .'</td>
                    <td>'. $product->price * $product->pty .'</td>
                </tr>
            ';
        }

        $output .= '
                </tbody>
            </table>
        ';
        return $output;       
    }
}
